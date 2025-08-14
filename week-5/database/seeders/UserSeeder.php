<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
 
    public function run(): void
    {
        $user1 = User::create([
            'name' => 'Admin One',
            'email' => 'admin1@admin.com',
            'password' => bcrypt('12345678'),
        ]);
        $user1->assignRole('admin1');

        $user2 = User::create([
            'name' => 'Admin Two',
            'email' => 'admin2@admin.com',
            'password' => bcrypt('12345678'),
        ]);
        $user2->assignRole('admin2');
    }

}
