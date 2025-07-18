<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    
    public function run(): void
    {
        $users = [
            ['name' => 'Fitsum', 'email' => 'fitsum@example.com'],
            ['name' => 'Abel', 'email' => 'abel@example.com'],
            ['name' => 'Marta', 'email' => 'marta@example.com'],
            ['name' => 'Selam', 'email' => 'selam@example.com'],
            ['name' => 'Helen', 'email' => 'helen@example.com'],
            ['name' => 'Jonas', 'email' => 'jonas@example.com'],
            ['name' => 'Samuel', 'email' => 'samuel@example.com'],
            ['name' => 'Ruth', 'email' => 'ruth@example.com'],
            ['name' => 'Daniel', 'email' => 'daniel@example.com'],
            ['name' => 'Sara', 'email' => 'sara@example.com'],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt('1234'),
            ]);
            $user->assignRole('member');    
        }
    }
}
