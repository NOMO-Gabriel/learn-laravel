<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir la liste des utilisateurs.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Détermine si l'utilisateur peut voir un profil d'utilisateur.
     */
    public function view(User $user, User $targetUser): bool
    {
        // Un utilisateur peut voir son propre profil ou un admin peut voir n'importe quel profil
        return $user->id === $targetUser->id || $user->hasRole('admin');
    }

    /**
     * Détermine si l'utilisateur peut créer d'autres utilisateurs.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour un profil.
     */
    public function update(User $user, User $targetUser): bool
    {
        // Un utilisateur peut modifier son propre profil ou un admin peut modifier n'importe quel profil
        return $user->id === $targetUser->id || $user->hasRole('admin');
    }

    /**
     * Détermine si l'utilisateur peut supprimer un compte.
     */
    public function delete(User $user, User $targetUser): bool
    {
        // Un admin peut supprimer n'importe quel compte sauf le sien
        return $user->hasRole('admin') && $user->id !== $targetUser->id;
    }

    /**
     * Détermine si l'utilisateur peut bloquer/débloquer un compte.
     */
    public function toggleActive(User $user, User $targetUser): bool
    {
        // Un admin peut bloquer/débloquer n'importe quel compte sauf le sien
        return $user->hasRole('admin') && $user->id !== $targetUser->id;
    }
}