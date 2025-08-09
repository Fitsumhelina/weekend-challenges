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
        Permission::firstOrCreate(['name' => 'edit-contact-info']);
        Permission::firstOrCreate(['name' => 'edit-service']);

        // Roles
        $admin1 = Role::firstOrCreate(['name' => 'admin1']);
        $admin2 = Role::firstOrCreate(['name' => 'admin2']);

        // Assign permissions to roles
        $admin1->givePermissionTo('edit-service');
        $admin2->givePermissionTo('edit-contact-info');
    }
}
