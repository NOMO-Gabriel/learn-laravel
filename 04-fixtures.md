
# 🏗️ Remplissage de la base de données avec des Fixtures

[⬅️ Étape précédente : Création des modèles et migrations](03-modeles-migrations.md)  
[➡️ Étape suivante : Création des contrôleurs et routage](05-controllers-web.md)  

---

## 📌 Plan de cette section
- [Introduction aux seeders](#introduction-aux-seeders)
- [Création des seeders](#création-des-seeders)
- [Remplissage de la base de données](#remplissage-de-la-base-de-données)
- [📜 Commandes utiles pour les seeders](#-commandes-utiles-pour-les-seeders)

---

## 📝 Introduction aux seeders  

Les **seeders** permettent de remplir automatiquement la base de données avec des **données factices**. Cela est utile pour :  
✅ Tester l'application sans devoir entrer les données manuellement  
✅ Avoir un environnement de développement avec des données réalistes  
✅ Faciliter le travail en équipe en partageant des jeux de données de test  

Dans cette section, nous allons :  
✅ Créer des seeders pour **Users, Categories, Expenses et Incomes**  
✅ Générer 5 données par table  
✅ Exécuter les seeders pour remplir la base de données  

---

## 🛠️ Création des seeders  

Exécutez ces commandes pour générer les seeders :  

```sh
php artisan make:seeder UserSeeder
php artisan make:seeder CategorySeeder
php artisan make:seeder ExpenseSeeder
php artisan make:seeder IncomeSeeder
```

---

## 📂 Contenu des seeders  

Ouvrez chaque fichier dans `database/seeders/` et ajoutez ces données :  

### 🔹 `UserSeeder.php`  
```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            ['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('password')],
            ['name' => 'John Doe', 'email' => 'john@example.com', 'password' => Hash::make('password')],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'password' => Hash::make('password')],
            ['name' => 'Paul Johnson', 'email' => 'paul@example.com', 'password' => Hash::make('password')],
            ['name' => 'Alice Brown', 'email' => 'alice@example.com', 'password' => Hash::make('password')],
        ]);
    }
}
```

---

### 🔹 `CategorySeeder.php`  
```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::insert([
            ['name' => 'Alimentation'],
            ['name' => 'Transport'],
            ['name' => 'Loisirs'],
            ['name' => 'Santé'],
            ['name' => 'Factures'],
        ]);
    }
}
```

---

### 🔹 `ExpenseSeeder.php`  
```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;

class ExpenseSeeder extends Seeder
{
    public function run()
    {
        Expense::insert([
            ['user_id' => 1, 'category_id' => 1, 'amount' => 50, 'description' => 'Courses alimentaires', 'date' => now()],
            ['user_id' => 2, 'category_id' => 2, 'amount' => 15, 'description' => 'Ticket de bus', 'date' => now()],
            ['user_id' => 3, 'category_id' => 3, 'amount' => 30, 'description' => 'Cinéma', 'date' => now()],
            ['user_id' => 4, 'category_id' => 4, 'amount' => 80, 'description' => 'Consultation médicale', 'date' => now()],
            ['user_id' => 5, 'category_id' => 5, 'amount' => 100, 'description' => 'Facture d\'électricité', 'date' => now()],
        ]);
    }
}
```

---

### 🔹 `IncomeSeeder.php`  
```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Income;

class IncomeSeeder extends Seeder
{
    public function run()
    {
        Income::insert([
            ['user_id' => 1, 'category_id' => 1, 'amount' => 2000, 'description' => 'Salaire', 'date' => now()],
            ['user_id' => 2, 'category_id' => 2, 'amount' => 500, 'description' => 'Prime transport', 'date' => now()],
            ['user_id' => 3, 'category_id' => 3, 'amount' => 150, 'description' => 'Cadeau', 'date' => now()],
            ['user_id' => 4, 'category_id' => 4, 'amount' => 300, 'description' => 'Remboursement', 'date' => now()],
            ['user_id' => 5, 'category_id' => 5, 'amount' => 1000, 'description' => 'Revenu secondaire', 'date' => now()],
        ]);
    }
}
```

---

## 🚀 Remplissage de la base de données  

### 1️⃣ **Exécuter un seul seeder**  
Si vous voulez tester un seul seeder :  
```sh
php artisan db:seed --class=UserSeeder
```

### 2️⃣ **Exécuter tous les seeders**  
Pour exécuter tous les seeders en une seule fois :  
```sh
php artisan db:seed
```

### 3️⃣ **Exécuter les migrations + seeders en une seule commande**  
```sh
php artisan migrate:fresh --seed
```
⚠️ **Attention :** `migrate:fresh` **supprime toutes les tables avant de les recréer**.

---

## 📜 Commandes utiles pour les seeders  

| Commande | Description |
|----------|------------|
| `php artisan make:seeder NomSeeder` | Crée un seeder |
| `php artisan db:seed --class=NomSeeder` | Exécute un seeder spécifique |
| `php artisan db:seed` | Exécute tous les seeders |
| `php artisan migrate:fresh --seed` | Réinitialise la base et exécute les seeders |

---

## 📌 Code source de cette étape  

Le code source correspondant à cette étape est disponible sur la branche `step-3`.

---

## 📌 Prochaine étape  

Nous allons maintenant créer les **contrôleurs et routes** pour gérer notre application. **[➡️ Étape suivante : Création des contrôleurs et routage](05-controllers-web.md)**.
```