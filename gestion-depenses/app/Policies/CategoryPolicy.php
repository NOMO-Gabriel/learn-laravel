<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir la liste des catégories.
     */
    public function viewAny(User $user): bool
    {
        return true; // L'affichage de la liste sera filtré dans le contrôleur.
    }

    /**
     * Détermine si l'utilisateur peut voir une catégorie spécifique.
     */
    public function view(User $user, Category $category): bool
    {
        return $user->id === $category->user_id;
    }

    /**
     * Détermine si l'utilisateur peut créer une catégorie.
     */
    public function create(User $user): bool
    {
        return true; // Tous les utilisateurs peuvent ajouter des catégories.
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour une catégorie.
     */
    public function update(User $user, Category $category): bool
    {
        return $user->id === $category->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer une catégorie.
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->id === $category->user_id;
    }
}
