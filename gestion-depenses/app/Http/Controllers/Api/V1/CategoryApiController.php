<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\CategoryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCategoryRequest;
use App\Http\Requests\Api\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CategoryApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Category::query();
        
        // Si pas admin, ne montrer que les catégories de l'utilisateur connecté
        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        } else if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Recherche par nom
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        
        // Tri
        $sortField = $request->input('sort', 'name');
        $sortDirection = $request->input('direction', 'asc');
        
        $allowedSortFields = ['id', 'name', 'created_at'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }
        
        // Inclure les relations
        if ($request->has('include')) {
            $includes = explode(',', $request->input('include'));
            $allowedIncludes = ['user', 'expenses', 'incomes'];
            
            $validIncludes = array_intersect($includes, $allowedIncludes);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }
        
        // Inclure les comptes
        if ($request->has('include_counts')) {
            $query->withCount(['expenses', 'incomes']);
        }
        
        $categories = $query->paginate($request->input('per_page', 15));
        
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        // Créer un DTO à partir de la requête validée
        $categoryDTO = CategoryDTO::fromRequest($request, auth()->id());
        
        // Autoriser l'action
        $this->authorize('create', Category::class);
        
        // Créer la catégorie
        $category = Category::create($categoryDTO->toArray());
        
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Category $category)
    {
        // Autoriser l'action
        $this->authorize('view', $category);
        
        // Inclure les relations
        if ($request->has('include')) {
            $includes = explode(',', $request->input('include'));
            $allowedIncludes = ['user', 'expenses', 'incomes'];
            
            $validIncludes = array_intersect($includes, $allowedIncludes);
            if (!empty($validIncludes)) {
                $category->load($validIncludes);
            }
        }
        
        // Inclure les comptes
        if ($request->has('include_counts')) {
            $category->loadCount(['expenses', 'incomes']);

        }
        
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // Autoriser l'action
        $this->authorize('update', $category);
        
        // Créer un DTO à partir de la requête validée
        $categoryDTO = CategoryDTO::fromRequest($request, $category->user_id);
        
        // Mettre à jour la catégorie
        $category->update($categoryDTO->toArray());
        
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Autoriser l'action
        $this->authorize('delete', $category);
        
        // Vérifier si la catégorie est utilisée
        if ($category->expenses()->count() > 0 || $category->incomes()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete a category that is in use'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $category->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
