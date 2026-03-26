<?php

use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

new class extends Component
{
    use AuthorizesCrud, AuthorizesRequests;

    public ?Role $role = null;

    public $name;

    public $permissions = [];

    public $allPermissions;

    public $groupedPermissions;

    public function mount(?Role $role = null)
    {
        $this->allPermissions = Permission::pluck('name');

        $this->groupedPermissions = $this->allPermissions
            ->groupBy(function ($permission) {
                return explode('.', $permission)[0];
            })
            ->map(function ($items) {
                return $items->values();
            });

        if ($role && $role->exists) {
            $this->authorizeUpdate($role);

            $this->role = $role;
            $this->name = $role->name;

            $this->permissions = $role->permissions->pluck('name')->toArray();
        } else {
            $this->authorizeStore(Role::class);
        }
    }

    public function save()
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                $this->role
                    ? 'unique:roles,name,'.$this->role->id
                    : 'unique:roles,name',
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        return $this->transaction(function () {

            if ($this->role) {
                $this->authorizeUpdate($this->role);

                $this->role->update([
                    'name' => $this->name,
                ]);

                $this->role->syncPermissions($this->permissions);

                Flux::toast(
                    heading: 'Success',
                    text: 'Role updated successfully',
                    variant: 'success'
                );
            } else {
                $this->authorizeStore(Role::class);

                $role = Role::create([
                    'name' => $this->name,
                ]);

                $role->syncPermissions($this->permissions);

                Flux::toast(
                    heading: 'Success',
                    text: 'Role created successfully',
                    variant: 'success'
                );
            }

            $this->redirect(route('roles.index'), navigate: true);
        });
    }
};
