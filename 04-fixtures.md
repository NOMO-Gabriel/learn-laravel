# 🧪 Remplissage de la base de données : Seeders et Factories

[⬅️ Étape précédente : Création des modèles et migrations](03-modeles-migrations.md)  
[➡️ Étape suivante : Création des contrôleurs et routage](05-controllers-web.md)  

---

## 📌 Plan de cette section
- [Introduction aux seeders et factories](#introduction-aux-seeders-et-factories)
- [Les seeders dans Laravel](#les-seeders-dans-laravel)
- [Les factories dans Laravel](#les-factories-dans-laravel)
- [Utilisation avancée des factories](#utilisation-avancée-des-factories)
- [Création des seeders pour notre application](#création-des-seeders-pour-notre-application)
- [Création des factories pour notre application](#création-des-factories-pour-notre-application)
- [Exécution des seeders](#exécution-des-seeders)
- [📜 Commandes utiles pour les seeders et factories](#-commandes-utiles-pour-les-seeders-et-factories)

---

## 📝 Introduction aux seeders et factories

Lors du développement d'une application, il est souvent nécessaire de disposer de données pour tester les fonctionnalités. Laravel propose deux mécanismes complémentaires pour remplir votre base de données :

### 🔹 Seeders
Les **seeders** sont des classes qui permettent d'insérer des données dans votre base de données de manière programmative. Ils sont généralement utilisés pour :
- Insérer des données initiales nécessaires au fonctionnement de l'application (rôles, catégories prédéfinies, etc.)
- Créer un jeu de données de démonstration
- Préparer l'environnement pour les tests

### 🔹 Factories
Les **factories** sont des « usines » qui génèrent des instances de modèles avec des attributs aléatoires mais réalistes. Elles sont particulièrement utiles pour :
- Générer de grandes quantités de données de test
- Créer des données pour les tests automatisés
- Simuler des scénarios complexes avec des relations entre modèles

### 🔹 Différences entre seeders et factories

| Seeders | Factories |
|---------|-----------|
| Destinés aux données statiques ou prédéfinies | Destinées à générer des données aléatoires mais réalistes |
| Généralement utilisés pour l'initialisation de l'application | Principalement utilisées pour les tests |
| Créent souvent un petit nombre d'enregistrements spécifiques | Peuvent générer facilement des milliers d'enregistrements |
| Peuvent utiliser des factories | Sont utilisées par les seeders ou les tests |

Dans cette section, nous allons :
✅ Comprendre le fonctionnement des seeders et des factories
✅ Créer des seeders pour insérer des données initiales
✅ Créer des factories pour générer des données de test
✅ Utiliser ces outils pour remplir notre base de données de l'application de gestion de dépenses

---

## 🌱 Les seeders dans Laravel

### 1. Qu'est-ce qu'un seeder ?

Un seeder est simplement une classe PHP qui contient une méthode `run()`. Cette méthode est exécutée lorsque vous lancez la commande `php artisan db:seed`. Les seeders sont stockés dans le répertoire `database/seeders`.

### 2. Structure d'un seeder

Voici la structure de base d'un seeder :

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Logique pour insérer des données
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
```

### 3. Le seeder principal (DatabaseSeeder)

Laravel inclut par défaut un seeder principal appelé `DatabaseSeeder`. Ce seeder agit comme un point d'entrée qui peut appeler d'autres seeders :

```php
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
        // Appeler d'autres seeders
        $this->call([
            UserSeeder::class,
            ProductSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
```

### 4. Méthodes d'insertion de données dans un seeder

Il existe plusieurs façons d'insérer des données à l'aide d'un seeder :

#### a. Utilisation de la méthode `create()` d'Eloquent

```php
User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password'),
]);
```

#### b. Utilisation de la méthode `insert()` pour des insertions multiples

```php
User::insert([
    [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => bcrypt('password'),
    ],
    [
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'password' => bcrypt('password'),
    ],
]);
```

#### c. Utilisation du Query Builder

```php
DB::table('users')->insert([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password'),
]);
```

#### d. Utilisation des factories (voir section suivante)

```php
User::factory()->count(10)->create();
```

### 5. Créer et exécuter un seeder

Pour créer un seeder :

```sh
php artisan make:seeder NomDuSeeder
```

Pour exécuter tous les seeders :

```sh
php artisan db:seed
```

Pour exécuter un seeder spécifique :

```sh
php artisan db:seed --class=NomDuSeeder
```

---

## 🏭 Les factories dans Laravel

### 1. Qu'est-ce qu'une factory ?

Une factory est une classe qui définit comment créer des instances de modèles avec des attributs générés automatiquement. Les factories utilisent la bibliothèque Faker pour générer des données réalistes comme des noms, des adresses email, des numéros de téléphone, etc.

### 2. Structure d'une factory

Voici la structure de base d'une factory :

```php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}
```

### 3. La bibliothèque Faker

Faker est une bibliothèque PHP qui génère des données aléatoires mais réalistes. Voici quelques exemples de ce que Faker peut générer :

```php
// Données personnelles
$faker->name();              // Ex: 'John Doe'
$faker->firstName();         // Ex: 'John'
$faker->lastName();          // Ex: 'Doe'
$faker->email();             // Ex: 'john.doe@example.com'
$faker->phoneNumber();       // Ex: '1-555-123-4567'

// Adresses
$faker->address();           // Ex: '123 Main St, Anytown, CA 12345'
$faker->city();              // Ex: 'New York'
$faker->country();           // Ex: 'United States'
$faker->postcode();          // Ex: '10001'

// Texte
$faker->text();              // Génère un paragraphe
$faker->sentence();          // Génère une phrase
$faker->word();              // Génère un mot

// Nombres et dates
$faker->randomNumber();      // Ex: 43926
$faker->numberBetween(1, 100); // Ex: 42
$faker->dateTimeBetween('-1 year', 'now'); // Date dans l'année passée
$faker->date('Y-m-d');       // Ex: '2022-02-25'

// Internet
$faker->url();               // Ex: 'http://www.example.com/'
$faker->ipv4();              // Ex: '192.168.1.1'
$faker->userAgent();         // Ex: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)...'

// Divers
$faker->colorName();         // Ex: 'blue'
$faker->hexColor();          // Ex: '#0099ff'
$faker->company();           // Ex: 'Acme Inc.'
$faker->jobTitle();          // Ex: 'Senior Developer'
```

### 4. Utilisation de base des factories

#### a. Créer une instance sans l'enregistrer

```php
$user = User::factory()->make();
```

#### b. Créer une instance et l'enregistrer

```php
$user = User::factory()->create();
```

#### c. Créer plusieurs instances

```php
$users = User::factory()->count(5)->create();
```

#### d. Surcharger certains attributs

```php
$admin = User::factory()->create([
    'is_admin' => true,
    'name' => 'Admin User',
]);
```

---

## 🚀 Utilisation avancée des factories

### 1. Définir des états (states)

Les états permettent de définir des variations du modèle :

```php
/**
 * Indicate that the user is an admin.
 */
public function admin(): static
{
    return $this->state(fn (array $attributes) => [
        'is_admin' => true,
        'role' => 'admin',
    ]);
}

/**
 * Indicate that the user is suspended.
 */
public function suspended(): static
{
    return $this->state(fn (array $attributes) => [
        'is_active' => false,
        'suspended_at' => now(),
    ]);
}
```

Utilisation :

```php
// Créer un administrateur
$admin = User::factory()->admin()->create();

// Créer un utilisateur suspendu
$suspended = User::factory()->suspended()->create();

// Combinaison d'états
$suspendedAdmin = User::factory()->admin()->suspended()->create();
```

### 2. Génération de données avec des relations

Les factories peuvent générer des données avec des relations :

```php
// Dans UserFactory
public function configure()
{
    return $this->afterCreating(function (User $user) {
        // Créer des dépenses pour cet utilisateur
        Expense::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);
        
        // Créer des revenus pour cet utilisateur
        Income::factory()->count(2)->create([
            'user_id' => $user->id,
        ]);
    });
}
```

Autre approche avec la relation directe :

```php
// Dans ExpenseFactory
public function definition(): array
{
    return [
        'user_id' => User::factory(),
        'category_id' => Category::factory(),
        'amount' => $this->faker->randomFloat(2, 10, 1000),
        'description' => $this->faker->sentence(),
        'date' => $this->faker->dateTimeBetween('-6 months', 'now'),
    ];
}
```

Utilisation :

```php
// Crée un utilisateur avec ses dépenses et revenus
$user = User::factory()->create();

// Crée une dépense liée à un utilisateur existant
$expense = Expense::factory()
    ->for($user)
    ->for(Category::factory())
    ->create();

// Crée un utilisateur avec 5 dépenses
$user = User::factory()
    ->has(Expense::factory()->count(5))
    ->create();
```

### 3. Séquences et fonctions personnalisées

Vous pouvez utiliser des séquences pour générer des données qui suivent un modèle :

```php
/**
 * Define the model's default state.
 */
public function definition(): array
{
    static $order = 1;
    
    return [
        'order_number' => 'ORD-' . str_pad($order++, 5, '0', STR_PAD_LEFT),
        'amount' => $this->faker->randomFloat(2, 100, 10000),
        'status' => $this->faker->randomElement(['pending', 'processing', 'completed']),
    ];
}
```

Ou utiliser la méthode `sequence` :

```php
$users = User::factory()
    ->count(3)
    ->sequence(
        ['role' => 'admin'],
        ['role' => 'editor'],
        ['role' => 'user'],
    )
    ->create();
```

---

## 📊 Création des seeders pour notre application

Maintenant que nous comprenons les concepts, créons les seeders pour notre application de gestion de dépenses.

### 1. Création du seeder pour les utilisateurs

```sh
php artisan make:seeder UserSeeder
```

Contenu du seeder (`database/seeders/UserSeeder.php`) :

```php
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
        // Créer un utilisateur admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active' => true,
        ]);
        
        // Créer quelques utilisateurs réguliers
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active' => true,
        ]);
        
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active' => true,
        ]);
        
        User::create([
            'name' => 'Robert Johnson',
            'email' => 'robert@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active' => true,
        ]);
        
        User::create([
            'name' => 'Emily Brown',
            'email' => 'emily@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active' => false, // Un utilisateur désactivé
        ]);
    }
}
```

### 2. Création du seeder pour les catégories

```sh
php artisan make:seeder CategorySeeder
```

Contenu du seeder (`database/seeders/CategorySeeder.php`) :

```php
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
```

### 3. Création du seeder pour les dépenses et revenus

```sh
php artisan make:seeder ExpenseSeeder
php artisan make:seeder IncomeSeeder
```

Contenu du seeder pour les dépenses (`database/seeders/ExpenseSeeder.php`) :

```php
<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtenir les IDs des utilisateurs et des catégories
        $userIds = User::pluck('id')->toArray();
        $categoryIds = Category::whereIn('name', [
            'Alimentation', 'Transport', 'Logement', 'Factures', 'Loisirs', 'Santé', 'Éducation'
        ])->pluck('id')->toArray();
        
        // Dépenses pour chaque utilisateur
        foreach ($userIds as $userId) {
            // Quelques dépenses récentes
            for ($i = 0; $i < 10; $i++) {
                Expense::create([
                    'user_id' => $userId,
                    'category_id' => $categoryIds[array_rand($categoryIds)],
                    'amount' => rand(10, 1000) / 10, // Montant entre 1 et 100 avec décimales
                    'description' => $this->getRandomDescription(),
                    'date' => Carbon::now()->subDays(rand(0, 30)), // Date dans les 30 derniers jours
                ]);
            }
            
            // Quelques dépenses plus anciennes
            for ($i = 0; $i < 20; $i++) {
                Expense::create([
                    'user_id' => $userId,
                    'category_id' => $categoryIds[array_rand($categoryIds)],
                    'amount' => rand(10, 1000) / 10,
                    'description' => $this->getRandomDescription(),
                    'date' => Carbon::now()->subDays(rand(31, 365)), // Date entre 31 et 365 jours
                ]);
            }
        }
    }
    
    /**
     * Get a random expense description.
     */
    private function getRandomDescription(): string
    {
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
        
        return $descriptions[array_rand($descriptions)];
    }
}
```

Contenu du seeder pour les revenus (`database/seeders/IncomeSeeder.php`) :

```php
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
```

### 4. Mise à jour du seeder principal (DatabaseSeeder)

Modifions le seeder principal pour qu'il appelle nos seeders dans le bon ordre :

```php
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
```

---

## 🏭 Création des factories pour notre application

Créons maintenant des factories pour générer des données de test plus complexes et en plus grande quantité.

### 1. Factory pour les utilisateurs

Laravel inclut déjà une factory pour les utilisateurs. Nous allons la modifier pour l'adapter à nos besoins :

```php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active' => true,
            'profile_image' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
    
    /**
     * Indicate that the user is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
```

### 2. Factory pour les catégories

```sh
php artisan make:factory CategoryFactory
```

Contenu de la factory (`database/factories/CategoryFactory.php`) :

```php
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
```

### 3. Factory pour les dépenses

```sh
php artisan make:factory ExpenseFactory
```

Contenu de la factory (`database/factories/ExpenseFactory.php`) :

```php
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
```

### 4. Factory pour les revenus

```sh
php artisan make:factory IncomeFactory
```

Contenu de la factory (`database/factories/IncomeFactory.php`) :

```php
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
```

### 5. Utilisation des factories dans les seeders

Nous pouvons maintenant modifier nos seeders pour utiliser les factories au lieu de créer les données manuellement. Voici comment nous pourrions modifier le `UserSeeder` :

```php
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
        User::factory()->count(4)->create();
        
        // Créer des utilisateurs inactifs
        User::factory()->inactive()->count(2)->create();
    }
}
```

Et pour `ExpenseSeeder` :

```php
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
```

Cela montre la puissance des factories pour générer rapidement de nombreuses données de test avec différentes caractéristiques.

---

## 🚀 Exécution des seeders

Une fois que tous les seeders et factories sont prêts, nous pouvons les exécuter.

### 1. Exécuter un seul seeder

```sh
php artisan db:seed --class=UserSeeder
```

### 2. Exécuter tous les seeders

```sh
php artisan db:seed
```

### 3. Réinitialiser la base de données et exécuter les seeders

Cette commande est utile pendant le développement, mais **ATTENTION**, elle effacera toutes les données existantes !

```sh
php artisan migrate:fresh --seed
```

### 4. Vérifier les données insérées

Vous pouvez vérifier les données insérées de plusieurs façons :

1. **Via Tinker** :
   ```sh
   php artisan tinker
   >>> User::count(); // Nombre d'utilisateurs
   >>> Category::pluck('name'); // Liste des catégories
   >>> Expense::count(); // Nombre de dépenses
   >>> Income::count(); // Nombre de revenus
   ```

2. **Via un gestionnaire de base de données** comme phpMyAdmin, MySQL Workbench, etc.

3. **Via l'application** en accédant aux différentes pages une fois que les contrôleurs et vues seront créés.

---

## 📜 Commandes utiles pour les seeders et factories

```sh
# Créer un seeder
php artisan make:seeder NomSeeder

# Créer une factory
php artisan make:factory NomFactory

# Créer une factory et le modèle associé
php artisan make:factory NomFactory --model=Nom

# Exécuter tous les seeders
php artisan db:seed

# Exécuter un seeder spécifique
php artisan db:seed --class=NomSeeder

# Réinitialiser la base de données et exécuter les seeders
php artisan migrate:fresh --seed

# Réinitialiser la base de données et exécuter un seeder spécifique
php artisan migrate:fresh --seed --seeder=NomSeeder
```

---

## 📌 Code source de cette étape

Le code source correspondant à cette étape est disponible sur la branche `step-3`.

---

## 📌 Prochaine étape

Nous allons maintenant créer les **contrôleurs et routes** pour gérer notre application. **[➡️ Étape suivante : Création des contrôleurs et routage](05-controllers-web.md)**.