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
        $this->authorize('viewAny', Income::class);
        
        // Récupérer l'utilisateur connecté
        $user = Auth::user();
        
        // Démarrer la requête
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
        
        $categories = Category::where('user_id', Auth::id())->get();
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
        
        $categories = Category::where('user_id', Auth::id())->get();
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