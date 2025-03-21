# 🏗️ Création des Modèles et Migrations

[⬅️ Étape précédente : Création et configuration du projet](02-creation-configuration.md)  
[➡️ Étape suivante : Remplissage de la base de données](04-fixtures.md)  

---

## 📌 Plan de cette section
- [Introduction aux modèles et migrations](#introduction-aux-modèles-et-migrations)
- [Définitions : Qu'est-ce qu'un modèle et une migration ?](#définitions--quest-ce-quun-modèle-et-une-migration)
- [Création des modèles et migrations](#création-des-modèles-et-migrations)
- [Création des migrations pour des fonctionnalités supplémentaires](#création-des-migrations-pour-des-fonctionnalités-supplémentaires)
- [Définition des relations entre modèles](#définition-des-relations-entre-modèles)
- [Accesseurs et mutateurs](#accesseurs-et-mutateurs)
- [Attributs calculés](#attributs-calculés)
- [Exécution des migrations](#exécution-des-migrations)
- [Configuration du stockage pour les images](#configuration-du-stockage-pour-les-images)
- [📜 Commandes utiles pour les modèles et migrations](#-commandes-utiles-pour-les-modèles-et-migrations)

---

## 📝 Introduction aux modèles et migrations  

Laravel utilise **Eloquent ORM** pour interagir avec la base de données. Chaque table de la base de données est représentée par un **modèle** et peut être créée/modifiée via une **migration**.  

Dans cette section, nous allons :  
✅ Créer les modèles **User, Expense, Income et Category**  
✅ Générer leurs migrations associées  
✅ Ajouter des champs supplémentaires comme la photo de profil  
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

> 💡 **Note :** Le modèle User et sa migration de base sont déjà inclus dans Laravel par défaut, mais nous allons les modifier pour nos besoins.

---

## 📂 Migrations de base

Ouvrez les fichiers de migration dans `database/migrations/` et complétez-les comme suit :

### 🔹 Migration `create_users_table.php`
```php
// Cette migration est déjà présente par défaut dans Laravel
// Nous n'avons pas besoin de la modifier pour l'instant
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

## 📂 Création des migrations pour des fonctionnalités supplémentaires

Pour ajouter des fonctionnalités comme la photo de profil utilisateur et la gestion des comptes actifs/inactifs, nous allons créer des migrations supplémentaires :

### 🔹 Migration pour ajouter la photo de profil

```sh
php artisan make:migration add_profile_image_to_users_table --table=users
```

Ouvrez le fichier de migration généré et ajoutez le code suivant :

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_image')->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_image');
        });
    }
};
```

### 🔹 Migration pour ajouter le statut actif/inactif

```sh
php artisan make:migration add_is_active_to_users_table --table=users
```

Ouvrez le fichier de migration généré et ajoutez le code suivant :

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('profile_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
```

---

## 🔗 Modèles avec relations et attributs

### 🔹 Modèle `User.php`

Modifiez le modèle User existant pour inclure les nouveaux champs et les relations :

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user's expenses.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the user's incomes.
     */
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    /**
     * Get the user's profile image URL.
     */
    protected function profileImageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->profile_image) {
                    return Storage::url('profiles/' . $this->profile_image);
                }
                
                // Return default avatar if no image is set
                return asset('images/default-avatar.png');
            },
        );
    }
}
```

### 🔹 Modèle `Expense.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Expense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'description',
        'date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    /**
     * Get the user that owns the expense.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of the expense.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the formatted amount.
     */
    protected function formattedAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->amount, 2) . ' €',
        );
    }
}
```

### 🔹 Modèle `Income.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Income extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'description',
        'date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    /**
     * Get the user that owns the income.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of the income.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the formatted amount.
     */
    protected function formattedAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->amount, 2) . ' €',
        );
    }
}
```

### 🔹 Modèle `Category.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name'];

    /**
     * Get the expenses for the category.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the incomes for the category.
     */
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
}
```

---

## 📐 Définition des relations entre modèles

Dans Laravel, les relations sont définies comme des méthodes dans les modèles. Voici les relations définies dans notre application :

### 🔹 Relations de type "One-to-Many" (Un à plusieurs)

- **Un utilisateur peut avoir plusieurs dépenses** :
  ```php
  // Dans User.php
  public function expenses()
  {
      return $this->hasMany(Expense::class);
  }
  
  // Dans Expense.php
  public function user()
  {
      return $this->belongsTo(User::class);
  }
  ```

- **Un utilisateur peut avoir plusieurs revenus** :
  ```php
  // Dans User.php
  public function incomes()
  {
      return $this->hasMany(Income::class);
  }
  
  // Dans Income.php
  public function user()
  {
      return $this->belongsTo(User::class);
  }
  ```

- **Une catégorie peut être associée à plusieurs dépenses** :
  ```php
  // Dans Category.php
  public function expenses()
  {
      return $this->hasMany(Expense::class);
  }
  
  // Dans Expense.php
  public function category()
  {
      return $this->belongsTo(Category::class);
  }
  ```

- **Une catégorie peut être associée à plusieurs revenus** :
  ```php
  // Dans Category.php
  public function incomes()
  {
      return $this->hasMany(Income::class);
  }
  
  // Dans Income.php
  public function category()
  {
      return $this->belongsTo(Category::class);
  }
  ```

### 🔹 Utilisation des relations dans le code

Les relations permettent d'accéder facilement aux données liées :

```php
// Obtenir toutes les dépenses d'un utilisateur
$user = User::find(1);
$expenses = $user->expenses;

// Obtenir la catégorie d'une dépense
$expense = Expense::find(1);
$category = $expense->category;

// Créer une nouvelle dépense pour un utilisateur
$user->expenses()->create([
    'category_id' => 1,
    'amount' => 50.00,
    'description' => 'Courses',
    'date' => now(),
]);
```

---

## 🎯 Accesseurs et mutateurs

Laravel permet de définir des **accesseurs** (pour formater les données lors de leur lecture) et des **mutateurs** (pour transformer les données lors de leur écriture).

### 🔹 Accesseur pour l'URL de l'image de profil

```php
// Dans User.php
protected function profileImageUrl(): Attribute
{
    return Attribute::make(
        get: function () {
            if ($this->profile_image) {
                return Storage::url('profiles/' . $this->profile_image);
            }
            
            // Return default avatar if no image is set
            return asset('images/default-avatar.png');
        },
    );
}
```

### 🔹 Accesseur pour le montant formaté

```php
// Dans Expense.php et Income.php
protected function formattedAmount(): Attribute
{
    return Attribute::make(
        get: fn () => number_format($this->amount, 2) . ' FRCFA',
    );
}
```

### 🔹 Utilisation des accesseurs

```php
// Obtenir l'URL de l'image de profil
$imageUrl = $user->profile_image_url;

// Obtenir le montant formaté d'une dépense
$formattedAmount = $expense->formatted_amount; // Par exemple: "50.00 €"
```

---

## 🧮 Attributs calculés

Vous pouvez également créer des attributs calculés qui ne sont pas stockés dans la base de données.

Exemple d'attributs calculés que nous pourrions ajouter aux modèles :

### 🔹 Calcul du total des dépenses pour un utilisateur

```php
// Dans User.php
public function getTotalExpensesAttribute()
{
    return $this->expenses()->sum('amount');
}
```

### 🔹 Calcul du total des revenus pour un utilisateur

```php
// Dans User.php
public function getTotalIncomesAttribute()
{
    return $this->incomes()->sum('amount');
}
```

### 🔹 Calcul de la balance (revenus - dépenses)

```php
// Dans User.php
public function getBalanceAttribute()
{
    return $this->total_incomes - $this->total_expenses;
}
```

---

## 🚀 Exécution des migrations  

Une fois que vous avez créé toutes les migrations, exécutez-les pour créer les tables dans votre base de données :

```sh
php artisan migrate
```

Si vous avez besoin de recommencer depuis zéro :

```sh
php artisan migrate:fresh
```

> ⚠️ **Attention :** `migrate:fresh` supprime toutes les tables existantes avant de recréer la structure.

---

## 🖼️ Configuration du stockage pour les images

Pour stocker les images de profil des utilisateurs, nous devons configurer le système de stockage de Laravel.

### 1. Créer un lien symbolique entre storage et public

```sh
php artisan storage:link
```

Cette commande crée un lien symbolique du dossier `storage/app/public` vers `public/storage`, rendant les fichiers accessibles publiquement.

### 2. Créer le dossier pour les images de profil

```sh
mkdir -p storage/app/public/profiles
```

### 3. Configuration du stockage dans le fichier .env

Par défaut, Laravel est configuré pour utiliser le disque "local" pour le stockage. Pour notre application, nous utiliserons le disque "public" :

```
FILESYSTEM_DISK=public
```

### 4. Exemple d'upload d'image de profil

Voici comment vous pourriez implémenter l'upload d'image dans un contrôleur :

```php
public function updateProfileImage(Request $request)
{
    $request->validate([
        'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user = auth()->user();

    // Supprimer l'ancienne image si elle existe
    if ($user->profile_image) {
        Storage::disk('public')->delete('profiles/' . $user->profile_image);
    }

    // Générer un nom unique pour l'image
    $imageName = time() . '_' . $user->id . '.' . $request->profile_image->extension();
    
    // Stocker l'image dans le dossier profiles
    $request->profile_image->storeAs('profiles', $imageName, 'public');
    
    // Mettre à jour le champ profile_image dans la base de données
    $user->update(['profile_image' => $imageName]);

    return back()->with('success', 'Image de profil mise à jour avec succès!');
}
```

---

## 📜 Commandes utiles pour les modèles et migrations  

```sh
# Créer un modèle avec sa migration
php artisan make:model NomDuModele -m

# Créer une migration pour modifier une table existante
php artisan make:migration add_column_to_table --table=nom_table

# Exécuter toutes les migrations
php artisan migrate

# Annuler la dernière migration
php artisan migrate:rollback

# Réappliquer toutes les migrations
php artisan migrate:refresh

# Supprimer toutes les tables et réexécuter les migrations
php artisan migrate:fresh

# Vérifier l'état des migrations
php artisan migrate:status

# Créer un modèle avec migration, factory et seeder
php artisan make:model NomDuModele -mfs
```

---

## 📌 Code source de cette étape  

Le code source correspondant à cette étape est disponible sur la branche `step-2`.

---

## 📌 Prochaine étape  

Maintenant que nos modèles et migrations sont créés, nous allons passer au remplissage de la base de données avec des données de test (fixtures). **[➡️ Étape suivante : Remplissage de la base de données](04-fixtures.md)**.