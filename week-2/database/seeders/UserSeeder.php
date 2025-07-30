<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::create([
            'name' => 'Abel',
            'email' => 'abel@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        if ($adminRole) {
            $admin->assignRole($adminRole);
        }

        // Create member user
        $memberRole = Role::where('name', 'member')->first();
        $member = User::create([
            'name' => 'Fitsum',
            'email' => 'fitsum@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        if ($memberRole) {
            $member->assignRole($memberRole);
        }
    }
}

