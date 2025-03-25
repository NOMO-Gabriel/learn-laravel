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
