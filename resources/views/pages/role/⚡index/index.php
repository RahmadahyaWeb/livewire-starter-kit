<?php

use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

new #[Title('Roles & Permissions')] class extends Component
{
    use AuthorizesCrud;
    use WithoutUrlPagination, WithPagination;

    public $deleteId;

    public function mount()
    {
        $this->authorizeIndex(Role::class);
    }

    #[Computed()]
    public function roles()
    {
        return Role::paginate(10);
    }

    public function confirmDelete(int $id): void
    {
        $role = Role::findOrFail($id);

        $this->authorizeDelete($role);

        $this->deleteId = $id;

        $this->modal('delete-role')->show();
    }

    public function destroy()
    {
        $this->transaction(function () {
            $role = Role::findOrFail($this->deleteId);

            $this->authorizeDelete($role);

            $role->delete();

            Flux::toast(
                heading: 'Success',
                text: 'Role deleted successfully',
                variant: 'success'
            );

            $this->modal('delete-role')->close();

        });

    }
};
