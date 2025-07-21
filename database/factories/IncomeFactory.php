<?php

namespace Database\Factories;

use App\Models\Income;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeFactory extends Factory
{
    protected $model = Income::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'source' => \App\Models\User::inRandomOrder()->first()->id,
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['paid', 'pending']),
            'amount' => $this->faker->randomFloat(2, 100, 5000),
            'created_by' => \App\Models\User::inRandomOrder()->first()->id,
            'updated_by' => \App\Models\User::inRandomOrder()->first()->id,
        ];
    }
}
