<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {

            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            // ========================
            // PERMISSIONS
            // ========================
            $permissions = [
                'user.view',
                'user.create',
                'user.update',
                'user.delete',

                'role.view',
                'role.create',
                'role.update',
                'role.delete',
            ];

            foreach ($permissions as $perm) {
                Permission::firstOrCreate(['name' => $perm]);
            }

            // ========================
            // ROLES
            // ========================
            $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
            $salesRole = Role::firstOrCreate(['name' => 'sales']);

            // Super Admin → semua permission
            $superAdminRole->syncPermissions(Permission::all());

            // Sales → limited permission
            $salesRole->syncPermissions([]);

            // ========================
            // USERS
            // ========================
            $superAdmin = User::updateOrCreate(
                ['email' => 'superadmin@mail.com'],
                [
                    'name' => 'Super Admin',
                    'password' => Hash::make('password'),
                ]
            );

            $sales = User::updateOrCreate(
                ['email' => 'sales@mail.com'],
                [
                    'name' => 'Sales',
                    'password' => Hash::make('password'),
                ]
            );

            // ========================
            // ASSIGN ROLE
            // ========================
            $superAdmin->syncRoles([$superAdminRole]);
            $sales->syncRoles([$salesRole]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
