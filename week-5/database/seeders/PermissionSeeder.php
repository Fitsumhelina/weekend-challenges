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
        Permission::firstOrCreate(['name' => 'create-contact-info']);
        Permission::firstOrCreate(['name' => 'edit-contact-info']);
        Permission::firstOrCreate(['name' => 'delete-contact-info']);
        Permission::firstOrCreate(['name' => 'create-service']);
        Permission::firstOrCreate(['name' => 'edit-service']);
        Permission::firstOrCreate(['name' => 'delete-service']);

        // Roles
        $admin1 = Role::firstOrCreate(['name' => 'admin1']);
        $admin2 = Role::firstOrCreate(['name' => 'admin2']);

        // Assign permissions to roles
        $admin1->syncPermissions(['create-contact-info', 'edit-contact-info', 'delete-contact-info']);
        $admin2->syncPermissions(['create-service', 'edit-service', 'delete-service']);
    }
}
