<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des utilisateurs
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $this->authorize('create', User::class);
        
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        
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
     * Affiche les détails d'un utilisateur
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        return view('users.show', compact('user'));
    }

    /**
     * Affiche le formulaire de modification
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Met à jour un utilisateur (pour les administrateurs)
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        // Si c'est un administrateur, il peut uniquement modifier le rôle
        if (Auth::user()->hasRole('admin') && Auth::id() !== $user->id) {
            $validated = $request->validate([
                'role' => 'required|exists:roles,name',
            ]);

            // Mise à jour du rôle uniquement
            $user->syncRoles([$validated['role']]);
            
            return redirect()->route('users.index')
                            ->with('success', 'Rôle de l\'utilisateur mis à jour avec succès');
        } 
        // Si c'est l'utilisateur lui-même, il peut modifier ses informations personnelles
        else {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
            ]);
            
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            
            $user->save();
            
            return redirect()->route('profile.edit')
                            ->with('success', 'Profil mis à jour avec succès');
        }
    }

    /**
     * Supprime un utilisateur
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        // Empêcher la suppression de son propre compte
        if ($user->id === Auth::id()) {
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
     * Bloque ou débloque un utilisateur
     */
    public function toggleActive(User $user)
    {
        $this->authorize('toggleActive', $user);
        
        // Empêcher de se bloquer soi-même
        if ($user->id === Auth::id()) {
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
