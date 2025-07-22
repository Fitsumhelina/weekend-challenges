<?php

namespace Database\Factories;

use App\Models\Kitat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class KitatFactory extends Factory
{
    protected $model = Kitat::class;

    public function definition()
    {
        return [
            'description' => $this->faker->optional()->text(200),
            'amount' => $this->faker->optional()->randomFloat(2, 100, 10000),
            'interest_rate' => $this->faker->unique()->numberBetween(1, 20),
            'created_by' => \App\Models\User::inRandomOrder()->first()->id,

        ];
    }
}