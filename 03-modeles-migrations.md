# 🏗️ Création des Modèles et Migrations

[⬅️ Étape précédente : Création et configuration du projet](02-creation-configuration.md)  
[➡️ Étape suivante : Remplissage de la base de données](04-fixtures.md)  

---

## 📌 Plan de cette section
- [Introduction aux modèles et migrations](#introduction-aux-modèles-et-migrations)
- [Définitions : Qu'est-ce qu'un modèle et une migration ?](#définitions--quest-ce-quun-modèle-et-une-migration)
- [Création des modèles et migrations](#création-des-modèles-et-migrations)
- [Définition des relations entre modèles](#définition-des-relations-entre-modèles)
- [Exécution des migrations](#exécution-des-migrations)
- [📜 Commandes utiles pour les modèles et migrations](#-commandes-utiles-pour-les-modèles-et-migrations)

---

## 📝 Introduction aux modèles et migrations  

Laravel utilise **Eloquent ORM** pour interagir avec la base de données. Chaque table de la base de données est représentée par un **modèle** et peut être créée/modifiée via une **migration**.  

Dans cette section, nous allons :  
✅ Créer les modèles **User, Expense, Income et Category**  
✅ Générer leurs migrations associées  
✅ Définir les relations entre les modèles  

---

## 📖 Définitions : Qu'est-ce qu'un modèle et une migration ?

### 📌 Qu'est-ce qu'un modèle ?
Un **modèle** en Laravel représente une table dans la base de données. Il permet d'interagir avec les données en effectuant des requêtes SQL de manière fluide grâce à **Eloquent ORM**.

**Exemple d'utilisation d'un modèle :**
```php
// Récupérer tous les utilisateurs
$users = User::all();

// Créer un nouvel utilisateur
User::create(['name' => 'John Doe', 'email' => 'john@example.com']);
```

### 📌 Qu'est-ce qu'une migration ?
Une **migration** est un fichier qui permet de créer, modifier ou supprimer des tables en base de données via des commandes Laravel.

**Exemple d'une migration :**
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->timestamps();
});
```

---

## 🛠️ Création des modèles et migrations  

Exécutez les commandes suivantes pour générer les modèles et leurs migrations associées :  

```sh
php artisan make:model User -m
php artisan make:model Expense -m
php artisan make:model Income -m
php artisan make:model Category -m
```

---

## 📂 Migrations

Ouvrez les fichiers de migration dans `database/migrations/` et complétez-les comme suit :

### 🔹 Migration `create_users_table.php`
```php
// on ne modifie rien
```

### 🔹 Migration `create_expenses_table.php`
```php
Schema::create('expenses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('category_id')->constrained()->onDelete('cascade');
    $table->decimal('amount', 10, 2);
    $table->text('description')->nullable();
    $table->date('date');
    $table->timestamps();
});
```

### 🔹 Migration `create_incomes_table.php`
```php
Schema::create('incomes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('category_id')->constrained()->onDelete('cascade');
    $table->decimal('amount', 10, 2);
    $table->text('description')->nullable();
    $table->date('date');
    $table->timestamps();
});
```

### 🔹 Migration `create_categories_table.php`
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});
```

---

## 🔗 Modèles avec `fillable`

### 🔹 Modèle `User.php`
```php
// on ne modifie rien
```

### 🔹 Modèle `Expense.php`
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'description', 'category_id', 'user_id', 'date'];
}
```

### 🔹 Modèle `Income.php`
```php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Income extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'description', 'category_id', 'user_id', 'date'];
}
```

### 🔹 Modèle `Category.php`
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
}
```

---

## 🚀 Exécution des migrations  

```sh
php artisan migrate
```

---

## 📜 Commandes utiles pour les modèles et migrations  

```sh
php artisan make:model NomDuModele -m   # Créer un modèle avec sa migration
php artisan migrate        # Exécuter toutes les migrations
php artisan migrate:rollback  # Annuler la dernière migration
php artisan migrate:refresh   # Réappliquer toutes les migrations
```

---

## 📌 Code source de cette étape  

Le code source correspondant à cette étape est disponible sur la branche `step-2`.

---

## 📌 Prochaine étape  

Nous allons maintenant remplir la base de données avec des **fixtures**. **[➡️ Étape suivante : Remplissage de la base de données](04-fixtures.md)**.
