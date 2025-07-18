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
            'amount' => $this->faker->randomFloat(2, 100, 5000),
            'source' => $this->faker->company(),
            'description' => $this->faker->paragraph(),
            'created_by' => 'abebe',
            'updated_by' => 'abebe'
        ];
    }
}
