<?php

use App\Http\Controllers\Api\V1\CategoryApiController;
use App\Http\Controllers\Api\V1\ExpenseApiController;
use App\Http\Controllers\Api\V1\IncomeApiController;
use App\Http\Controllers\Api\V1\UserApiController;
use App\Http\Controllers\Api\V1\ProfileApiController;
use App\Http\Controllers\Api\V1\AuthApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Route pour obtenir l'utilisateur authentifié
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes API v1
Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    // Routes pour les utilisateurs
    Route::apiResource('users', UserApiController::class);
    Route::patch('users/{user}/toggle-active', [UserApiController::class, 'toggleActive'])->name('api.users.toggle-active');
    
    // Routes pour les dépenses
    Route::apiResource('expenses', ExpenseApiController::class);
    
    // Routes pour les revenus
    Route::apiResource('incomes', IncomeApiController::class);
    
    // Routes pour les catégories
    Route::apiResource('categories', CategoryApiController::class);

    // Routes pour le profil
    Route::get('profile', [ProfileApiController::class, 'show'])->name('api.profile.show');
    Route::put('profile', [ProfileApiController::class, 'update'])->name('api.profile.update');
    Route::post('profile/image', [ProfileApiController::class, 'updateImage'])->name('api.profile.update-image');
    Route::delete('profile', [ProfileApiController::class, 'destroy'])->name('api.profile.destroy');

});

// Routes d'authentification (sans middleware d'authentification)
Route::prefix('v1')->group(function () {
    Route::post('login', [AuthApiController::class, 'login'])->name('api.login');
    Route::post('register', [AuthApiController::class, 'register'])->name('api.register');
    
    // Routes protégées par authentification
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user', [AuthApiController::class, 'user'])->name('api.user');
        Route::post('logout', [AuthApiController::class, 'logout'])->name('api.logout');
        Route::post('logout-all', [AuthApiController::class, 'logoutAll'])->name('api.logout.all');
    });
});

