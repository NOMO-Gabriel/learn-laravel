<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Catégories de dépenses et revenus communes
        $categories = [
            'Alimentation',
            'Transport',
            'Logement',
            'Factures',
            'Loisirs',
            'Santé',
            'Éducation',
            'Habillement',
            'Voyage',
            'Cadeaux',
            'Salaire',
            'Investissement',
            'Remboursement',
            'Vente',
            'Autres revenus',
        ];
        
        // Récupérer tous les utilisateurs
        $users = User::all();
        
        // Pour chaque utilisateur, créer les catégories
        foreach ($users as $user) {
            foreach ($categories as $category) {
                Category::create([
                    'name' => $category,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}