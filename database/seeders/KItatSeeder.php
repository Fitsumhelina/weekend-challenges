<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\kitat;

class KItatSeeder extends Seeder
{
    public function run()
    {
        kitat::factory()->count(5)->create();
    }
}