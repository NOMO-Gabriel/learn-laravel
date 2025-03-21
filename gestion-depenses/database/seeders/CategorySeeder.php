<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Catégories de dépenses communes
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
        
        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}