<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_user = Role::create(['name' => 'default']);
        $admin = Role::create(['name' => 'admin']);
        $moderator = Role::create(['name' => 'moderator']);

        $manage_user_permissions = Permission::create(['name' => 'manage users']);
        $create_product = Permission::create(['name' => 'create product']);
        $update_product = Permission::create(['name' => 'update product']);
        $delete_product = Permission::create(['name' => 'delete product']);

        $admin_permissions = [
            $manage_user_permissions,
            $create_product,
            $update_product,
            $delete_product
        ];

        $moderator_permissions = [$create_product];

        $admin->syncPermissions($admin_permissions);
        $moderator->syncPermissions($moderator_permissions);
    }
}
