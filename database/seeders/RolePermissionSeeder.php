<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // ✅ Clear cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ✅ Permissions
        $permissions = [
            'view_product',
            'create_product',
            'edit_product',
            'delete_product',

            'view_user',
            'edit_user',
            'delete_user',
            'assign_role',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'api', // ✅ FIXED
            ]);
        }

        // dd("ds");
        // ✅ Roles (ALL api)
        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'api'
        ]);

        $manager = Role::firstOrCreate([
            'name' => 'Manager',
            'guard_name' => 'api'
        ]);

        $user = Role::firstOrCreate([
            'name' => 'User',
            'guard_name' => 'api'
        ]);

        // ✅ Assign permissions
        $admin->syncPermissions($permissions);

        $manager->syncPermissions([
            'view_product',
            'edit_product',
            'delete_product',
        ]);

        $user->syncPermissions([
            'view_product',
        ]);
    }
}