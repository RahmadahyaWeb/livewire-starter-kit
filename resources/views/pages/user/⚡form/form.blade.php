<div class="space-y-6">

    <x-page-header :title="$user ? 'Edit User' : 'Create User'" :description="$user
        ? 'Update user information and manage assigned roles.'
        : 'Create a new user and assign roles to control system access.'" />

    <form wire:submit.prevent="save">
        <flux:card class="space-y-6">

            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input wire:model="email" type="email" />
                <flux:error name="email" />
            </flux:field>

            <div>
                <flux:label>Roles</flux:label>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                    @foreach ($allRoles as $role)
                        <flux:checkbox :label="$role" :value="$role" wire:model="roles" />
                    @endforeach
                </div>

                <flux:error name="roles" />
            </div>

            @if ($user)
                <div class="flex justify-end gap-2 items-center">
                    <flux:button type="button" variant="danger" wire:click="resetPassword">
                        Reset Password
                    </flux:button>

                    <flux:button type="submit" variant="primary">
                        Update
                    </flux:button>
                </div>
            @else
                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary">
                        Create
                    </flux:button>
                </div>
            @endif

        </flux:card>
    </form>

</div>
