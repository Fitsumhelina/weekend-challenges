<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
      
        // Permissions
        Permission::firstOrCreate(['name' => 'create-contactinfo']);
        Permission::firstOrCreate(['name' => 'update-contactinfo']);
        Permission::firstOrCreate(['name' => 'delete-contactinfo']);
        Permission::firstOrCreate(['name' => 'create-service']);
        Permission::firstOrCreate(['name' => 'update-service']);
        Permission::firstOrCreate(['name' => 'delete-service']);

        // Roles
        $admin1 = Role::firstOrCreate(['name' => 'admin1']);
        $admin2 = Role::firstOrCreate(['name' => 'admin2']);

        // Assign permissions to roles
        $admin1->syncPermissions(['create-contactinfo', 'update-contactinfo', 'delete-contactinfo']);
        $admin2->syncPermissions(['create-service', 'update-service', 'delete-service']);
    }
}
