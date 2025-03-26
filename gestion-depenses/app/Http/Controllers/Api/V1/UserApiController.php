<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreUserRequest ;
use App\Http\Requests\Api\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;  
class UserApiController extends Controller
{

    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        
        $query = User::query();
        
        // Filtrage par statut
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Filtrage par rôle
        if ($request->has('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        // Recherche par nom ou email
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
        
        // Tri
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        // Liste des champs de tri autorisés
        $allowedSortFields = ['id', 'name', 'email', 'created_at', 'updated_at'];
        
        // Vérifier que le champ de tri est autorisé
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }
        
        // Inclure les relations
        if ($request->has('include')) {
            $includes = explode(',', $request->input('include'));
            $allowedIncludes = ['roles', 'expenses', 'incomes'];
            
            $validIncludes = array_intersect($includes, $allowedIncludes);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }
        
        $users = $query->paginate($request->input('per_page', 15));
        
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);
        
        // Créer un DTO à partir de la requête validée
        $userDTO = UserDTO::fromRequest($request);
        
        // Créer l'utilisateur
        $user = User::create($userDTO->toArray());
        
        // Assigner le rôle
        if ($request->has('role')) {
            $role = Role::where('name', $request->role)->first();
            if ($role) {
                $user->assignRole($role);
            }
        } else {
            $user->assignRole('user'); // Rôle par défaut
        }
        
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        $this->authorize('view', $user);
        
        // Inclure les relations
        if ($request->has('include')) {
            $includes = explode(',', $request->input('include'));
            $allowedIncludes = ['roles', 'expenses', 'incomes'];
            
            $validIncludes = array_intersect($includes, $allowedIncludes);
            if (!empty($validIncludes)) {
                $user->load($validIncludes);
            }
        }
        
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        
        // Créer un DTO à partir de la requête validée
        $userDTO = UserDTO::fromRequest($request);
        
        // Mettre à jour l'utilisateur
        $user->update($userDTO->toArray());
        
        // Mettre à jour le rôle si nécessaire
        if ($request->has('role') && $request->user()->hasRole('admin')) {
            $user->syncRoles([$request->role]);
        }
        
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot delete your own account'
            ], Response::HTTP_FORBIDDEN);
        }
        
        $user->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    
    /**
     * Toggle user active status.
     */
    public function toggleActive(User $user)
    {
        $this->authorize('toggleActive', $user);
        
        // Empêcher de se bloquer soi-même
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot block your own account'
            ], Response::HTTP_FORBIDDEN);
        }
        
        $user->is_active = !$user->is_active;
        $user->save();
        
        return new UserResource($user);
    }
}
