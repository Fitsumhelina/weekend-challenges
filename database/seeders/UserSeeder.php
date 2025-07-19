<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'member'];

        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                for ($i = 1; $i <= 3; $i++) {
                    $user = User::factory()->make([
                        'email' => "{$roleName}{$i}@gmail.com",
                        'name' => ucfirst($roleName) . " User {$i}",
                    ]);

                    $user->save();

                    $user->assignRole($role);
                }
            }
        }
    }
}
