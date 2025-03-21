<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
       
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Traiter l'image de profil si fournie
        if ($request->hasFile('profile_image')) {
            // Supprimer l'ancienne image
            if ($user->profile_image) {
                Storage::disk('public')->delete('profiles/' . $user->profile_image);
            }
            
            $imageName = time() . '_' . $user->id . '.' . $request->profile_image->extension();
            $request->profile_image->storeAs('profiles', $imageName, 'public');
            $user->profile_image = $imageName;
        }

        $user->save();

        // Mettre à jour le rôle
        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')
                         ->with('success', 'Utilisateur mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
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
     * Toggle user active status
     */
    public function toggleActive(User $user)
    {
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
