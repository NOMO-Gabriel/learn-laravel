<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Expense::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Descriptions possibles pour les dépenses
        $descriptions = [
            'Courses au supermarché',
            'Restaurant',
            'Essence',
            'Transport en commun',
            'Loyer',
            'Électricité',
            'Internet et téléphone',
            'Assurance',
            'Médicaments',
            'Consultation médicale',
            'Cinéma',
            'Livres',
            'Vêtements',
            'Chaussures',
            'Cadeau anniversaire',
            'Matériel informatique',
            'Abonnement streaming',
            'Cours en ligne',
            'Coiffeur',
            'Entretien voiture',
        ];
        
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory()->forExpense(),
            'amount' => $this->faker->randomFloat(2, 5, 500),
            'description' => $this->faker->randomElement($descriptions),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
    
    /**
     * Indicate that the expense is recent (last month).
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }
    
    /**
     * Indicate that the expense is for a specific category.
     */
    public function forCategory(string $categoryName): static
    {
        $category = Category::firstOrCreate(['name' => $categoryName]);
        
        return $this->state(fn (array $attributes) => [
            'category_id' => $category->id,
        ]);
    }
    
    /**
     * Indicate that the expense has a high amount.
     */
    public function highAmount(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $this->faker->randomFloat(2, 500, 2000),
        ]);
    }
}
