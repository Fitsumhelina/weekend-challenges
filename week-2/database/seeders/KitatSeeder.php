<?php

namespace Database\Seeders;

use App\Models\Kitat;
use Database\Factories\KitatFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KitatSeeder extends Seeder
{
    public function run(): void
    {
    Kitat::create([
        'interest_rate' => 50,
    ]);
}
}
