# 🎮 Création des Contrôleurs, Routage, Authentification et Gestion des Rôles

[⬅️ Étape précédente : Remplissage de la base de données](04-fixtures.md)  
[➡️ Étape suivante : Création des interfaces avec Blade](06-interfaces.md)  

---

## 📌 Plan de cette section
- [Introduction aux contrôleurs et au routage](#introduction-aux-contrôleurs-et-au-routage)
- [Création des contrôleurs](#création-des-contrôleurs)
- [Définition des routes](#définition-des-routes)
- [Mise en place de l'authentification avec Laravel Breeze](#mise-en-place-de-lauthentification-avec-laravel-breeze)
- [Gestion des rôles et permissions avec Spatie](#gestion-des-rôles-et-permissions-avec-spatie)
- [📜 Commandes utiles pour les contrôleurs et le routage](#-commandes-utiles-pour-les-contrôleurs-et-le-routage)

---

## 📝 Introduction aux contrôleurs et au routage  

### 🔹 **Les contrôleurs**

Les **contrôleurs** sont des classes PHP qui gèrent la logique métier de votre application. Ils reçoivent les requêtes HTTP, interagissent avec les modèles pour récupérer/manipuler les données, et renvoient une réponse au client.

**Fonctions principales des contrôleurs:**
- Traiter les requêtes entrantes
- Valider les données
- Interagir avec la base de données via les modèles
- Préparer les données pour les vues
- Retourner une réponse (vue, JSON, redirection, etc.)

### 🔹 **Le routage**

Le **routage** est le mécanisme qui définit comment les URLs de votre application sont associées aux actions des contrôleurs.

**Fonctions principales du routage:**
- Définir les points d'entrée de l'application (URLs)
- Associer des URLs à des méthodes de contrôleurs
- Gérer les paramètres d'URL
- Appliquer des middleware (filtres)
- Grouper les routes par fonctionnalité ou par préfixe

Dans cette section, nous allons :  
✅ Créer les **contrôleurs** nécessaires à notre application  
✅ Définir les **routes** pour toutes les fonctionnalités  
✅ Mettre en place l'**authentification** avec Laravel Breeze  
✅ Configurer la **gestion des rôles** avec Laravel Permission  

---

## 🛠️ Création des contrôleurs  

### 🔸 Différents types de contrôleurs

Laravel propose différentes options pour créer des contrôleurs:

1. **Contrôleur simple** - Une classe vide où vous définissez vos propres méthodes
   ```sh
   php artisan make:controller NomController
   ```

2. **Contrôleur de ressource** - Inclut les 7 méthodes CRUD standards (index, create, store, etc.)
   ```sh
   php artisan make:controller NomController --resource
   ```

3. **Contrôleur API** - Similaire au contrôleur de ressource mais sans les méthodes d'affichage de formulaires
   ```sh
   php artisan make:controller NomController --api
   ```

4. **Contrôleur invokable** - Un contrôleur avec une seule méthode `__invoke()`
   ```sh
   php artisan make:controller NomController --invokable
   ```

### 🔸 Création des contrôleurs pour notre application

Exécutez les commandes suivantes pour générer les contrôleurs :  

```sh
php artisan make:controller ExpenseController --resource
php artisan make:controller IncomeController --resource
php artisan make:controller CategoryController --resource
php artisan make:controller DashboardController
```

---

## 🔄 Différentes façons de récupérer les données

Il existe plusieurs façons de récupérer des données avec Eloquent. Voici les principales méthodes:

### 1. Récupérer tous les enregistrements

```php
// Méthode 1: Utilisation de all()
$expenses = Expense::all();

// Méthode 2: Utilisation de get()
$expenses = Expense::get();
```

### 2. Récupérer un enregistrement spécifique

```php
// Par ID
$expense = Expense::find(1);

// Avec condition
$expense = Expense::where('id', 1)->first();

// Avec condition (lance une exception si non trouvé)
$expense = Expense::findOrFail(1);
```

### 3. Récupérer des enregistrements avec des filtres

```php
// Filtrage simple
$expenses = Expense::where('user_id', 1)->get();

// Filtrages multiples
$expenses = Expense::where('user_id', 1)
                  ->where('amount', '>', 100)
                  ->get();

// Opérateurs de comparaison
$expenses = Expense::where('amount', '>=', 50)->get();

// Recherche partielle
$expenses = Expense::where('description', 'like', '%courses%')->get();
```

### 4. Tri des résultats

```php
// Ordre croissant
$expenses = Expense::orderBy('date', 'asc')->get();

// Ordre décroissant
$expenses = Expense::orderBy('amount', 'desc')->get();

// Tri multiple
$expenses = Expense::orderBy('date', 'desc')
                  ->orderBy('amount', 'desc')
                  ->get();
```

### 5. Limiter les résultats

```php
// Limiter le nombre d'enregistrements
$expenses = Expense::take(5)->get();

// Pagination
$expenses = Expense::paginate(15);

// Offset et limit
$expenses = Expense::skip(10)->take(5)->get();
```

### 6. Charger les relations

```php
// Eager loading (N+1 query problem solution)
$expenses = Expense::with('category', 'user')->get();

// Lazy loading (à éviter dans les boucles)
foreach ($expenses as $expense) {
    echo $expense->category->name;
}
```

---

## 🛠️ Code source des contrôleurs

### 🔹 `DashboardController.php`
```php
namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'totalExpenses' => Expense::sum('amount'),
            'totalIncomes' => Income::sum('amount'),
            'balance' => Income::sum('amount') - Expense::sum('amount'),
            'expenseCount' => Expense::count(),
            'incomeCount' => Income::count(),
            'categoryCount' => Category::count(),
            'userCount' => User::count(),
        ];
        
        // Dernières transactions
        $latestExpenses = Expense::with('category', 'user')
                                ->latest()
                                ->take(5)
                                ->get();
                                
        $latestIncomes = Income::with('category', 'user')
                              ->latest()
                              ->take(5)
                              ->get();
        
        // Données pour graphique - Dépenses par catégorie
        $expensesByCategory = Expense::select('categories.name', DB::raw('SUM(expenses.amount) as total'))
                                    ->join('categories', 'expenses.category_id', '=', 'categories.id')
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

### 🔹 `ExpenseController.php`
```php
namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Application des middleware
        $this->middleware(['auth', 'active.user']);
        
        // Vérifier que l'utilisateur peut modifier/supprimer uniquement ses propres dépenses
        $this->middleware(function ($request, $next) {
            $expense = $request->route('expense');
            
            if ($expense && !Auth::user()->hasRole('admin') && $expense->user_id !== Auth::id()) {
                return redirect()->route('expenses.index')
                                ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette dépense.');
            }
            
            return $next($request);
        })->only(['edit', 'update', 'destroy']);
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
        // Vérifier que l'utilisateur peut voir cette dépense
        if (!Auth::user()->hasRole('admin') && $expense->user_id !== Auth::id()) {
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

### 🔹 `IncomeController.php`
```php
namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    /**
     * Affiche la liste des revenus
     * GET /incomes
     */
    public function index(Request $request)
    {
        // Récupérer les revenus avec filtrage optionnel
        $query = Income::with(['category', 'user']);
        
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
        $categories = Category::all();
        return view('incomes.create', compact('categories'));
    }

    /**
     * Enregistre un nouveau revenu
     * POST /incomes
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
        $validated['user_id'] = Auth::id() ?? 1; // 1 comme valeur par défaut temporaire
        
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
        $income->load(['category', 'user']);
        return view('incomes.show', compact('income'));
    }

    /**
     * Affiche le formulaire de modification
     * GET /incomes/{income}/edit
     */
    public function edit(Income $income)
    {
        $categories = Category::all();
        return view('incomes.edit', compact('income', 'categories'));
    }

    /**
     * Met à jour un revenu
     * PUT /incomes/{income}
     */
    public function update(Request $request, Income $income)
    {
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
        $income->delete();
        
        return redirect()->route('incomes.index')
                         ->with('success', 'Revenu supprimé avec succès !');
    }
}
```

### 🔹 `CategoryController.php`
```php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Seuls les administrateurs peuvent gérer les catégories
        $this->middleware(['auth', 'active.user', 'role:admin']);
    }

    /**
     * Affiche la liste des catégories
     * GET /categories
     */
    public function index()
    {
        $categories = Category::withCount(['expenses', 'incomes'])->get();
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
            'name' => 'required|string|max:255|unique:categories',
        ]);
        
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
        return view('categories.edit', compact('category'));
    }

    /**
     * Met à jour une catégorie
     * PUT /categories/{category}
     */
    public function update(Request $request, Category $category)
    {
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
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

---

## 🔗 Définition des routes  

Laravel offre plusieurs façons de définir des routes. Voyons les différentes méthodes :

### 📝 1. Routes individuelles

```php
// Route simple
Route::get('/dashboard', [DashboardController::class, 'index']);

// Avec nommage
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Avec paramètres
Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');

// Routes avec verbes HTTP différents
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
```

### 📝 2. Routes de ressource (Resource Routes)

C'est un raccourci qui génère automatiquement toutes les routes CRUD en une seule ligne :

```php
Route::resource('expenses', ExpenseController::class);
```

Cela génère les routes suivantes :

| Méthode HTTP | URL                  | Action      | Nom de la route     |
|--------------|----------------------|-------------|---------------------|
| GET          | /expenses            | index       | expenses.index      |
| GET          | /expenses/create     | create      | expenses.create     |
| POST         | /expenses            | store       | expenses.store      |
| GET          | /expenses/{expense}  | show        | expenses.show       |
| GET          | /expenses/{expense}/edit | edit    | expenses.edit       |
| PUT/PATCH    | /expenses/{expense}  | update      | expenses.update     |
| DELETE       | /expenses/{expense}  | destroy     | expenses.destroy    |

### 📝 3. Routes API

Si vous développez une API, vous pouvez utiliser :

```php
Route::apiResource('expenses', ExpenseController::class);
```

Cela est similaire à `resource` mais n'inclut pas les routes pour afficher des formulaires (`create` et `edit`).

### 📝 4. Préfixage et groupement de routes

Pour organiser vos routes :

```php
// Groupe de routes avec préfixe
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);
    Route::resource('users', AdminUserController::class);
});

// Groupe avec middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('expenses', ExpenseController::class);
});

// Groupe avec préfixe et middleware
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Routes protégées pour les administrateurs
});
```

### 📝 Code source de `web.php` avec application des middleware

Ajoutez ce code dans `routes/web.php` :

```php
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route d'accueil
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Routes protégées par authentification et vérification d'utilisateur actif
Route::middleware(['auth', 'verified', 'active.user'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes pour le profil utilisateur
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::patch('/profile/image', 'updateImage')->name('profile.updateImage');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
    
    // Routes pour les dépenses - accessibles à tous les utilisateurs connectés
    Route::resource('expenses', ExpenseController::class);
    
    // Routes pour les revenus - accessibles à tous les utilisateurs connectés
    Route::resource('incomes', IncomeController::class);
    
    // Routes accessibles uniquement aux administrateurs
    Route::middleware(['role:admin'])->group(function () {
        // Routes pour les catégories
        Route::resource('categories', CategoryController::class);
        
        // Routes pour la gestion des utilisateurs
        Route::resource('users', UserController::class);
        
        // Route pour bloquer/débloquer un utilisateur
        Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])
            ->name('users.toggleActive');
    });
});

// Routes d'authentification (générées par Breeze)
require __DIR__.'/auth.php';
```

---

## 🔐 Mise en place de l'authentification avec Laravel Breeze

Laravel Breeze est un package léger qui fournit un système d'authentification avec des vues Blade et un scaffolding minimal.

### 1. Installation de Laravel Breeze

```sh
composer require laravel/breeze --dev
php artisan breeze:install blade
```

Cette commande installe :
- Les contrôleurs d'authentification
- Les routes d'authentification
- Les vues Blade pour l'authentification
- Un layout principal

### 2. Installation des dépendances front-end

```sh
npm install
npm run dev
```

### 3. Appliquer les migrations nécessaires

```sh
php artisan migrate
```

### 4. Mettre à jour le modèle User avec gestion d'avatar

Ouvrez le fichier `app/Models/User.php` et assurez-vous qu'il contient les bons traits pour l'authentification et ajoutez la gestion de l'avatar :

```php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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
     * Get all expenses for user
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    
    /**
     * Get all incomes for user
     */
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
    
    /**
     * Get the profile image URL
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return Storage::url('profiles/' . $this->profile_image);
        }
        
        // Return default avatar if no image is set
        return asset('images/default-avatar.png');
    }
}
```

---

## 🛡️ Gestion des rôles et permissions avec Spatie

Nous allons utiliser le package [Spatie Laravel Permission](https://github.com/spatie/laravel-permission) pour gérer les rôles et permissions dans notre application.

### 1. Installation du package

```sh
composer require spatie/laravel-permission
```

### 2. Publier les migrations

```sh
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

### 3. Appliquer les migrations

```sh
php artisan migrate
```

### 4. Créer un seeder pour les rôles et permissions

```sh
php artisan make:seeder RolesAndPermissionsSeeder
```

### 5. Remplir le seeder avec les rôles et permissions

Ouvrez le fichier `database/seeders/RolesAndPermissionsSeeder.php` et ajoutez ce code :

```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Réinitialiser les rôles et permissions en cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions
        // Dépenses
        Permission::create(['name' => 'view expenses']);
        Permission::create(['name' => 'create expenses']);
        Permission::create(['name' => 'edit expenses']);
        Permission::create(['name' => 'delete expenses']);
        
        // Revenus
        Permission::create(['name' => 'view incomes']);
        Permission::create(['name' => 'create incomes']);
        Permission::create(['name' => 'edit incomes']);
        Permission::create(['name' => 'delete incomes']);
        
        // Catégories
        Permission::create(['name' => 'view categories']);
        Permission::create(['name' => 'create categories']);
        Permission::create(['name' => 'edit categories']);
        Permission::create(['name' => 'delete categories']);
        
        // Utilisateurs
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        
        // Créer les rôles et assigner les permissions
        // Rôle utilisateur standard
        $role = Role::create(['name' => 'user']);
        $role->givePermissionTo([
            'view expenses', 'create expenses', 'edit expenses', 'delete expenses',
            'view incomes', 'create incomes', 'edit incomes', 'delete incomes',
            'view categories'
        ]);
        
        // Rôle administrateur
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
        
        // Assigner le rôle admin au premier utilisateur (ID=1)
        $admin = User::find(1);
        if ($admin) {
            $admin->assignRole('admin');
        }
        
        // Assigner le rôle utilisateur aux autres utilisateurs
        $users = User::where('id', '>', 1)->get();
        foreach ($users as $user) {
            $user->assignRole('user');
        }
    }
}
```

### 6. Mettre à jour le DatabaseSeeder

Ouvrez le fichier `database/seeders/DatabaseSeeder.php` et ajoutez l'appel au nouveau seeder :

```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ExpenseSeeder::class,
            IncomeSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
```

### 7. Exécuter le seeder

```sh
php artisan db:seed --class=RolesAndPermissionsSeeder
```

Ou réinitialiser et repeupler toute la base de données :

```sh
php artisan migrate:fresh --seed
```

### 8. Créer un middleware pour gérer les comptes bloqués

Créons un middleware pour empêcher l'accès aux utilisateurs bloqués :

```sh
php artisan make:middleware CheckUserIsActive
```

Ouvrez le fichier `app/Http/Middleware/CheckUserIsActive.php` et ajoutez :

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->is_active) {
            Auth::logout();
            
            return redirect()->route('login')
                ->with('error', 'Votre compte a été bloqué. Veuillez contacter l\'administrateur.');
        }
        
        return $next($request);
    }
}
```

### 9. Enregistrer le middleware dans Laravel 11

Dans Laravel 11, nous n'utilisons plus le fichier Kernel.php pour enregistrer les middleware. À la place, modifiez le fichier `bootstrap/app.php` pour y ajouter votre middleware :

```php
// bootstrap/app.php
return Application::configure()
    ->withMiddleware(function (Middleware $middleware) {
        // Ajouter votre middleware ici
        $middleware->alias('active.user', \App\Http\Middleware\CheckUserIsActive::class);
    })
    ->withRouting(function (Routing $routing) {
        $routing->web(__DIR__.'/../routes/web.php');
        $routing->apiResource('api');
    })
    // ...
```

### 10. Mettre à jour les routes pour utiliser ce middleware

Dans Laravel 11, l'utilisation du middleware dans les routes reste similaire :

```php
Route::middleware(['auth', 'verified', 'active.user'])->group(function () {
    // Routes protégées...
});
```

Vous pouvez aussi appliquer le middleware directement dans le contrôleur en utilisant le constructeur :

```php
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'active.user']);
    }
    
    // Méthodes du contrôleur...
}
```

### 11. Ajouter des migrations pour les champs `is_active` et `profile_image` dans la table users

#### Migration pour le champ is_active

```sh
php artisan make:migration add_is_active_to_users_table --table=users
```

Ouvrez le fichier de migration créé et ajoutez :

```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('is_active')->default(true);
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('is_active');
    });
}
```

#### Migration pour le champ profile_image

```sh
php artisan make:migration add_profile_image_to_users_table --table=users
```

Ouvrez le fichier de migration créé et ajoutez :

```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('profile_image')->nullable();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('profile_image');
    });
}
```

Exécutez les migrations :

```sh
php artisan migrate
```

#### Configuration du stockage des images

Créez un lien symbolique pour accéder au dossier `storage` depuis le dossier `public` :

```sh
php artisan storage:link
```

Assurez-vous que le dossier `profiles` existe dans `storage/app/public` :

```sh
mkdir -p storage/app/public/profiles
```

---

## 📌 Création du ProfileController pour la gestion de l'image de profil

Le `ProfileController` nécessite des méthodes supplémentaires pour gérer l'upload d'image de profil :

```php
namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's profile image.
     */
    public function updateImage(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        // Supprimer l'ancienne image si elle existe
        if ($user->profile_image) {
            Storage::disk('public')->delete('profiles/' . $user->profile_image);
        }

        // Télécharger la nouvelle image
        $imageName = time() . '_' . $user->id . '.' . $request->profile_image->extension();
        $request->profile_image->storeAs('profiles', $imageName, 'public');

        // Mettre à jour l'utilisateur
        $user->profile_image = $imageName;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-image-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Supprimer l'image de profil si elle existe
        if ($user->profile_image) {
            Storage::disk('public')->delete('profiles/' . $user->profile_image);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
```

## 📌 Création du UserController pour la gestion des utilisateurs par l'admin

Créons un contrôleur dédié à la gestion des utilisateurs par l'admin :

```sh
php artisan make:controller UserController --resource
```

```php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Seuls les administrateurs peuvent gérer les utilisateurs
        $this->middleware(['auth', 'active.user', 'role:admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        return view('users.show', compact('user'));
    }

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Traiter l'image de profil si fournie
        if ($request->hasFile('profile_image')) {
            // Supprimer l'ancienne image
            if ($user->profile_image) {
                Storage::disk('public')->delete('profiles/' . $user->profile_image);
            }
            
            $imageName = time() . '_' . $user->id . '.' . $request->profile_image->extension();
            $request->profile_image->storeAs('profiles', $imageName, 'public');
            $user->profile_image = $imageName;
        }

        $user->save();

        // Mettre à jour le rôle
        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')
                         ->with('success', 'Utilisateur mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
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

## 📜 Commandes utiles pour les contrôleurs et le routage  

| Commande | Description |
|----------|------------|
| `php artisan make:controller NomController` | Crée un contrôleur simple |
| `php artisan make:controller NomController --resource` | Crée un contrôleur avec les méthodes CRUD |
| `php artisan make:controller NomController --api` | Crée un contrôleur API |
| `php artisan make:controller NomController --invokable` | Crée un contrôleur invokable |
| `php artisan route:list` | Affiche la liste des routes |
| `php artisan route:list --name=users` | Filtre les routes par nom |
| `php artisan route:cache` | Met en cache les routes pour améliorer les performances |
| `php artisan route:clear` | Supprime le cache des routes |
| `php artisan storage:link` | Crée un lien symbolique de storage vers public |

---

## 📌 Code source de cette étape  

Le code source correspondant à cette étape est disponible sur la branche `step-4`.

---

## 📌 Prochaine étape  

Nous allons maintenant créer les **interfaces avec Blade** pour afficher les données. **[➡️ Étape suivante : Création des interfaces avec Blade](06-interfaces.md)**.