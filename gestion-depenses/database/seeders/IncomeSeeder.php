<?php

namespace Database\Seeders;

use App\Models\Income;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class IncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtenir les IDs des utilisateurs et des catégories
        $userIds = User::pluck('id')->toArray();
        $categoryIds = Category::whereIn('name', [
            'Salaire', 'Investissement', 'Remboursement', 'Vente', 'Autres revenus'
        ])->pluck('id')->toArray();
        
        // Revenus pour chaque utilisateur
        foreach ($userIds as $userId) {
            // Salaires mensuels sur les 6 derniers mois
            for ($i = 0; $i < 6; $i++) {
                Income::create([
                    'user_id' => $userId,
                    'category_id' => Category::where('name', 'Salaire')->first()->id,
                    'amount' => rand(2000, 4000),
                    'description' => 'Salaire mensuel',
                    'date' => Carbon::now()->subMonths($i)->startOfMonth()->addDays(rand(0, 5)),
                ]);
            }
            
            // Quelques revenus supplémentaires aléatoires
            for ($i = 0; $i < 5; $i++) {
                Income::create([
                    'user_id' => $userId,
                    'category_id' => $categoryIds[array_rand($categoryIds)],
                    'amount' => rand(50, 1000),
                    'description' => $this->getRandomDescription(),
                    'date' => Carbon::now()->subDays(rand(0, 180)), // Date dans les 6 derniers mois
                ]);
            }
        }
    }
    
    /**
     * Get a random income description.
     */
    private function getRandomDescription(): string
    {
        $descriptions = [
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
        
        return $descriptions[array_rand($descriptions)];
    }
}