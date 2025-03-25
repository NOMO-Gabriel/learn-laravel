<?php



namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin fixe
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active' => true,
        ]);
        
        // Créer des utilisateurs avec la factory
        User::factory()->count(2)->create();
        
        // Créer des utilisateurs inactifs
        User::factory()->inactive()->count(1)->create();
    }
}




// namespace Database\Seeders;

// use App\Models\User;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Str;

// class UserSeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      */
//     public function run(): void
//     {
//         // Créer un utilisateur admin
//         User::create([
//             'name' => 'Admin',
//             'email' => 'admin@example.com',
//             'email_verified_at' => now(),
//             'password' => Hash::make('password'),
//             'remember_token' => Str::random(10),
//             'is_active' => true,
//         ]);
        
//         // Créer quelques utilisateurs réguliers
//         User::create([
//             'name' => 'John Doe',
//             'email' => 'john@example.com',
//             'email_verified_at' => now(),
//             'password' => Hash::make('password'),
//             'remember_token' => Str::random(10),
//             'is_active' => true,
//         ]);
        
//         User::create([
//             'name' => 'Jane Smith',
//             'email' => 'jane@example.com',
//             'email_verified_at' => now(),
//             'password' => Hash::make('password'),
//             'remember_token' => Str::random(10),
//             'is_active' => true,
//         ]);
        
//         User::create([
//             'name' => 'Robert Johnson',
//             'email' => 'robert@example.com',
//             'email_verified_at' => now(),
//             'password' => Hash::make('password'),
//             'remember_token' => Str::random(10),
//             'is_active' => true,
//         ]);
        
//         User::create([
//             'name' => 'Emily Brown',
//             'email' => 'emily@example.com',
//             'email_verified_at' => now(),
//             'password' => Hash::make('password'),
//             'remember_token' => Str::random(10),
//             'is_active' => false, // Un utilisateur désactivé
//         ]);
//     }
// }