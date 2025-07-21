<?php

namespace Database\Factories;

use App\Models\kitat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class KitatFactory extends Factory
{
    protected $model = kitat::class;

    public function definition()
    {
        return [
            'description' => $this->faker->optional()->text(200),
            'amount' => $this->faker->optional()->randomFloat(2, 100, 10000),
            // Ensure only one unique interest value exists at a time
            'interest' => kitat::count() === 0
                ? $this->faker->unique()->numberBetween(1, 20)
                : kitat::first()->interest,
            'created_by' => \App\Models\User::inRandomOrder()->first()->id,
        ];
    }
}