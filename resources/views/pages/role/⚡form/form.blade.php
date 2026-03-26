<div class="space-y-6">

    <x-page-header :title="$role ? 'Edit Role' : 'Create Role'" :description="$role
        ? 'Update role details and adjust assigned permissions.'
        : 'Define a new role and assign permissions to control user access within the system.'" />

    <form wire:submit.prevent="save">
        <flux:card class="space-y-6">

            <flux:field>
                <flux:label>Role</flux:label>

                <flux:input wire:model="name" type="text" />

                <flux:error name="name" />
            </flux:field>

            <div>
                <flux:label>Permissions</flux:label>

                <div class="space-y-4 mt-2">
                    @foreach ($groupedPermissions as $group => $permissions)
                        <div>
                            <div class="font-semibold capitalize mb-2">
                                {{ $group }}
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach ($permissions as $permission)
                                    <flux:checkbox :label="$permission" :value="$permission"
                                        wire:model="permissions" />
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end">
                <flux:button type="submit" variant="primary">
                    {{ $role ? 'Update' : 'Create' }}
                </flux:button>
            </div>

        </flux:card>
    </form>

</div>
