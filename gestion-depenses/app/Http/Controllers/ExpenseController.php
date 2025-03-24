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