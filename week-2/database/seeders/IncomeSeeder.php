<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Income;
use Database\Factories\IncomeFactory;

class IncomeSeeder extends Seeder
{
    public function run(): void
    {
        Income::factory()->count(20)->create();
    }
}
