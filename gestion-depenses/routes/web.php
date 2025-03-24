<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Web
|--------------------------------------------------------------------------
|
| Définition de toutes les routes pour l'interface utilisateur
|
*/

// Route d'accueil accessible à tous
Route::get('/', function () {
    return view('welcome');
})->name('home');


// Les routes d'authentification générées par Breeze sont dans auth.php
// Elles incluent login, register, password reset, etc.
require __DIR__.'/auth.php';

// Routes protégées par authentification et vérification d'utilisateur actif
Route::middleware(['auth', 'verified', 'active.user'])->group(function () {
    // Dashboard - Page principale après connexion
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    
    // Routes pour le profil utilisateur - accessible à tous les utilisateurs authentifiés
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::patch('/profile/image', 'updateImage')->name('profile.updateImage');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
    
    // Routes pour les dépenses - autorisations gérées dans le contrôleur
    Route::resource('expenses', ExpenseController::class);
    
    // Routes pour les revenus - autorisations gérées dans le contrôleur
    Route::resource('incomes', IncomeController::class);
    
    // Routes pour les catégories - accessibles uniquement aux utilisateurs avec permission
    Route::resource('categories', CategoryController::class);
    
    // Routes pour la gestion des utilisateurs - accessibles uniquement aux administrateurs
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        
        // Route pour bloquer/débloquer un utilisateur
        Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])
            ->name('users.toggleActive');
    });
});