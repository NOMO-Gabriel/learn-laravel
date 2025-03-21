<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pour chaque utilisateur actif
        User::where('is_active', true)->each(function ($user) {
            // Créer des dépenses récentes
            Expense::factory()
                ->count(5)
                ->recent()
                ->for($user)
                ->create();
            
            // Créer des dépenses à montant élevé
            Expense::factory()
                ->count(2)
                ->highAmount()
                ->for($user)
                ->create();
            
            // Créer des dépenses pour des catégories spécifiques
            $categories = ['Alimentation', 'Transport', 'Logement'];
            foreach ($categories as $category) {
                Expense::factory()
                    ->count(3)
                    ->forCategory($category)
                    ->for($user)
                    ->create();
            }

           
            // Créer d'autres dépenses variées
            Expense::factory()
                ->count(15)
                ->for($user)
                ->create();
        });
    }
}

// namespace Database\Seeders;

// use App\Models\Expense;
// use App\Models\User;
// use App\Models\Category;
// use Illuminate\Database\Seeder;
// use Carbon\Carbon;

// class ExpenseSeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      */
//     public function run(): void
//     {
//         // Obtenir les IDs des utilisateurs et des catégories
//         $userIds = User::pluck('id')->toArray();
//         $categoryIds = Category::whereIn('name', [
//             'Alimentation', 'Transport', 'Logement', 'Factures', 'Loisirs', 'Santé', 'Éducation'
//         ])->pluck('id')->toArray();
        
//         // Dépenses pour chaque utilisateur
//         foreach ($userIds as $userId) {
//             // Quelques dépenses récentes
//             for ($i = 0; $i < 10; $i++) {
//                 Expense::create([
//                     'user_id' => $userId,
//                     'category_id' => $categoryIds[array_rand($categoryIds)],
//                     'amount' => rand(10, 1000) / 10, // Montant entre 1 et 100 avec décimales
//                     'description' => $this->getRandomDescription(),
//                     'date' => Carbon::now()->subDays(rand(0, 30)), // Date dans les 30 derniers jours
//                 ]);
//             }
            
//             // Quelques dépenses plus anciennes
//             for ($i = 0; $i < 20; $i++) {
//                 Expense::create([
//                     'user_id' => $userId,
//                     'category_id' => $categoryIds[array_rand($categoryIds)],
//                     'amount' => rand(10, 1000) / 10,
//                     'description' => $this->getRandomDescription(),
//                     'date' => Carbon::now()->subDays(rand(31, 365)), // Date entre 31 et 365 jours
//                 ]);
//             }
//         }
//     }
    
//     /**
//      * Get a random expense description.
//      */
//     private function getRandomDescription(): string
//     {
//         $descriptions = [
//             'Courses au supermarché',
//             'Restaurant',
//             'Essence',
//             'Transport en commun',
//             'Loyer',
//             'Électricité',
//             'Internet et téléphone',
//             'Assurance',
//             'Médicaments',
//             'Consultation médicale',
//             'Cinéma',
//             'Livres',
//             'Vêtements',
//             'Chaussures',
//             'Cadeau anniversaire',
//             'Matériel informatique',
//             'Abonnement streaming',
//             'Cours en ligne',
//             'Coiffeur',
//             'Entretien voiture',
//         ];
        
//         return $descriptions[array_rand($descriptions)];
//     }
// }