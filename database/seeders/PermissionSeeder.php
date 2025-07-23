<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define all permissions
        $permissions = [
            // income permissions
            'create income',
            'update income',
            'delete income',
            'view income',

            // expenses management
            'create expense',
            'update expense',
            'delete expense',
            'view expense',

            // Role management
            'create role',
            'view role',
            'update role',
            'delete role',

            // Permission management
            'view permission',
           

            // User management
            'create user',
            'view user',
            'update user',
            'delete user',

            #other permissions
            'view report',
            'view sidebar',
            'view dashboard',
            'export data',
            'view settings',
        ];

      

        // Create or update permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create or update roles
        $admin   = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $member  = Role::firstOrCreate(['name' => 'member', 'guard_name' => 'web']);


        // Assign permissions
        $admin->syncPermissions(Permission::all());

        $member->syncPermissions([
            'view expense',
            'view income',
        ]);

   
    }
}
