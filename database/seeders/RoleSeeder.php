<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
 
    public function run(): void
    {
        $role = Role::create([
            'name'=> 'Admin',
        ]);

        $role = Role::create([
            'name' => 'member',
        ]);

        $permission = Permission::create(['name' => 'create post']);
        $permission = Permission::create(['name' => 'edit post']);
        $permission = Permission::create(['name' => 'delete post']);
        $permission = Permission::create(['name' => 'view post']);
    }
}
