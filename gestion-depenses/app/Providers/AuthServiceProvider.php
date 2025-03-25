<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Associer les models à leurs policies.
     */
    protected $policies = [
        \App\Models\Expense::class => \App\Policies\ExpensePolicy::class,
        \App\Models\Income::class => \App\Policies\IncomePolicy::class,
        \App\Models\Category::class => \App\Policies\CategoryPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();


        // // Définir un super-admin qui peut tout faire
        // Gate::before(function ($user, $ability) {
        //     return $user->hasRole('admin') ? true : null;
        // });
    }
}
