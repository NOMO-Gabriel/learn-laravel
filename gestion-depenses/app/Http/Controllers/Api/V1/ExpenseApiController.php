<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\ExpenseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreExpenseRequest;
use App\Http\Requests\Api\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;   

class ExpenseApiController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Expense::query();
        
        // Si pas admin, ne montrer que les dépenses de l'utilisateur connecté
        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        } else if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filtrage par catégorie
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filtrage par date
        if ($request->has('date_start')) {
            $query->where('date', '>=', $request->date_start);
        }
        
        if ($request->has('date_end')) {
            $query->where('date', '<=', $request->date_end);
        }
        
        // Filtrage par montant
        if ($request->has('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        
        if ($request->has('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }
        
        // Recherche par description
        if ($request->has('search')) {
            $query->where('description', 'like', "%{$request->search}%");
        }
        
        // Tri
        $sortField = $request->input('sort', 'date');
        $sortDirection = $request->input('direction', 'desc');
        
        $allowedSortFields = ['id', 'date', 'amount', 'created_at'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }
        
        // Inclure les relations
        if ($request->has('include')) {
            $includes = explode(',', $request->input('include'));
            $allowedIncludes = ['user', 'category'];
            
            $validIncludes = array_intersect($includes, $allowedIncludes);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }
        
        $expenses = $query->paginate($request->input('per_page', 15));
        
        return ExpenseResource::collection($expenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        // Créer un DTO à partir de la requête validée
        $expenseDTO = ExpenseDTO::fromRequest($request, auth()->id());
        
        // Autoriser l'action
        $this->authorize('create', Expense::class);
        
        // Vérifier que la catégorie appartient à l'utilisateur
        $category = Category::findOrFail($expenseDTO->category_id);
        if ($category->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'The category does not belong to you'
            ], Response::HTTP_FORBIDDEN);
        }
        
        // Créer la dépense
        $expense = Expense::create($expenseDTO->toArray());
        
        return new ExpenseResource($expense);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Expense $expense)
    {
        // Autoriser l'action
        $this->authorize('view', $expense);
        
        // Inclure les relations
        if ($request->has('include')) {
            $includes = explode(',', $request->input('include'));
            $allowedIncludes = ['user', 'category'];
            
            $validIncludes = array_intersect($includes, $allowedIncludes);
            if (!empty($validIncludes)) {
                $expense->load($validIncludes);
            }
        }
        
        return new ExpenseResource($expense);
    }
    /**
 * Update the specified resource in storage.
 */
public function update(UpdateExpenseRequest $request, Expense $expense)
{
    // Autoriser l'action
    $this->authorize('update', $expense);
    
    // Créer un DTO à partir de la requête validée
    $expenseDTO = ExpenseDTO::fromRequest($request, $expense->user_id);
    
    // Vérifier que la catégorie appartient à l'utilisateur
    $category = Category::findOrFail($expenseDTO->category_id);
    if ($category->user_id !== $expense->user_id && !auth()->user()->hasRole('admin')) {
        return response()->json([
            'message' => 'The category does not belong to you'
        ], Response::HTTP_FORBIDDEN);
    }
    
    // Mettre à jour la dépense
    $expense->update($expenseDTO->toArray());
    
    return new ExpenseResource($expense);
}

/**
 * Remove the specified resource from storage.
 */
public function destroy(Expense $expense)
{
    // Autoriser l'action
    $this->authorize('delete', $expense);
    
    $expense->delete();
    
    return response()->json(null, Response::HTTP_NO_CONTENT);
}
}