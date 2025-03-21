<?php
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
