<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,         // D'abord les utilisateurs
            CategorySeeder::class,     // Ensuite les catégories
            ExpenseSeeder::class,      // Puis les dépenses
            IncomeSeeder::class,       // Et enfin les revenus
        ]);
    }
}