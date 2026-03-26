<div>
    <x-page-header title="Users"
        description="Manage system users, assign roles, and control access permissions across the application."
        button-label="Add User" :button-href="route('users.create')" />

    <flux:card>
        <flux:table :paginate="$this->users">
            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Role</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->users as $user)
                    <flux:table.row :key="$user->id">
                        <flux:table.cell>
                            {{ $user->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            @foreach ($user->roles as $role)
                                {{ $role->name }}
                            @endforeach
                        </flux:table.cell>

                        <flux:table.cell align="end">
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon:trailing="ellipsis-horizontal">
                                </flux:button>

                                <flux:menu>
                                    <flux:menu.item icon="pencil" href="{{ route('users.edit', $user) }}">
                                        Edit
                                    </flux:menu.item>

                                    <flux:menu.separator />

                                    <flux:menu.item icon="trash" variant="danger"
                                        wire:click="confirmDelete({{ $user->id }})">
                                        Delete
                                    </flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <x-delete-modal name="delete-user" heading="Delete User?"
        message="You're about to delete this user.<br>This action cannot be reversed." action="destroy" />
</div>
