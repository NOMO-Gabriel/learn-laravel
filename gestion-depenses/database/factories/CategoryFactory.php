<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Alimentation', 'Transport', 'Logement', 'Factures', 'Loisirs', 
            'Santé', 'Éducation', 'Habillement', 'Voyage', 'Cadeaux',
            'Salaire', 'Investissement', 'Remboursement', 'Vente', 'Autres revenus',
        ];
        
        return [
            'name' => $this->faker->randomElement($categories),
        ];
    }
    
    /**
     * Indicate that the category is for expenses.
     */
    public function forExpense(): static
    {
        $expenseCategories = [
            'Alimentation', 'Transport', 'Logement', 'Factures', 'Loisirs', 
            'Santé', 'Éducation', 'Habillement', 'Voyage', 'Cadeaux',
        ];
        
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement($expenseCategories),
        ]);
    }
    
    /**
     * Indicate that the category is for incomes.
     */
    public function forIncome(): static
    {
        $incomeCategories = [
            'Salaire', 'Investissement', 'Remboursement', 'Vente', 'Autres revenus',
        ];
        
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement($incomeCategories),
        ]);
    }
}