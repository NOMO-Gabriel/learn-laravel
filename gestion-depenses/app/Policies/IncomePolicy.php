<?php

namespace App\Policies;

use App\Models\Income;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncomePolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir la liste des revenus.
     */
    public function viewAny(User $user): bool
    {
        return true; // L'affichage de la liste sera filtré dans le contrôleur.
    }

    /**
     * Détermine si l'utilisateur peut voir un revenu spécifique.
     */
    public function view(User $user, Income $income): bool
    {
        return $user->id === $income->user_id;
    }

    /**
     * Détermine si l'utilisateur peut créer un revenu.
     */
    public function create(User $user): bool
    {
        return true; // Tous les utilisateurs peuvent ajouter des revenus.
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour un revenu.
     */
    public function update(User $user, Income $income): bool
    {
        return $user->id === $income->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer un revenu.
     */
    public function delete(User $user, Income $income): bool
    {
        return $user->id === $income->user_id;
    }
}
