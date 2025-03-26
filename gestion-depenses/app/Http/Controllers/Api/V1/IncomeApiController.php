<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\IncomeDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreIncomeRequest;
use App\Http\Requestss\Api\V1\UpdateIncomeRequest;
use App\Http\Resources\IncomeResource;
use App\Models\Category;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

class IncomeApiController extends Controller
{   
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Income::query();
        
        // Si pas admin, ne montrer que les revenus de l'utilisateur connecté
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
        
        $incomes = $query->paginate($request->input('per_page', 15));
        
        return IncomeResource::collection($incomes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIncomeRequest $request)
    {
        // Créer un DTO à partir de la requête validée
        $incomeDTO = IncomeDTO::fromRequest($request, auth()->id());
        
        // Autoriser l'action
        $this->authorize('create', Income::class);
        
        // Vérifier que la catégorie appartient à l'utilisateur
        $category = Category::findOrFail($incomeDTO->category_id);
        if ($category->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'The category does not belong to you'
            ], Response::HTTP_FORBIDDEN);
        }
        
        // Créer le revenu
        $income = Income::create($incomeDTO->toArray());
        
        return new IncomeResource($income);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Income $income)
    {
        // Autoriser l'action
        $this->authorize('view', $income);
        
        // Inclure les relations
        if ($request->has('include')) {
            $includes = explode(',', $request->input('include'));
            $allowedIncludes = ['user', 'category'];
            
            $validIncludes = array_intersect($includes, $allowedIncludes);
            if (!empty($validIncludes)) {
                $income->load($validIncludes);
            }
        }
        
        return new IncomeResource($income);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIncomeRequest $request, Income $income)
    {
        // Autoriser l'action
        $this->authorize('update', $income);
        
        // Créer un DTO à partir de la requête validée
        $incomeDTO = IncomeDTO::fromRequest($request, $income->user_id);
        
        // Vérifier que la catégorie appartient à l'utilisateur
        $category = Category::findOrFail($incomeDTO->category_id);
        if ($category->user_id !== $income->user_id && !auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'The category does not belong to you'
            ], Response::HTTP_FORBIDDEN);
        }
        
        // Mettre à jour le revenu
        $income->update($incomeDTO->toArray());
        
        return new IncomeResource($income);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        // Autoriser l'action
        $this->authorize('delete', $income);
        
        $income->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}