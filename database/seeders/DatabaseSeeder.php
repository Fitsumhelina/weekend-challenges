<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  
    public function run(): void
    {
        // User::factory(10)->create();

       $this->call([
           RoleSeeder::class,
           AdminSeeder::class,
           MemberSeeder::class,
       ]);
    }
}
