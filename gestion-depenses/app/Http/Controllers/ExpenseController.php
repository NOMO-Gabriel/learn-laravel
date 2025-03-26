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
        $this->authorize('viewAny', Expense::class);
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