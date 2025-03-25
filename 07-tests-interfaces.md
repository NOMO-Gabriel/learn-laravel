# 🔍 Tests et Débogage de l'Application

[⬅️ Étape précédente : Création des interfaces avec Blade](06-interfaces.md)  
[➡️ Étape suivante : Création des contrôleurs d'API](08-controllers-api.md)  

---

## 📌 Table des matières
- [Introduction aux tests et débogage](#introduction-aux-tests-et-débogage)
- [Correction de la méthode hasRole](#correction-de-la-méthode-hasrole)
- [Relation catégorie-utilisateur](#relation-catégorie-utilisateur)
- [Sécurisation avec les Policies](#sécurisation-avec-les-policies)
- [Correction des permissions administrateur](#correction-des-permissions-administrateur)
- [Configuration correcte de Spatie Laravel Permission](#configuration-correcte-de-spatie-laravel-permission)
- [Affichage des erreurs en développement](#affichage-des-erreurs-en-développement)
- [Quelques améliorations du design](#-quelques-améliorations-du-design)
- [Tests manuels des interfaces](#tests-manuels-des-interfaces)
- [Débogage des problèmes courants](#débogage-des-problèmes-courants)
- [📜 Commandes utiles pour le débogage](#-commandes-utiles-pour-le-débogage)

---

## 📝 Introduction aux tests et débogage

Avant de passer au développement de l'API, il est crucial de s'assurer que notre application web fonctionne correctement. Dans cette section, nous allons identifier et corriger plusieurs problèmes de sécurité et d'autorisation.

Nous nous concentrerons sur plusieurs problèmes importants par ordre logique :
1. 🔐 **Mauvaise utilisation de la méthode hasRole** dans les contrôleurs
2. 🏷️ **Relation catégorie-utilisateur** (chaque utilisateur doit gérer ses propres catégories)
3. 🛡️ **Problèmes de permissions et d'autorisation** avec l'implémentation des policies
4. 👑 **Gestion des utilisateurs par les administrateurs** (rôles et statut actif/inactif)

Les tests et le débogage sont des étapes essentielles dans le développement d'applications web, car ils permettent d'assurer la stabilité, la sécurité et la fiabilité du système.

---

## 🔐 Correction de la méthode hasRole

### 🔹 Identification du problème

Dans notre application, nous avons utilisé `Auth::hasRole('admin')` pour vérifier si l'utilisateur a le rôle d'administrateur. Cependant, cette syntaxe est incorrecte car la façade `Auth` ne dispose pas directement de la méthode `hasRole`.

#### ❌ Utilisation incorrecte
```php
// Mauvaise utilisation - Auth::hasRole n'existe pas
if (!Auth::hasRole('admin')) {
    $query->where('user_id', $user->id);
}
```

### 🔹 Correction

La méthode `hasRole` est disponible sur l'instance de l'utilisateur, pas sur la façade Auth. Il faut donc récupérer l'utilisateur avant d'appeler cette méthode.

#### ✅ Utilisation correcte
```php
// Récupérer l'utilisateur connecté d'abord
$user = Auth::user();
// Puis utiliser la méthode hasRole sur l'objet utilisateur
if (!$user->hasRole('admin')) {
    $query->where('user_id', $user->id);
}
```

Cependant,cette correction doit être appliquée à tous les contrôleurs qui utilisent la vérification de rôle, notamment `IncomeController`, `CategoryController` et `DashboardController`.

---

## 🏷️ Relation catégorie-utilisateur

Un aspect important de notre application est que chaque utilisateur doit gérer ses propres catégories. Pour cela, nous devons établir une relation entre les utilisateurs et les catégories.

### 🔹 Concept de la relation utilisateur-catégorie

Dans notre modèle actuel, les catégories sont partagées entre tous les utilisateurs. Nous voulons modifier ce comportement pour que :
- Chaque catégorie appartienne à un utilisateur spécifique
- Les utilisateurs ne voient et ne manipulent que leurs propres catégories


### 🔹 Mise à jour du modèle Category

Le modèle `Category` doit être modifié pour inclure une relation avec l'utilisateur :

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
    protected $fillable = ['name', 'user_id'];

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

    /**
     * Get the user that owns the category.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

### 🔹 Mise à jour du modèle User

Dans le modèle `User`,il y'a une petite faute d'othographe, `income` doit avoir un `I` majuscule,cette classe  doit être modifiée pour inclure ce changement :

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Permission\Traits\HasRoles; 


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

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

### 🔹 Création d'une migration pour ajouter user_id aux catégories

Nous devons créer une migration pour ajouter le champ `user_id` à la table `categories` :

```sh
php artisan make:migration add_user_id_to_categories_table --table=categories
```

Modifiez le fichier de migration généré :

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('name')->constrained()->onDelete('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
```

### 🔹 Mise à jour du CategorySeeder

Modifions le `CategorySeeder` pour créer des catégories pour chaque utilisateur :

```php
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
```

### 🔹 Mise à jour du CategoryController

Maintenant, modifions le `CategoryController` pour tenir compte de cette relation :

```php
<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des catégories
     */
    public function index()
    {
        $user = Auth::user();
        
        // L'utilisateur ne voit que ses propres catégories
        $categories = Category::where('user_id', $user->id)
                             ->withCount(['expenses', 'incomes'])
                             ->get();
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Enregistre une nouvelle catégorie
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        // Ajouter l'ID de l'utilisateur connecté
        $validated['user_id'] = Auth::id();
        
        // Création de la catégorie
        Category::create($validated);
        
        return redirect()->route('categories.index')
                         ->with('success', 'Catégorie ajoutée avec succès !');
    }

    /**
     * Affiche une catégorie spécifique
     */
    public function show(Category $category)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur peut voir cette catégorie
        if ($category->user_id !== $user->id) {
            return redirect()->route('categories.index')
                            ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette catégorie.');
        }
        
        $expenses = $category->expenses()->with('user')->latest()->take(5)->get();
        $incomes = $category->incomes()->with('user')->latest()->take(5)->get();
        
        return view('categories.show', compact('category', 'expenses', 'incomes'));
    }

    /**
     * Affiche le formulaire de modification
     */
    public function edit(Category $category)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur peut modifier cette catégorie
        if ($category->user_id !== $user->id) {
            return redirect()->route('categories.index')
                            ->with('error', 'Vous n\'êtes pas autorisé à modifier cette catégorie.');
        }
        
        return view('categories.edit', compact('category'));
    }

    /**
     * Met à jour une catégorie
     */
    public function update(Request $request, Category $category)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur peut modifier cette catégorie
        if ($category->user_id !== $user->id) {
            return redirect()->route('categories.index')
                            ->with('error', 'Vous n\'êtes pas autorisé à modifier cette catégorie.');
        }
        
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        // Mise à jour de la catégorie
        $category->update($validated);
        
        return redirect()->route('categories.index')
                         ->with('success', 'Catégorie mise à jour avec succès !');
    }

    /**
     * Supprime une catégorie
     */
    public function destroy(Category $category)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur peut supprimer cette catégorie
        if ($category->user_id !== $user->id) {
            return redirect()->route('categories.index')
                            ->with('error', 'Vous n\'êtes pas autorisé à supprimer cette catégorie.');
        }
        
        // Vérifier si la catégorie est utilisée
        if ($category->expenses()->count() > 0 || $category->incomes()->count() > 0) {
            return redirect()->route('categories.index')
                            ->with('error', 'Impossible de supprimer cette catégorie car elle est utilisée !');
        }
        
        $category->delete();
        
        return redirect()->route('categories.index')
                         ->with('success', 'Catégorie supprimée avec succès !');
    }
}
```

### 🔹 Exécution des migrations et seeders

Après avoir implémenté ces modifications, exécutez les commandes suivantes :

```sh
php artisan migrate
php artisan db:seed --class=CategorySeeder
```

Ou réinitialisez complètement la base de données :

```sh
php artisan migrate:fresh --seed
```

---

## 🛡️ Sécurisation avec les Policies

Laravel offre un système de policies puissant pour gérer les autorisations au niveau des modèles. Nous allons l'utiliser pour sécuriser notre application.

### 🔹 Introduction aux Policies dans Laravel

Les **policies** sont des classes qui encapsulent la logique d'autorisation pour un modèle spécifique. Elles définissent qui peut effectuer quelles actions sur quelles ressources.

Principales caractéristiques des policies :
- Elles permettent de séparer clairement la logique d'autorisation du reste du code
- Elles sont organisées par modèle, ce qui rend le code plus maintenable
- Elles permettent d'utiliser la fonction `authorize()` dans les contrôleurs
- Elles fonctionnent bien avec l'injection automatique des modèles dans les routes

### 🔹 Création de la Policy pour les dépenses

Commençons par créer une policy pour le modèle `Expense` :

```sh
php artisan make:policy ExpensePolicy --model=Expense
```

Modifiez le fichier `app/Policies/ExpensePolicy.php` :

```php


```

### 🔹 Création de la Policy pour les revenus

Créons une policy pour le modèle `Income` :

```sh
php artisan make:policy IncomePolicy --model=Income
```

Modifiez le fichier `app/Policies/IncomePolicy.php` :

```php
<?php

namespace App\Policies;

use App\Models\Income;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncomePolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir la liste des revenus.
     */
    public function viewAny(User $user): bool
    {
        return true; // L'affichage de la liste sera filtré dans le contrôleur.
    }

    /**
     * Détermine si l'utilisateur peut voir un revenu spécifique.
     */
    public function view(User $user, Income $income): bool
    {
        return $user->id === $income->user_id;
    }

    /**
     * Détermine si l'utilisateur peut créer un revenu.
     */
    public function create(User $user): bool
    {
        return true; // Tous les utilisateurs peuvent ajouter des revenus.
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour un revenu.
     */
    public function update(User $user, Income $income): bool
    {
        return $user->id === $income->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer un revenu.
     */
    public function delete(User $user, Income $income): bool
    {
        return $user->id === $income->user_id;
    }
}
```

### 🔹 Création de la Policy pour les catégories

Créons une policy pour le modèle `Category` :

```sh
php artisan make:policy CategoryPolicy --model=Category
```

Modifiez le fichier `app/Policies/CategoryPolicy.php` :

```php
<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir la liste des catégories.
     */
    public function viewAny(User $user): bool
    {
        return true; // L'affichage de la liste sera filtré dans le contrôleur.
    }

    /**
     * Détermine si l'utilisateur peut voir une catégorie spécifique.
     */
    public function view(User $user, Category $category): bool
    {
        return $user->id === $category->user_id;
    }

    /**
     * Détermine si l'utilisateur peut créer une catégorie.
     */
    public function create(User $user): bool
    {
        return true; // Tous les utilisateurs peuvent ajouter des catégories.
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour une catégorie.
     */
    public function update(User $user, Category $category): bool
    {
        return $user->id === $category->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer une catégorie.
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->id === $category->user_id;
    }
}
```

### 🔹 Création de la Policy pour les utilisateurs

Créons une policy pour le modèle `User` :

```sh
php artisan make:policy UserPolicy --model=User
```

Modifiez le fichier `app/Policies/UserPolicy.php` :

```php
<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir la liste des utilisateurs.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Détermine si l'utilisateur peut voir un profil d'utilisateur.
     */
    public function view(User $user, User $targetUser): bool
    {
        // Un utilisateur peut voir son propre profil ou un admin peut voir n'importe quel profil
        return $user->id === $targetUser->id || $user->hasRole('admin');
    }

    /**
     * Détermine si l'utilisateur peut créer d'autres utilisateurs.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour un profil.
     */
    public function update(User $user, User $targetUser): bool
    {
        // Un utilisateur peut modifier son propre profil ou un admin peut modifier n'importe quel profil
        return $user->id === $targetUser->id || $user->hasRole('admin');
    }

    /**
     * Détermine si l'utilisateur peut supprimer un compte.
     */
    public function delete(User $user, User $targetUser): bool
    {
        // Un admin peut supprimer n'importe quel compte sauf le sien
        return $user->hasRole('admin') && $user->id !== $targetUser->id;
    }

    /**
     * Détermine si l'utilisateur peut bloquer/débloquer un compte.
     */
    public function toggleActive(User $user, User $targetUser): bool
    {
        // Un admin peut bloquer/débloquer n'importe quel compte sauf le sien
        return $user->hasRole('admin') && $user->id !== $targetUser->id;
    }
}
```

### 🔹 Enregistrement des policies dans AuthServiceProvider

Pour que Laravel reconnaisse ces policies, nous devons les enregistrer dans le `AuthServiceProvider` :

```php
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Expense::class => \App\Policies\ExpensePolicy::class,
        \App\Models\Income::class => \App\Policies\IncomePolicy::class,
        \App\Models\Category::class => \App\Policies\CategoryPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Définir la règle que les admins peuvent tout faire (super-admin)
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
            return null; // null = continuer la vérification normale
        });
    }
}
```

### 🔹 Mise à jour des contrôleurs avec les policies

#### ExpenseController

```php
<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ExpenseController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des dépenses
     * GET /expenses
     */
    public function index(Request $request)
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();
        
        // Démarrer la requête
        $query = Expense::with(['category', 'user'])->where('user_id', $user->id);
        
        
        
        // Filtre par catégorie
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filtre par date
        if ($request->has('date_start') && $request->date_start) {
            $query->where('date', '>=', $request->date_start);
        }
        
        if ($request->has('date_end') && $request->date_end) {
            $query->where('date', '<=', $request->date_end);
        }
        
        // Pagination des résultats
        $expenses = $query->latest()->paginate(10);
        
        // Récupérer les catégories pour le filtre
        $categories = Category::all();
        
        return view('expenses.index', compact('expenses', 'categories'));
    }

    /**
     * Affiche le formulaire de création
     * GET /expenses/create
     */
    public function create()
    {
        $this->authorize('create', Expense::class);
        
        $categories = Category::where('user_id', Auth::id())->get();
        return view('expenses.create', compact('categories'));
    }

    /**
     * Enregistre une nouvelle dépense
     * POST /expenses
     */
    public function store(Request $request)
    {
        $this->authorize('create', Expense::class);
        
        // Validation des données
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);
        
        // Ajouter l'ID de l'utilisateur connecté
        $validated['user_id'] = Auth::id();
        
        // Création de la dépense
        Expense::create($validated);
        
        return redirect()->route('expenses.index')
                         ->with('success', 'Dépense ajoutée avec succès !');
    }

    /**
     * Affiche une dépense spécifique
     * GET /expenses/{expense}
     */
    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);
        
        $expense->load(['category', 'user']);
        return view('expenses.show', compact('expense'));
    }

    /**
     * Affiche le formulaire de modification
     * GET /expenses/{expense}/edit
     */
    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);
        
        $categories = Category::where('user_id', Auth::id())->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Met à jour une dépense
     * PUT /expenses/{expense}
     */
    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);
        
        // Validation des données
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);
        
        // Mise à jour de la dépense
        $expense->update($validated);
        
        return redirect()->route('expenses.index')
                         ->with('success', 'Dépense mise à jour avec succès !');
    }

    /**
     * Supprime une dépense
     * DELETE /expenses/{expense}
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        
        $expense->delete();
        
        return redirect()->route('expenses.index')
                         ->with('success', 'Dépense supprimée avec succès !');
    }
}
```

#### IncomeController

```php
<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IncomeController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Affiche la liste des revenus
     * GET /incomes
     */
    public function index(Request $request)
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();
        
        // Récupérer les revenus avec filtrage optionnel
        $query = Income::with(['category', 'user'])->where('user_id', $user->id);
        
        
        // Filtre par catégorie
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filtre par date
        if ($request->has('date_start') && $request->date_start) {
            $query->where('date', '>=', $request->date_start);
        }
        
        if ($request->has('date_end') && $request->date_end) {
            $query->where('date', '<=', $request->date_end);
        }
        
        // Pagination des résultats
        $incomes = $query->latest()->paginate(10);
        
        // Récupérer les catégories pour le filtre
        $categories = Category::all();
        
        return view('incomes.index', compact('incomes', 'categories'));
    }

    /**
     * Affiche le formulaire de création
     * GET /incomes/create
     */
    public function create()
    {
        $this->authorize('create', Income::class);
        
        $categories = Category::all();
        return view('incomes.create', compact('categories'));
    }

    /**
     * Enregistre un nouveau revenu
     * POST /incomes
     */
    public function store(Request $request)
    {
        $this->authorize('create', Income::class);
        
        // Validation des données
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);
        
        // Ajouter l'ID de l'utilisateur connecté
        $validated['user_id'] = Auth::id();
        
        // Création du revenu
        Income::create($validated);
        
        return redirect()->route('incomes.index')
                         ->with('success', 'Revenu ajouté avec succès !');
    }

    /**
     * Affiche un revenu spécifique
     * GET /incomes/{income}
     */
    public function show(Income $income)
    {
        $this->authorize('view', $income);
        
        $income->load(['category', 'user']);
        return view('incomes.show', compact('income'));
    }

    /**
     * Affiche le formulaire de modification
     * GET /incomes/{income}/edit
     */
    public function edit(Income $income)
    {
        $this->authorize('update', $income);
        
        $categories = Category::all();
        return view('incomes.edit', compact('income', 'categories'));
    }

    /**
     * Met à jour un revenu
     * PUT /incomes/{income}
     */
    public function update(Request $request, Income $income)
    {
        $this->authorize('update', $income);
        
        // Validation des données
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);
        
        // Mise à jour du revenu
        $income->update($validated);
        
        return redirect()->route('incomes.index')
                         ->with('success', 'Revenu mis à jour avec succès !');
    }

    /**
     * Supprime un revenu
     * DELETE /incomes/{income}
     */
    public function destroy(Income $income)
    {
        $this->authorize('delete', $income);
        
        $income->delete();
        
        return redirect()->route('incomes.index')
                         ->with('success', 'Revenu supprimé avec succès !');
    }
}
```

#### CategoryController

```php
<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des catégories
     */
    public function index()
    {
        $user = Auth::user();
        
        // L'utilisateur ne voit que ses propres catégories
        $categories = Category::where('user_id', $user->id)
                            ->withCount(['expenses', 'incomes'])
                            ->get();
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $this->authorize('create', Category::class);
        
        return view('categories.create');
    }

    /**
     * Enregistre une nouvelle catégorie
     */
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);
        
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        // Ajouter l'ID de l'utilisateur connecté
        $validated['user_id'] = Auth::id();
        
        // Création de la catégorie
        Category::create($validated);
        
        return redirect()->route('categories.index')
                        ->with('success', 'Catégorie ajoutée avec succès !');
    }

    /**
     * Affiche une catégorie spécifique
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);
        
        $expenses = $category->expenses()->with('user')->latest()->take(5)->get();
        $incomes = $category->incomes()->with('user')->latest()->take(5)->get();
        
        return view('categories.show', compact('category', 'expenses', 'incomes'));
    }

    /**
     * Affiche le formulaire de modification
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        
        return view('categories.edit', compact('category'));
    }

    /**
     * Met à jour une catégorie
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);
        
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        // Mise à jour de la catégorie
        $category->update($validated);
        
        return redirect()->route('categories.index')
                        ->with('success', 'Catégorie mise à jour avec succès !');
    }

    /**
     * Supprime une catégorie
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        
        // Vérifier si la catégorie est utilisée
        if ($category->expenses()->count() > 0 || $category->incomes()->count() > 0) {
            return redirect()->route('categories.index')
                            ->with('error', 'Impossible de supprimer cette catégorie car elle est utilisée !');
        }
        
        $category->delete();
        
        return redirect()->route('categories.index')
                        ->with('success', 'Catégorie supprimée avec succès !');
    }
}
```

#### DashboardController

```php
<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');

            // L'utilisateur standard ne voit que ses propres statistiques
            $stats = [
                'totalExpenses' => Expense::where('user_id', $user->id)->sum('amount'),
                'totalIncomes' => Income::where('user_id', $user->id)->sum('amount'),
                'balance' => Income::where('user_id', $user->id)->sum('amount') - Expense::where('user_id', $user->id)->sum('amount'),
                'expenseCount' => Expense::where('user_id', $user->id)->count(),
                'incomeCount' => Income::where('user_id', $user->id)->count(),
                'categoryCount' => Category::where('user_id', $user->id)->count(),
                'userCount' => 1, // Juste l'utilisateur lui-même
            ];
            
            // Dernières transactions de l'utilisateur
            $latestExpenses = Expense::with('category')
                                   ->where('user_id', $user->id)
                                   ->latest()
                                   ->take(5)
                                   ->get();
                                    
            $latestIncomes = Income::with('category')
                                 ->where('user_id', $user->id)
                                 ->latest()
                                 ->take(5)
                                 ->get();
            
            // Données pour graphique - Dépenses par catégorie (utilisateur)
            $expensesByCategory = Expense::select('categories.name', DB::raw('SUM(expenses.amount) as total'))
                                        ->join('categories', 'expenses.category_id', '=', 'categories.id')
                                        ->where('expenses.user_id', $user->id)
                                        ->groupBy('categories.name')
                                        ->get();
        
        
        return view('dashboard.index', compact(
            'stats',
            'latestExpenses',
            'latestIncomes',
            'expensesByCategory'
        ));
    }
}
```

---

## 👑 Correction des permissions administrateur

Dans notre application, nous devons traiter correctement les administrateurs qui gèrent les utilisateurs. Actuellement, il y a quelques problèmes à corriger.

### 🔹 Problèmes identifiés

- Les administrateurs doivent connaître le mot de passe d'un utilisateur pour modifier son compte
- Le champ mot de passe est obligatoire dans le formulaire d'édition des utilisateurs
- Les administrateurs devraient se concentrer sur la gestion des rôles et du statut actif/inactif

### 🔹 Mise à jour du UserController

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des utilisateurs
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $this->authorize('create', User::class);
        
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        // Assigner le rôle
        $user->assignRole($validated['role']);

        // Traiter l'image de profil si fournie
        if ($request->hasFile('profile_image')) {
            $imageName = time() . '_' . $user->id . '.' . $request->profile_image->extension();
            $request->profile_image->storeAs('profiles', $imageName, 'public');
            $user->profile_image = $imageName;
            $user->save();
        }

        return redirect()->route('users.index')
                         ->with('success', 'Utilisateur créé avec succès');
    }

    /**
     * Affiche les détails d'un utilisateur
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        return view('users.show', compact('user'));
    }

    /**
     * Affiche le formulaire de modification
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Met à jour un utilisateur (pour les administrateurs)
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        // Si c'est un administrateur, il peut uniquement modifier le rôle
        if (Auth::user()->hasRole('admin') && Auth::id() !== $user->id) {
            $validated = $request->validate([
                'role' => 'required|exists:roles,name',
            ]);

            // Mise à jour du rôle uniquement
            $user->syncRoles([$validated['role']]);
            
            return redirect()->route('users.index')
                            ->with('success', 'Rôle de l\'utilisateur mis à jour avec succès');
        } 
        // Si c'est l'utilisateur lui-même, il peut modifier ses informations personnelles
        else {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
            ]);
            
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            
            $user->save();
            
            return redirect()->route('profile.edit')
                            ->with('success', 'Profil mis à jour avec succès');
        }
    }

    /**
     * Supprime un utilisateur
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        // Empêcher la suppression de son propre compte
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                            ->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }

        // Supprimer l'image de profil
        if ($user->profile_image) {
            Storage::disk('public')->delete('profiles/' . $user->profile_image);
        }

        $user->delete();

        return redirect()->route('users.index')
                        ->with('success', 'Utilisateur supprimé avec succès');
    }

    /**
     * Bloque ou débloque un utilisateur
     */
    public function toggleActive(User $user)
    {
        $this->authorize('toggleActive', $user);
        
        // Empêcher de se bloquer soi-même
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                            ->with('error', 'Vous ne pouvez pas bloquer votre propre compte');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activé' : 'bloqué';
        
        return redirect()->route('users.index')
                        ->with('success', "L'utilisateur a été $status avec succès");
    }
}
```

### 🔹 Mise à jour des vues d'administration des utilisateurs

Pour le fichier `resources/views/users/edit.blade.php`, modifiez-le pour n'afficher que les champs nécessaires aux administrateurs :

```blade
<!-- resources/views/users/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Modifier l\'utilisateur')

@section('header', 'Modifier l\'utilisateur')

@section('content')
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
            <h2 class="text-lg font-semibold">Informations utilisateur</h2>
            <div class="mt-2 flex items-center">
                <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}" class="h-12 w-12 rounded-full mr-4">
                <div>
                    <p class="font-medium">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </div>
        
        <div class="mb-4">
            <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Rôle</label>
            <div class="mt-2 space-y-2">
                @foreach($roles as $role)
                    <div class="flex items-center">
                        <input id="role_{{ $role->id }}" name="role" type="radio" value="{{ $role->name }}" 
                               class="h-4 w-4 text-primary-600 focus:ring-primary-500" 
                               {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                        <label for="role_{{ $role->id }}" class="ml-2 block text-sm text-gray-900">
                            {{ ucfirst($role->name) }}
                        </label>
                    </div>
                @endforeach
            </div>
            @error('role')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mt-6 flex justify-between">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-save mr-1"></i> Mettre à jour
            </button>
            
            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-times mr-1"></i> Annuler
            </a>
        </div>
    </form>
@endsection
```


---

## ⚙️ Configuration correcte de Spatie Laravel Permission

Pour s'assurer que notre système d'autorisations fonctionne correctement, nous devons vérifier la configuration du package Spatie Laravel Permission.

### 🔹 Vérification de la configuration

1. **Assurez-vous que le trait HasRoles est bien utilisé dans le modèle User**

```php
// app/Models/User.php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    
    // ...
}
```

2. **Vérifiez que les middleware sont correctement enregistrés**

Laravel 11 utilise une nouvelle approche pour enregistrer les middleware. Modifiez le fichier `bootstrap/app.php` :

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Ajout de notre middleware personnalisé
        $middleware->alias([
            'active.user' => \App\Http\Middleware\CheckUserIsActive::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configuration des exceptions (vide pour l'instant)
    })
    ->create();
```

3. **Vider le cache après les modifications**

```sh
php artisan config:clear
php artisan cache:clear
php artisan cache:forget spatie.permission.cache
```

---

## 🐞 Affichage des erreurs en développement

Pour faciliter le débogage pendant le développement, il est important de configurer Laravel pour afficher les erreurs.

### 🔹 Configuration du fichier .env

Assurez-vous que votre fichier `.env` est configuré pour le mode développement :

```dotenv
APP_ENV=local
APP_DEBUG=true
```

### 🔹 Nettoyage du cache de configuration

Après avoir modifié le fichier .env, nettoyez les caches :

```sh
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 🔹 Consulter les logs Laravel

Les logs sont une source précieuse d'informations pour le débogage :

```sh
tail -f storage/logs/laravel.log
```
---
# 🎨 Quelques améliorations du design

Après avoir testé notre application, nous avons identifié quelques points à améliorer dans l'interface utilisateur. Voici les modifications apportées pour rendre l'application plus agréable à utiliser.

## 1. Amélioration de l'affichage des utilisateurs sans photo de profil

### Problème initial
Dans la barre de navigation, si l'utilisateur n'a pas téléchargé de photo de profil, un espace vide ou un lien cassé apparaît, ce qui n'est pas esthétique.

### Solution
Nous ajoutons une icône d'utilisateur par défaut qui s'affiche lorsqu'aucune photo de profil n'est disponible.

#### Code à remplacer dans `resources/views/partials/header.blade.php` :

```php
<button @click="open = !open" class="flex items-center text-gray-700 hover:text-primary-500 focus:outline-none">
    <img src="{{ auth()->user()->profile_image_url }}" alt="Photo de profil" class="h-8 w-8 rounded-full object-cover">
    <span class="ml-2">{{ auth()->user()->name }}</span>
    <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
    </svg>
</button>
```

#### Nouveau code :

```php
<!-- resources/views/partials/header.blade.php -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-primary-600">
                <i class="fas fa-wallet mr-2"></i>
                Gestion Dépenses
            </a>
        </div>
        
        <div class="flex items-center">
            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center text-gray-700 hover:text-primary-500 focus:outline-none">
                        @if(auth()->user()->profile_image)
                            <img src="{{ auth()->user()->profile_image_url }}" alt="Photo de profil" class="h-8 w-8 rounded-full object-cover">
                        @else
                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <span class="ml-2">{{ auth()->user()->name }}</span>
                        <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i> Mon profil
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="space-x-2">
                    <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-800">Connexion</a>
                    <a href="{{ route('register') }}" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-4 rounded">Inscription</a>
                </div>
            @endauth
        </div>
    </div>
</header>
```

## 2. Correction du chevauchement dans la liste des utilisateurs

### Problème initial
Dans la vue `users.index.blade.php`, le nom de l'utilisateur et la date d'inscription se chevauchent parfois, rendant l'information difficile à lire, particulièrement pour les noms longs.

### Solution
Nous restructurons l'affichage pour placer la date d'inscription sous le nom et réduisons la taille du texte pour une meilleure lisibilité.

#### Code à remplacer dans `resources/views/users/index.blade.php` (section concernant l'affichage de l'utilisateur) :

```php
<td class="px-6 py-4 whitespace-nowrap">
    <div class="flex items-center">
        <div class="flex-shrink-0 h-10 w-10">
            <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->profile_image_url }}" alt="{{ $user->name }}">
        </div>
        <div class="ml-4">
            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
            <div class="text-sm text-gray-500">Inscrit le {{ $user->created_at->format('d/m/Y') }}</div>
        </div>
    </div>
</td>
```

#### Nouveau code :

```php
<td class="px-6 py-4">
    <div class="flex items-center">
        <div class="flex-shrink-0 h-10 w-10">
            @if($user->profile_image)
                <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->profile_image_url }}" alt="{{ $user->name }}">
            @else
                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                    <i class="fas fa-user"></i>
                </div>
            @endif
        </div>
        <div class="ml-4">
            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
            <div class="text-xs text-gray-500">Inscrit le {{ $user->created_at->format('d/m/Y') }}</div>
        </div>
    </div>
</td>
```

## 3. Amélioration de la mise en page des boutons d'action

### Problème initial
Les boutons d'action dans la liste des utilisateurs sont parfois mal alignés ou trop rapprochés, ce qui peut entraîner des clics accidentels.

### Solution
Nous réorganisons les actions avec un espacement approprié et ajoutons des infobulles pour clarifier la fonction de chaque bouton.

#### Code à remplacer dans `resources/views/users/index.blade.php` (section concernant les actions) :

```php
<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
    <a href="{{ route('users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('users.edit', $user) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">
        <i class="fas fa-edit"></i>
    </a>
    
    <form action="{{ route('users.toggleActive', $user) }}" method="POST" class="inline mr-2">
        @csrf
        @method('PATCH')
        <button type="submit" class="{{ $user->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}" title="{{ $user->is_active ? 'Bloquer' : 'Débloquer' }}" onclick="return confirm('Êtes-vous sûr de vouloir {{ $user->is_active ? 'bloquer' : 'débloquer' }} cet utilisateur ?')">
            <i class="fas {{ $user->is_active ? 'fa-lock' : 'fa-unlock' }}"></i>
        </button>
    </form>
    
    @if($user->id !== auth()->id())
        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    @endif
</td>
```

#### Nouveau code :

```php
<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
    <div class="flex justify-end space-x-2">
        <a href="{{ route('users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900" title="Voir">
            <i class="fas fa-eye"></i>
        </a>
        <a href="{{ route('users.edit', $user) }}" class="text-yellow-600 hover:text-yellow-900" title="Modifier">
            <i class="fas fa-edit"></i>
        </a>
        
        <form action="{{ route('users.toggleActive', $user) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="{{ $user->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}" title="{{ $user->is_active ? 'Bloquer' : 'Débloquer' }}" onclick="return confirm('Êtes-vous sûr de vouloir {{ $user->is_active ? 'bloquer' : 'débloquer' }} cet utilisateur ?')">
                <i class="fas {{ $user->is_active ? 'fa-lock' : 'fa-unlock' }}"></i>
            </button>
        </form>
        
        @if($user->id !== auth()->id())
            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        @endif
    </div>
</td>
```

## 4. Simplification de la vue des détails utilisateur

### Problème initial
La page de détails d'un utilisateur (`users.show.blade.php`) affiche trop d'informations financières personnelles, ce qui n'est pas nécessaire pour la gestion des utilisateurs par un administrateur.

### Solution
Nous simplifions cette vue pour n'afficher que les informations essentielles du profil : nom, email, rôle, statut et dates d'inscription/mise à jour.

Pour cette modification, consultez la section dédiée dans ce document de test, où un exemple complet de la vue simplifiée est fourni.

---

## 5. Amelioration de la page d'acceuil qui devient bein plus jolie:
```html
<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Dépenses - Suivez vos finances personnelles</title>
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Navigation -->
    <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-4">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-primary-600">
                    <i class="fas fa-wallet mr-2"></i>
                    Gestion Dépenses
                </a>
            </div>
            
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-primary-600 hover:text-primary-800 font-medium">
                        <i class="fas fa-tachometer-alt mr-1"></i> Mon Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-800 font-medium">Connexion</a>
                    <a href="{{ route('register') }}" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out">Inscription</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-500 to-primary-700 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Prenez le contrôle de vos finances personnelles</h1>
                    <p class="text-lg md:text-xl mb-8 opacity-90">Suivez vos dépenses, analysez vos habitudes financières et atteignez vos objectifs d'épargne.</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="bg-white text-primary-700 hover:text-primary-800 py-3 px-6 rounded-md font-semibold shadow-lg hover:shadow-xl transition duration-300 text-center">
                                Accéder à mon Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="bg-white text-primary-700 hover:text-primary-800 py-3 px-6 rounded-md font-semibold shadow-lg hover:shadow-xl transition duration-300 text-center">
                                Commencer gratuitement
                            </a>
                            <a href="{{ route('login') }}" class="bg-transparent border-2 border-white hover:bg-white hover:text-primary-700 text-white py-3 px-6 rounded-md font-semibold transition duration-300 text-center">
                                Se connecter
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <img src="{{ asset('images/dashboard-preview.png') }}" alt="Dashboard Preview" class="w-full max-w-md rounded-lg shadow-2xl" onerror="this.src='https://via.placeholder.com/600x400?text=Dashboard+Preview'">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white" id="fonctionnalites">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Fonctionnalités principales</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Notre application de gestion de dépenses vous offre tous les outils nécessaires pour maîtriser votre budget.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <div class="text-primary-500 mb-4">
                        <i class="fas fa-chart-pie text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Tableau de bord intuitif</h3>
                    <p class="text-gray-600">Visualisez vos finances en un coup d'œil avec des graphiques et statistiques personnalisés.</p>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <div class="text-primary-500 mb-4">
                        <i class="fas fa-tags text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Catégorisation intelligente</h3>
                    <p class="text-gray-600">Organisez vos dépenses et revenus par catégories pour mieux comprendre vos habitudes.</p>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <div class="text-primary-500 mb-4">
                        <i class="fas fa-chart-line text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Suivi des tendances</h3>
                    <p class="text-gray-600">Analysez l'évolution de vos finances au fil du temps et identifiez des opportunités d'économies.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-16 bg-gray-50" id="comment-utiliser">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Comment utiliser l'application</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Suivez ces étapes simples pour commencer à gérer efficacement vos finances.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-4 text-2xl font-bold">1</div>
                    <h3 class="text-xl font-semibold mb-2">Inscription</h3>
                    <p class="text-gray-600">Créez gratuitement votre compte en quelques secondes.</p>
                </div>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-4 text-2xl font-bold">2</div>
                    <h3 class="text-xl font-semibold mb-2">Configurer votre profil</h3>
                    <p class="text-gray-600">Personnalisez votre profil et vos catégories de dépenses.</p>
                </div>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-4 text-2xl font-bold">3</div>
                    <h3 class="text-xl font-semibold mb-2">Enregistrer transactions</h3>
                    <p class="text-gray-600">Ajoutez facilement vos dépenses et revenus quotidiens.</p>
                </div>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-4 text-2xl font-bold">4</div>
                    <h3 class="text-xl font-semibold mb-2">Analyser vos finances</h3>
                    <p class="text-gray-600">Consultez les rapports et prenez de meilleures décisions financières.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Vos données sont sécurisées</h2>
                    <p class="text-lg text-gray-600 mb-6">La sécurité et la confidentialité de vos informations financières sont notre priorité absolue.</p>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <i class="fas fa-shield-alt text-primary-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">Chiffrement de bout en bout de toutes vos données</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-lock text-primary-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">Authentification sécurisée et gestion des rôles</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-user-shield text-primary-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">Nous ne partageons jamais vos informations avec des tiers</span>
                        </li>
                    </ul>
                </div>
                <div class="md:w-1/2">
                    <img src="{{ asset('images/security.png') }}" alt="Sécurité des données" class="w-full max-w-md mx-auto rounded-lg shadow-lg" onerror="this.src='https://via.placeholder.com/600x400?text=Sécurité+des+données'">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-primary-500 to-primary-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-6">Prêt à prendre le contrôle de vos finances ?</h2>
            <p class="text-xl mb-8 max-w-3xl mx-auto opacity-90">Rejoignez des milliers d'utilisateurs qui gèrent efficacement leur budget avec notre application.</p>
            <div class="inline-block">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-white text-primary-700 hover:text-primary-800 py-3 px-8 rounded-md font-semibold shadow-lg hover:shadow-xl transition duration-300 text-center">
                        Accéder à mon Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-primary-700 hover:text-primary-800 py-3 px-8 rounded-md font-semibold shadow-lg hover:shadow-xl transition duration-300 text-center">
                        S'inscrire gratuitement
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Gestion Dépenses</h3>
                    <p class="text-gray-400">Votre outil complet pour la gestion de finances personnelles.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liens rapides</h3>
                    <ul class="space-y-2">
                        <li><a href="#fonctionnalites" class="text-gray-400 hover:text-white">Fonctionnalités</a></li>
                        <li><a href="#comment-utiliser" class="text-gray-400 hover:text-white">Comment utiliser</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white">Connexion</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white">Inscription</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Ressources</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Centre d'aide</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Légal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Confidentialité</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Conditions d'utilisation</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Gestion Dépenses. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>
```
Ces améliorations du design rendent l'application plus intuitive et professionnelle, tout en respectant les bonnes pratiques d'expérience utilisateur.
---

## 🧪 Tests manuels des interfaces

Avant de considérer l'application prête, effectuez les tests manuels suivants pour vérifier son bon fonctionnement.

### 🔹 Tests utilisateur standard

1. **Authentification**
   - Créer un compte utilisateur
   - Se connecter avec ce compte
   - Vérifier la redirection vers le dashboard

2. **Dashboard**
   - Vérifier que seules les statistiques de l'utilisateur sont affichées
   - Vérifier que les graphiques affichent les bonnes données

3. **Catégories**
   - Créer une nouvelle catégorie
   - Vérifier que seules les catégories de l'utilisateur sont visibles
   - Modifier une catégorie existante
   - Supprimer une catégorie non utilisée

4. **Dépenses**
   - Créer une nouvelle dépense avec une catégorie
   - Vérifier qu'elle apparaît dans la liste
   - Modifier une dépense existante
   - Supprimer une dépense

5. **Revenus**
   - Créer un nouveau revenu
   - Vérifier qu'il apparaît dans la liste
   - Modifier un revenu existant
   - Supprimer un revenu

6. **Profil**
   - Modifier les informations du profil
   - Changer le mot de passe
   - Ajouter une photo de profil

### 🔹 Tests administrateur

1. **Gestion des utilisateurs**
   - Se connecter avec un compte administrateur (email: admin@example.com, mot de passe: password)
   - Vérifier la liste des utilisateurs
   - Créer un nouvel utilisateur
   - Modifier le rôle d'un utilisateur existant
   - Bloquer puis débloquer un utilisateur
   - Supprimer un utilisateur

2. **Accès aux données**
   - Vérifier que l'administrateur peut voir les données de tous les utilisateurs
   - Vérifier que l'administrateur peut modifier les données de tous les utilisateurs

---

## 🩹 Débogage des problèmes courants

Voici comment résoudre les problèmes fréquemment rencontrés dans le développement Laravel.

### 🔹 Erreur "Class not found"

Si vous rencontrez cette erreur, exécutez :

```sh
composer dump-autoload
```

### 🔹 Erreur 403 (Unauthorized) lors de l'accès aux ressources

Vérifiez :
1. Que l'utilisateur a le bon rôle/permission
2. Que la policy retourne `true` pour l'action concernée
3. Que `Gate::before` est correctement configuré pour les administrateurs

### 🔹 Erreur lors de l'envoi de formulaire

Vérifiez :
1. Que le formulaire contient le jeton CSRF : `@csrf`
2. Que la méthode HTTP est correcte (avec `@method('PUT')` pour les updates)
3. Que les règles de validation sont respectées

### 🔹 Problèmes avec les relations

Vérifiez :
1. Que les noms des méthodes et des clés étrangères sont corrects
2. Que les migrations ont bien été exécutées
3. Que les modèles sont correctement définis

---

## 📜 Commandes utiles pour le débogage

| Commande | Description |
|----------|------------|
| `php artisan route:list` | Affiche la liste des routes enregistrées |
| `php artisan cache:clear` | Vide le cache de l'application |
| `php artisan config:clear` | Vide le cache de configuration |
| `php artisan view:clear` | Vide le cache des vues compilées |
| `php artisan optimize:clear` | Vide tous les caches |
| `php artisan db:seed` | Remplit la base de données avec des données de test |
| `php artisan migrate:fresh --seed` | Recrée la base et remplit avec des données de test |
| `php artisan tinker` | Lance une console interactive pour tester le code |
| `php artisan storage:link` | Crée un lien symbolique du dossier storage vers public |
| `php artisan make:policy PolicyName --model=ModelName` | Crée une nouvelle policy |
| `php artisan cache:forget spatie.permission.cache` | Vide le cache des permissions |

---

Maintenant que notre application web est fonctionnelle et sécurisée, nous pouvons passer à la prochaine étape : le développement de l'API REST.

[➡️ Étape suivante : Création des contrôleurs d'API](08-controllers-api.md)