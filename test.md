# 🔍 Tests et Débogage de l'Application

[⬅️ Étape précédente : Création des interfaces avec Blade](06-interfaces.md)  
[➡️ Étape suivante : Création des contrôleurs d'API](08-controllers-api.md)  

---

## 📌 Table des matières
- [Introduction aux tests et débogage](#introduction-aux-tests-et-débogage)
- [Correction des problèmes d'autorisation](#correction-des-problèmes-dautorisation)
- [Configuration correcte de Spatie Laravel Permission](#configuration-correcte-de-spatie-laravel-permission)
- [Améliorations de la sécurité des contrôleurs](#améliorations-de-la-sécurité-des-contrôleurs)
- [Correction des permissions administrateur pour la gestion des utilisateurs](#correction-des-permissions-administrateur-pour-la-gestion-des-utilisateurs)
- [Correction de la relation catégorie-utilisateur](#correction-de-la-relation-catégorie-utilisateur)
- [Affichage des erreurs dans le développement](#affichage-des-erreurs-dans-le-développement)
- [Tests manuels des interfaces](#tests-manuels-des-interfaces)
- [Débogage des problèmes courants](#débogage-des-problèmes-courants)
- [📜 Commandes utiles pour le débogage](#-commandes-utiles-pour-le-débogage)

---

## 📝 Introduction aux tests et débogage

Avant de passer au développement de l'API, il est crucial de s'assurer que notre application web fonctionne correctement. Dans cette section, nous allons identifier et corriger plusieurs problèmes de sécurité et d'autorisation.

Nous nous concentrerons sur plusieurs problèmes importants :
1. 🔐 **Mauvaise utilisation de la méthode hasRole** dans ExpenseController
2. ⚙️ **Configuration incorrecte de Spatie Laravel Permission**
3. 🛡️ **Problèmes de permissions** (un utilisateur ne doit voir que ses propres données)
4. 👑 **Problèmes d'autorisation pour les administrateurs** (gestion des utilisateurs)
5. 🏷️ **Relation catégorie-utilisateur** (un utilisateur ne doit gérer que ses propres catégories)

Les tests et le débogage sont des étapes essentielles dans le développement d'applications web, car ils permettent d'assurer la stabilité, la sécurité et la fiabilité du système.

---

## 🔐 Correction des problèmes d'autorisation
## 🔐 Correction des problèmes d'autorisation

### 🔹 Problème dans ExpenseController

Dans notre `ExpenseController`, nous avons utilisé `Auth::hasRole('admin')` pour vérifier si l'utilisateur a le rôle d'administrateur. Cependant, cette syntaxe est incorrecte et peut causer des problèmes d'autorisation.

#### ❌ Mauvaise utilisation
```php
// Mauvaise utilisation
if (!Auth::hasRole('admin')) {
    $query->where('user_id', $user->id);
}
```

#### ✅ Correction


```php
// appeler la methode hasRole sur l'objet user renvoye par auth qui est le user authentifie
$user =Auth::user();
if (!$user->hasRole('admin')) {
    $query->where('user_id', $user->id);
}
```
##### Application 
Pour y remédier,
Modifiez le fichier `app/Http/Controllers/ExpenseController.php` comme suit :

```php
<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Affiche la liste des dépenses
     * GET /expenses
     */
    public function index(Request $request)
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();
        
        // Démarrer la requête
        $query = Expense::with(['category', 'user']);
        
        // Si pas admin, ne montrer que les dépenses de l'utilisateur connecté
        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        }
        
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
        $categories = Category::all();
        return view('expenses.create', compact('categories'));
    }

    /**
     * Enregistre une nouvelle dépense
     * POST /expenses
     */
    public function store(Request $request)
    {
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
         // Récupérer l'utilisateur connecté
         $user = Auth::user();
        
        // Vérifier que l'utilisateur peut voir cette dépense
        if (!$user->hasRole('admin') && $expense->user_id !== Auth::id()) {
            return redirect()->route('expenses.index')
                            ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette dépense.');
        }
        
        $expense->load(['category', 'user']);
        return view('expenses.show', compact('expense'));
    }

    /**
     * Affiche le formulaire de modification
     * GET /expenses/{expense}/edit
     */
    public function edit(Expense $expense)
    {
        $categories = Category::all();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Met à jour une dépense
     * PUT /expenses/{expense}
     */
    public function update(Request $request, Expense $expense)
    {
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
        $expense->delete();
        
        return redirect()->route('expenses.index')
                         ->with('success', 'Dépense supprimée avec succès !');
    }
}
```

---

## ⚙️ Configuration correcte de Spatie Laravel Permission

### 🔹 Problème de configuration

Si vous rencontrez des erreurs liées aux rôles et permissions, il peut y avoir des problèmes avec la configuration de Spatie Laravel Permission.

### 🔹 Vérification et correction

1. **Vérifiez que le trait HasRoles est bien utilisé dans le modèle User**

```php
// app/Models/User.php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    
    // ...
}
```

2. **Assurez-vous que le provider est correctement enregistré**

Ouvrez le fichier `config/app.php` et vérifiez que le service provider de Spatie est bien enregistré :

```php
<?php

return [

    'models' => [

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Spatie\Permission\Contracts\Permission` contract.
         */

        'permission' => Spatie\Permission\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `Spatie\Permission\Contracts\Role` contract.
         */

        'role' => Spatie\Permission\Models\Role::class,

    ],

    'table_names' => [

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'roles' => 'roles',

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * table should be used to retrieve your permissions. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'permissions' => 'permissions',

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * table should be used to retrieve your models permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'model_has_permissions' => 'model_has_permissions',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your models roles. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'model_has_roles' => 'model_has_roles',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [
        /*
         * Change this if you want to name the related pivots other than defaults
         */
        'role_pivot_key' => null, // default 'role_id',
        'permission_pivot_key' => null, // default 'permission_id',

        /*
         * Change this if you want to name the related model primary key other than
         * `model_id`.
         *
         * For example, this would be nice if your primary keys are all UUIDs. In
         * that case, name this `model_uuid`.
         */

        'model_morph_key' => 'model_id',

        /*
         * Change this if you want to use the teams feature and your related model's
         * foreign key is other than `team_id`.
         */

        'team_foreign_key' => 'team_id',
    ],

    /*
     * When set to true, the method for checking permissions will be registered on the gate.
     * Set this to false if you want to implement custom logic for checking permissions.
     */

    'register_permission_check_method' => true,

    /*
     * When set to true, Laravel\Octane\Events\OperationTerminated event listener will be registered
     * this will refresh permissions on every TickTerminated, TaskTerminated and RequestTerminated
     * NOTE: This should not be needed in most cases, but an Octane/Vapor combination benefited from it.
     */
    'register_octane_reset_listener' => false,

    /*
     * Events will fire when a role or permission is assigned/unassigned:
     * \Spatie\Permission\Events\RoleAttached
     * \Spatie\Permission\Events\RoleDetached
     * \Spatie\Permission\Events\PermissionAttached
     * \Spatie\Permission\Events\PermissionDetached
     *
     * To enable, set to true, and then create listeners to watch these events.
     */
    'events_enabled' => false,

    /*
     * Teams Feature.
     * When set to true the package implements teams using the 'team_foreign_key'.
     * If you want the migrations to register the 'team_foreign_key', you must
     * set this to true before doing the migration.
     * If you already did the migration then you must make a new migration to also
     * add 'team_foreign_key' to 'roles', 'model_has_roles', and 'model_has_permissions'
     * (view the latest version of this package's migration file)
     */

    'teams' => false,

    /*
     * The class to use to resolve the permissions team id
     */
    'team_resolver' => \Spatie\Permission\DefaultTeamResolver::class,

    /*
     * Passport Client Credentials Grant
     * When set to true the package will use Passports Client to check permissions
     */

    'use_passport_client_credentials' => false,

    /*
     * When set to true, the required permission names are added to exception messages.
     * This could be considered an information leak in some contexts, so the default
     * setting is false here for optimum safety.
     */

    'display_permission_in_exception' => false,

    /*
     * When set to true, the required role names are added to exception messages.
     * This could be considered an information leak in some contexts, so the default
     * setting is false here for optimum safety.
     */

    'display_role_in_exception' => false,

    /*
     * By default wildcard permission lookups are disabled.
     * See documentation to understand supported syntax.
     */

    'enable_wildcard_permission' => false,

    /*
     * The class to use for interpreting wildcard permissions.
     * If you need to modify delimiters, override the class and specify its name here.
     */
    // 'permission.wildcard_permission' => Spatie\Permission\WildcardPermission::class,

    /* Cache-specific settings */

    'cache' => [

        /*
         * By default all permissions are cached for 24 hours to speed up performance.
         * When permissions or roles are updated the cache is flushed automatically.
         */

        'expiration_time' => \DateInterval::createFromDateString('24 hours'),

        /*
         * The cache key used to store all permissions.
         */

        'key' => 'spatie.permission.cache',

        /*
         * You may optionally indicate a specific cache driver to use for permission and
         * role caching using any of the `store` drivers listed in the cache.php config
         * file. Using 'default' here means to use the `default` set in cache.php.
         */

        'store' => 'default',
    ],
];

```

3. **Publiez et vérifiez la configuration de Spatie**

```sh
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"
```

Vérifiez le fichier `config/permission.php` pour vous assurer que les modèles sont correctement définis :

```php
'models' => [
    'permission' => Spatie\Permission\Models\Permission::class,
    'role' => Spatie\Permission\Models\Role::class,
],
```

4. **Videz le cache des autorisations**

```sh
php artisan cache:forget spatie.permission.cache
php artisan cache:clear
```

5. **Vérifiez que le middleware role/permission est correctement configuré**

Le middleware devrait être correctement enregistré dans `bootstrap/app.php` :

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

---

## 🛡️ Améliorations de la sécurité des contrôleurs

Pour renforcer la sécurité de l'application, nous allons créer des Policy pour les modèles Expense, Income et Category. Les policies sont un moyen propre et organisé de gérer les autorisations dans Laravel.

### 🔹 Création de la Policy pour les dépenses

```sh
php artisan make:policy ExpensePolicy --model=Expense
```

Modifiez le fichier `app/Policies/ExpensePolicy.php` :

```php
<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir la liste des dépenses.
     * Ici, on pourrait restreindre mais cela dépend du besoin.
     */
    public function viewAny(User $user): bool
    {
        return true; // Permet d'afficher l'index, mais les données seront filtrées dans le contrôleur.
    }

    /**
     * Détermine si l'utilisateur peut voir une dépense spécifique.
     */
    public function view(User $user, Expense $expense): bool
    {
        return $user->id === $expense->user_id;
    }

    /**
     * Détermine si l'utilisateur peut créer une dépense.
     */
    public function create(User $user): bool
    {
        return true; // Tous les utilisateurs peuvent créer des dépenses.
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour une dépense.
     */
    public function update(User $user, Expense $expense): bool
    {
        return $user->id === $expense->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer une dépense.
     */
    public function delete(User $user, Expense $expense): bool
    {
        return $user->id === $expense->user_id;
    }
}
```

### 🔹 Création de la Policy pour les revenus

```sh
php artisan make:policy IncomePolicy --model=Income
```

Modifiez le fichier `app/Policies/IncomePolicy.php` comme pour ExpensePolicy.

vous deviez obtenir le resultat suivant:
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

### 🔹 Mise à jour de AuthServiceProvider

Enregistrez automatiquement les policies dans `app/Providers/AuthServiceProvider.php` :

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
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Définir un super-admin qui peut tout faire
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
```
### 🔹 Création de la Policy pour les utilisateurs

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
     * L'admin peut voir les profils des utilisateurs.
     */
    public function view(User $user, User $targetUser): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * L'admin peut bloquer un utilisateur (mais pas un autre admin).
     */
    public function block(User $user, User $targetUser): bool
    {
        return $user->hasRole('admin') && !$targetUser->hasRole('admin');
    }

    /**
     * L'admin peut réactiver un utilisateur.
     */
    public function activate(User $user, User $targetUser): bool
    {
        return $user->hasRole('admin') ;
    }
}

```

### 🔹 Mise à jour de AuthServiceProvider

Enregistrez automatiquement les policies dans `app/Providers/AuthServiceProvider.php` :

```php
<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Associer les models à leurs policies.
     */
    protected $policies = [
        \App\Models\Expense::class => \App\Policies\ExpensePolicy::class,
        \App\Models\Income::class => \App\Policies\IncomePolicy::class,
        \App\Models\Category::class => \App\Policies\CategoryPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();


        // // Définir un super-admin qui peut tout faire
        // Gate::before(function ($user, $ability) {
        //     return $user->hasRole('admin') ? true : null;
        // });
    }
}
```

### 🔹 Mise à jour des contrôleurs pour utiliser les policies

Modifiez nos controlleurs pour utiliser les policies au lieu des vérifications manuelles.

voici ce que ca donne:

#### `ExpenseController.php`
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
#### `IncomeController.php`
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
#### `UserController.php`
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
     * Display a listing of the resource.
     */
    public function index()
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }
        
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }
        
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }
        
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
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }
        
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }
        
        // Règles de validation différentes pour les administrateurs
        // Les admins peuvent uniquement modifier le rôle et le statut, pas les informations personnelles
        $validated = $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // Mise à jour du rôle uniquement
        $user->syncRoles([$validated['role']]);
        
        return redirect()->route('users.index')
                         ->with('success', 'Rôle de l\'utilisateur mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }
        
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
     * Toggle user active status
     */
    public function toggleActive(User $user)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }
        
        // Empêcher de se bloquer soi-même
        if ($user->id === auth()->id()) {
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
#### `CategoryController.php`
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
     * GET /categories
     */
    public function index()
    {
        $user = Auth::user();
        
        
            $categories = Category::where('user_id', $user->id)
                                 ->withCount(['expenses', 'incomes'])
                                 ->get();
        
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Affiche le formulaire de création
     * GET /categories/create
     */
    public function create()
    {
        $this->authorize('create', Category::class);
        
        return view('categories.create');
    }

    /**
     * Enregistre une nouvelle catégorie
     * POST /categories
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
     * GET /categories/{category}
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
     * GET /categories/{category}/edit
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        
        return view('categories.edit', compact('category'));
    }

    /**
     * Met à jour une catégorie
     * PUT /categories/{category}
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
     * DELETE /categories/{category}
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
#### `DashboardController.php`
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

## 👑 Correction des permissions administrateur pour la gestion des utilisateurs

En testant notre application, nous avons identifié un problème important dans la gestion des utilisateurs : actuellement, quand un administrateur veut modifier un utilisateur, il doit connaître le mot de passe de cet utilisateur, ce qui est illogique et peu sécurisé.

### 🚫 Problème identifié

- Un administrateur doit connaître le mot de passe d'un utilisateur pour modifier son compte
- Le champ mot de passe est obligatoire dans le formulaire d'édition des utilisateurs
- Les administrateurs peuvent modifier toutes les informations des utilisateurs, alors qu'ils devraient uniquement pouvoir gérer les rôles et le statut actif/inactif

### ✅ Correction à apporter

Nous allons modifier le contrôleur `UserController` et les règles de validation pour :

1. Rendre le mot de passe optionnel lors de la modification par un administrateur
2. Limiter les champs modifiables par un administrateur (principalement le rôle et le statut)
3. Ajuster les formulaires pour refléter ces modifications

### 🛠️ Mise à jour du UserController

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Méthodes précédentes inchangées...

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Règles de validation différentes pour les administrateurs
        // Les admins peuvent uniquement modifier le rôle et le statut, pas les informations personnelles
        $validated = $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // Mise à jour du rôle uniquement
        $user->syncRoles([$validated['role']]);
        
        return redirect()->route('users.index')
                         ->with('success', 'Rôle de l\'utilisateur mis à jour avec succès');
    }

    // Autres méthodes inchangées...

    /**
     * Toggle user active status
     */
    public function toggleActive(User $user)
    {
        // Empêcher de se bloquer soi-même
        if ($user->id === auth()->id()) {
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

### 📝 Modification de la vue d'édition des utilisateurs

Modifiez le fichier `resources/views/users/edit.blade.php` pour limiter les champs comme expliqué dans la section précédente.

### 📋 Mise à jour de la vue d'index des utilisateurs

Modifiez également la vue qui liste tous les utilisateurs pour clarifier les actions disponibles comme expliqué dans la section précédente.

---

## 🏷️ Correction de la relation catégorie-utilisateur

Nous devons modifier notre système pour que les catégories soient liées à un utilisateur spécifique, permettant à chacun de gérer ses propres catégories.

### 🔹 Mise à jour du modèle Category

Modifiez le modèle `app/Models/Category.php` :

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

### 🔹 Création d'une migration pour ajouter user_id aux catégories

```sh
php artisan make:migration add_user_id_to_categories_table --table=categories
```

Modifiez le fichier de migration :

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
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('name')->constrained()->onDelete('cascade');
        });

        // Assigner les catégories existantes à l'utilisateur admin (ID=1)
        DB::table('categories')->update(['user_id' => 1]);
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

### 🔹 Mise à jour du CategoryController

Modifiez le fichier `app/Http/Controllers/CategoryController.php` :

```php
<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'active.user']);
    }

    /**
     * Affiche la liste des catégories
     * GET /categories
     */
    public function index()
    {
        $user = Auth::user();
        
        // L'admin voit toutes les catégories, les utilisateurs normaux voient uniquement leurs catégories
        if ($user->hasRole('admin')) {
            $categories = Category::withCount(['expenses', 'incomes'])->get();
        } else {
            $categories = Category::where('user_id', $user->id)
                                 ->withCount(['expenses', 'incomes'])
                                 ->get();
        }
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Affiche le formulaire de création
     * GET /categories/create
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Enregistre une nouvelle catégorie
     * POST /categories
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
     * GET /categories/{category}
     */
    public function show(Category $category)
    {
        // Vérifier que l'utilisateur peut voir cette catégorie
        $user = Auth::user();
        if (!$user->hasRole('admin') && $category->user_id !== $user->id) {
            return redirect()->route('categories.index')
                            ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette catégorie.');
        }
        
        $expenses = $category->expenses()->with('user')->latest()->take(5)->get();
        $incomes = $category->incomes()->with('user')->latest()->take(5)->get();
        
        return view('categories.show', compact('category', 'expenses', 'incomes'));
    }

    /**
     * Affiche le formulaire de modification
     * GET /categories/{category}/edit
     */
    public function edit(Category $category)
    {
        // Vérifier que l'utilisateur peut modifier cette catégorie
        $user = Auth::user();
        if (!$user->hasRole('admin') && $category->user_id !== $user->id) {
            return redirect()->route('categories.index')
                            ->with('error', 'Vous n\'êtes pas autorisé à modifier cette catégorie.');
        }
        
        return view('categories.edit', compact('category'));
    }

    /**
     * Met à jour une catégorie
     * PUT /categories/{category}
     */
    public function update(Request $request, Category $category)
    {
        // Vérifier que l'utilisateur peut modifier cette catégorie
        $user = Auth::user();
        if (!$user->hasRole('admin') && $category->user_id !== $user->id) {
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
     * DELETE /categories/{category}
     */
    public function destroy(Category $category)
    {
        // Vérifier que l'utilisateur peut supprimer cette catégorie
        $user = Auth::user();
        if (!$user->hasRole('admin') && $category->user_id !== $user->id) {
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

### 🔹 Mise à jour du CategorySeeder

Modifiez le fichier `database/seeders/CategorySeeder.php` pour associer les catégories aux utilisateurs :

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

### 🔹 Exécuter la migration et mettre à jour les seeders

```sh
php artisan migrate
php artisan db:seed --class=CategorySeeder
```

---

## 🐞 Affichage des erreurs dans le développement

Pendant le développement, il est important de voir les erreurs pour pouvoir les corriger. Assurez-vous que votre environnement est correctement configuré.

### 🔹 Configuration dans le fichier .env

```dotenv
APP_ENV=local
APP_DEBUG=true
```

### 🔹 Nettoyage du cache de configuration

Après avoir modifié le fichier .env, nettoyez le cache :

```sh
php artisan config:clear
php artisan cache:clear
```

### 🔹 Logs Laravel

Consultez les logs Laravel pour voir les erreurs :

```sh
tail -f storage/logs/laravel.log
```

---

## 🧪 Tests manuels des interfaces

Avant de considérer que votre application est prête, effectuez les tests manuels suivants :

### 🔹 Checklist de tests utilisateur standard

1. **Inscription**
   - Créer un nouveau compte utilisateur
   - Vérifier la redirection vers le dashboard

2. **Connexion**
   - Se connecter avec les identifiants créés
   - Vérifier que le tableau de bord s'affiche correctement

3. **Gestion du profil**
   - Modifier le nom et l'email
   - Télécharger une image de profil
   - Changer le mot de passe

4. **Gestion des catégories personnelles**
   - Créer une nouvelle catégorie
   - Vérifier que seules vos catégories sont visibles
   - Modifier une catégorie existante
   - Supprimer une catégorie

5. **Gestion des dépenses**
   - Créer une nouvelle dépense en utilisant une de vos catégories
   - Voir la liste des dépenses
   - Modifier une dépense existante
   - Supprimer une dépense

6. **Gestion des revenus**
   - Créer un nouveau revenu avec une de vos catégories
   - Voir la liste des revenus
   - Modifier un revenu existant
   - Supprimer un revenu

### 🔹 Tests administrateur

1. **Connexion en tant qu'admin**
   - Se connecter avec l'utilisateur admin (admin@example.com / password)

2. **Gestion des utilisateurs**
   - Voir la liste des utilisateurs
   - Créer un nouvel utilisateur
   - Modifier le rôle d'un utilisateur existant
   - Bloquer/débloquer un utilisateur
   - Supprimer un utilisateur

3. **Gestion globale des catégories**
   - Voir la liste de toutes les catégories (de tous les utilisateurs)
   - Créer une nouvelle catégorie (qui sera associée à l'admin)
   - Modifier une catégorie existante
   - Supprimer une catégorie

4. **Vérification des permissions**
   - Voir les dépenses et revenus de tous les utilisateurs
   - Modifier les dépenses et revenus d'autres utilisateurs

---

## 🩹 Débogage des problèmes courants

### 🔹 Erreur "Class not found"

Si vous rencontrez des erreurs "Class not found", vérifiez que l'auto-loader est bien à jour :

```sh
composer dump-autoload
```

### 🔹 Erreur de connexion à la base de données

Vérifiez votre fichier `.env` pour vous assurer que les informations de connexion sont correctes :

```sh
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_depenses
DB_USERNAME=root
DB_PASSWORD=
```

### 🔹 Erreur de CORS avec les API

Si vous rencontrez des erreurs CORS lors des tests d'API, vérifiez la configuration CORS dans `config/cors.php` :

```php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

### 🔹 Erreur "Permission denied" sur les uploads

Vérifiez les permissions des dossiers de stockage :

```sh
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 🔹 Problèmes de validation des formulaires

Si les validations ne fonctionnent pas comme prévu :

1. Vérifiez que vous utilisez la bonne méthode HTTP (`POST`, `PUT`, etc.)
2. Assurez-vous que le jeton CSRF est présent dans le formulaire (`@csrf`)
3. Vérifiez les règles de validation dans le contrôleur
4. Consultez les messages d'erreur dans la session

### 🔹 Problèmes d'authentification

Si vous rencontrez des problèmes avec l'authentification :

1. Vérifiez que la table `users` est correctement migrée
2. Assurez-vous que les informations d'identification sont correctes
3. Vérifiez que l'utilisateur est actif (`is_active` est `true`)
4. Consultez les logs pour les messages d'erreur

### 🔹 Problèmes d'autorisation

Si les vérifications d'autorisation ne fonctionnent pas :

1. Vérifiez que les rôles sont correctement assignés aux utilisateurs
2. Assurez-vous que les policies sont correctement enregistrées
3. Vérifiez que les gates sont correctement définies
4. Consultez les logs pour les messages d'erreur d'autorisation

### 🔹 Problèmes avec les catégories liées aux utilisateurs

Si les catégories ne s'affichent pas correctement ou ne sont pas associées aux bons utilisateurs :

1. Vérifiez que la migration `add_user_id_to_categories_table` a bien été exécutée
2. Assurez-vous que les seeders ont été mis à jour pour associer les catégories aux utilisateurs
3. Vérifiez que les relations sont correctement définies dans les modèles
4. Consultez les logs pour les messages d'erreur

---

## 📜 Commandes utiles pour le débogage

| Commande | Description |
|----------|------------|
| `php artisan route:list` | Affiche toutes les routes enregistrées |
| `php artisan cache:clear` | Vide le cache de l'application |
| `php artisan config:clear` | Vide le cache de configuration |
| `php artisan view:clear` | Vide le cache des vues compilées |
| `php artisan optimize:clear` | Vide tous les caches |
| `php artisan db:seed` | Remplit la base de données avec des données de test |
| `php artisan migrate:fresh --seed` | Recrée la base et remplit avec des données de test |
| `php artisan tinker` | Lance une console interactive pour tester le code |
| `php artisan storage:link` | Crée un lien symbolique de storage vers public |
| `php artisan make:policy PolicyName --model=ModelName` | Crée une nouvelle policy pour un modèle |
| `php artisan cache:forget spatie.permission.cache` | Vide le cache des permissions |
| `php artisan debug:mode=on` | Active le mode debug (si disponible) |
| `php artisan debug:mode=off` | Désactive le mode debug (si disponible) |

---

## 📌 Code source de cette étape

Le code source correspondant à cette étape est disponible sur la branche `step-6`.

---

## 📌 Prochaine étape

Maintenant que notre application web est fonctionnelle et sécurisée, nous allons passer au développement de l'API REST. **[➡️ Étape suivante : Création des contrôleurs d'API](08-controllers-api.md)**.