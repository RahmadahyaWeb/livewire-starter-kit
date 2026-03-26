<div>
    <x-page-header title="Roles"
        description="Manage user roles and assign permissions to control access across the system."
        button-label="Add Role" :button-href="route('roles.create')" />

    <flux:card>
        <flux:table :paginate="$this->roles">
            <flux:table.columns>
                <flux:table.column>Role</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->roles as $role)
                    <flux:table.row :key="$role->id">
                        <flux:table.cell>
                            {{ $role->name }}
                        </flux:table.cell>

                        <flux:table.cell align="end">
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon:trailing="ellipsis-horizontal">
                                </flux:button>

                                <flux:menu>
                                    <flux:menu.item icon="pencil" href="{{ route('roles.edit', $role) }}">
                                        Edit
                                    </flux:menu.item>

                                    <flux:menu.separator />

                                    <flux:menu.item icon="trash" variant="danger"
                                        wire:click="confirmDelete({{ $role->id }})">
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

    <x-delete-modal name="delete-role" heading="Delete Role?"
        message="You're about to delete this role.<br>This action cannot be reversed." action="destroy" />

</div>
