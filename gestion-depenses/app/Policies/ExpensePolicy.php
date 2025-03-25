<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir la liste des dépenses.
     * Ici, on pourrait restreindre mais cela dépend du besoin.
     */
    public function viewAny(User $user): bool
    {
        return true; // Permet d'afficher l'index, mais les données seront filtrées dans le contrôleur.
    }

    /**
     * Détermine si l'utilisateur peut voir une dépense spécifique.
     */
    public function view(User $user, Expense $expense): bool
    {
        return $user->id === $expense->user_id;
    }

    /**
     * Détermine si l'utilisateur peut créer une dépense.
     */
    public function create(User $user): bool
    {
        return true; // Tous les utilisateurs peuvent créer des dépenses.
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour une dépense.
     */
    public function update(User $user, Expense $expense): bool
    {
        return $user->id === $expense->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer une dépense.
     */
    public function delete(User $user, Expense $expense): bool
    {
        return $user->id === $expense->user_id;
    }
}
