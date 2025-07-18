<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'category' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'created_by' => 'abebe',
            'updated_by' => 'abebe'
        ];
    }
}
