<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'totalExpenses' => Expense::sum('amount'),
            'totalIncomes' => Income::sum('amount'),
            'balance' => Income::sum('amount') - Expense::sum('amount'),
            'expenseCount' => Expense::count(),
            'incomeCount' => Income::count(),
            'categoryCount' => Category::count(),
            'userCount' => User::count(),
        ];
        
        // Dernières transactions
        $latestExpenses = Expense::with('category', 'user')
                                ->latest()
                                ->take(5)
                                ->get();
                                
        $latestIncomes = Income::with('category', 'user')
                              ->latest()
                              ->take(5)
                              ->get();
        
        // Données pour graphique - Dépenses par catégorie
        $expensesByCategory = Expense::select('categories.name', DB::raw('SUM(expenses.amount) as total'))
                                    ->join('categories', 'expenses.category_id', '=', 'categories.id')
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
