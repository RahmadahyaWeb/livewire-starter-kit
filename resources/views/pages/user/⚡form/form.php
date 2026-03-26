<?php

use App\Models\User;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

new class extends Component
{
    use AuthorizesCrud, AuthorizesRequests;

    public ?User $user = null;

    public $name;

    public $email;

    public $roles = [];

    public $allRoles;

    public function mount(?User $user = null)
    {
        $this->allRoles = Role::pluck('name');

        if ($user && $user->exists) {
            $this->authorizeUpdate($user);

            $this->user = $user;

            $this->name = $user->name;
            $this->email = $user->email;
            $this->roles = $user->roles->pluck('name')->toArray();
        } else {
            $this->authorizeStore(User::class);
        }
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                $this->user
                    ? 'unique:users,email,'.$this->user->id
                    : 'unique:users,email',
            ],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        return $this->transaction(function () {

            if ($this->user) {
                $this->authorizeUpdate($this->user);

                $this->user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                ]);

                $this->user->syncRoles($this->roles);

                Flux::toast(
                    heading: 'Success',
                    text: 'User updated successfully',
                    variant: 'success'
                );
            } else {
                $this->authorizeStore(User::class);

                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make('password'),
                ]);

                $user->syncRoles($this->roles);

                Flux::toast(
                    heading: 'Success',
                    text: 'User created successfully',
                    variant: 'success'
                );
            }

            $this->redirect(route('users.index'), navigate: true);
        });
    }

    public function resetPassword()
    {
        return $this->transaction(function () {

            if (! $this->user) {
                throw new Exception('User not found');
            }

            $this->authorizeUpdate($this->user);

            $this->user->update([
                'password' => Hash::make('password'),
            ]);

            Flux::toast(
                heading: 'Success',
                text: 'Password has been reset to default',
                variant: 'success'
            );
        });
    }
};
