<?php

use App\Models\User;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Title('Users')] class extends Component
{
    use AuthorizesCrud;
    use WithoutUrlPagination, WithPagination;

    public $deleteId;

    public function mount()
    {
        $this->authorizeIndex(User::class);
    }

    #[Computed()]
    public function users()
    {
        return User::with(['roles'])->paginate(10);
    }

    public function confirmDelete(int $id): void
    {
        $user = User::findOrFail($id);

        $this->authorizeDelete($user);

        $this->deleteId = $id;

        $this->modal('delete-user')->show();
    }

    public function destroy()
    {
        $this->transaction(function () {
            $user = User::findOrFail($this->deleteId);

            $this->authorizeDelete($user);

            if ($user->id === Auth::id()) {
                throw new Exception('You cannot delete your own account');
            }

            if ($user->hasRole('super_admin')) {
                throw new Exception('Super admin cannot be deleted');
            }

            $user->delete();

            Flux::toast(
                heading: 'Success',
                text: 'User deleted successfully',
                variant: 'success'
            );

            $this->modal('delete-user')->close();
        });
    }
};
