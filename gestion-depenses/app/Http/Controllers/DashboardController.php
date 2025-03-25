<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');

            // L'utilisateur standard ne voit que ses propres statistiques
            $stats = [
                'totalExpenses' => Expense::where('user_id', $user->id)->sum('amount'),
                'totalIncomes' => Income::where('user_id', $user->id)->sum('amount'),
                'balance' => Income::where('user_id', $user->id)->sum('amount') - Expense::where('user_id', $user->id)->sum('amount'),
                'expenseCount' => Expense::where('user_id', $user->id)->count(),
                'incomeCount' => Income::where('user_id', $user->id)->count(),
                'categoryCount' => Category::where('user_id', $user->id)->count(),
                'userCount' => 1, // Juste l'utilisateur lui-même
            ];
            
            // Dernières transactions de l'utilisateur
            $latestExpenses = Expense::with('category')
                                   ->where('user_id', $user->id)
                                   ->latest()
                                   ->take(5)
                                   ->get();
                                    
            $latestIncomes = Income::with('category')
                                 ->where('user_id', $user->id)
                                 ->latest()
                                 ->take(5)
                                 ->get();
            
            // Données pour graphique - Dépenses par catégorie (utilisateur)
            $expensesByCategory = Expense::select('categories.name', DB::raw('SUM(expenses.amount) as total'))
                                        ->join('categories', 'expenses.category_id', '=', 'categories.id')
                                        ->where('expenses.user_id', $user->id)
                                        ->groupBy('categories.name')
                                        ->get();
        
        
        return view('dashboard.index', compact(
            'stats',
            'latestExpenses',
            'latestIncomes',
            'expensesByCategory'
        ));
    }
}