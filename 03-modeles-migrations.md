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

**Avantages des migrations :**
- Permet de versionner la base de données
- Facilite la collaboration sur le projet
- Rend l'ajout/modification de tables plus organisé

**Exemple d'une migration :**
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
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

Cela crée :  
- Un modèle dans `app/Models/`  
- Une migration correspondante dans `database/migrations/`  

---

## 🔗 Définition des relations entre modèles  

### 🔹 Modèle `User.php`  
```php
class User extends Authenticatable
{
    use HasFactory;

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
}
```

### 🔹 Modèle `Expense.php`  
```php
class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'description', 'category_id', 'user_id', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
```

### 🔹 Modèle `Income.php`  
```php
class Income extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'description', 'category_id', 'user_id', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
```

### 🔹 Modèle `Category.php`  
```php
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
}
```

---

## 🚀 Exécution des migrations  

Appliquez les migrations pour créer les tables en base de données :  

```sh
php artisan migrate
```

Si une erreur survient, vérifiez votre fichier `.env` et assurez-vous que la base de données est bien créée.

---

## 📜 Commandes utiles pour les modèles et migrations  

### 📌 Création des modèles et migrations
```sh
php artisan make:model NomDuModele -m   # Créer un modèle avec sa migration
php artisan make:model NomDuModele      # Créer uniquement un modèle
php artisan make:migration create_nom_table  # Créer une migration seule
```

### 📌 Exécuter les migrations
```sh
php artisan migrate        # Exécuter toutes les migrations
php artisan migrate:rollback  # Annuler la dernière migration
php artisan migrate:reset     # Annuler toutes les migrations
php artisan migrate:refresh   # Réappliquer toutes les migrations
```

### 📌 Modifier une table existante
```sh
php artisan make:migration add_column_to_table --table=nom_table  # Ajouter une colonne
```

---

## 📌 Code source de cette étape  

Le code source correspondant à cette étape est disponible sur la branche `step-2`.

---

## 📌 Prochaine étape  

Nous allons maintenant remplir la base de données avec des **fixtures**. **[➡️ Étape suivante : Remplissage de la base de données](04-fixtures.md)**.
