<?php

namespace Database\Factories;

use App\Models\Income;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Income::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Descriptions possibles pour les revenus
        $descriptions = [
            'Salaire mensuel',
            'Prime exceptionnelle',
            'Remboursement frais médicaux',
            'Vente d\'objets sur leboncoin',
            'Dividendes',
            'Intérêts bancaires',
            'Remboursement ami',
            'Allocation',
            'Cadeau reçu',
            'Revenu locatif',
            'Freelance',
        ];
        
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory()->forIncome(),
            'amount' => $this->faker->randomFloat(2, 100, 3000),
            'description' => $this->faker->randomElement($descriptions),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
    
    /**
     * Indicate that the income is a salary.
     */
    public function salary(): static
    {
        $salaryCategory = Category::firstOrCreate(['name' => 'Salaire']);
        
        return $this->state(fn (array $attributes) => [
            'category_id' => $salaryCategory->id,
            'amount' => $this->faker->randomFloat(2, 1500, 4000),
            'description' => 'Salaire mensuel',
        ]);
    }
    
    /**
     * Indicate that the income is recent (last month).
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}