<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('header', 'Tableau de bord')

@section('content')
    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total des revenus</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalIncomes'], 2, ',', ' ') }} €</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total des dépenses</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalExpenses'], 2, ',', ' ') }} €</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Balance</p>
                    <p class="text-2xl font-bold {{ $stats['balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($stats['balance'], 2, ',', ' ') }} €
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-tag text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Catégories</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['categoryCount'] }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Dépenses par catégorie</h3>
            <div class="h-80">
                <canvas id="expensesByCategoryChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Balance mensuelle</h3>
            <div class="h-80">
                <canvas id="monthlyBalanceChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Dernières transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-bold">Dernières dépenses</h3>
                <a href="{{ route('expenses.index') }}" class="text-primary-600 hover:text-primary-800 text-sm">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="p-6">
                @if($latestExpenses->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($latestExpenses as $expense)
                            <div class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $expense->description }}</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <span class="mr-2">{{ $expense->date->format('d/m/Y') }}</span>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $expense->category->name }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-red-600 font-bold">{{ number_format($expense->amount, 2, ',', ' ') }} €</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucune dépense enregistrée</p>
                @endif
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-bold">Derniers revenus</h3>
                <a href="{{ route('incomes.index') }}" class="text-primary-600 hover:text-primary-800 text-sm">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="p-6">
                @if($latestIncomes->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($latestIncomes as $income)
                            <div class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $income->description }}</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <span class="mr-2">{{ $income->date->format('d/m/Y') }}</span>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $income->category->name }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-green-600 font-bold">{{ number_format($income->amount, 2, ',', ' ') }} €</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucun revenu enregistré</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Données pour le graphique des dépenses par catégorie
    const expensesByCategoryData = {
        labels: {!! json_encode($expensesByCategory->pluck('name')) !!},
        datasets: [{
            label: 'Dépenses',
            data: {!! json_encode($expensesByCategory->pluck('total')) !!},
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(255, 159, 64, 0.7)',
                'rgba(199, 199, 199, 0.7)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(199, 199, 199, 1)',
            ],
            borderWidth: 1
        }]
    };

    // Configuration du graphique des dépenses par catégorie
    const expensesByCategoryConfig = {
        type: 'doughnut',
        data: expensesByCategoryData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        },
    };

    // Création du graphique des dépenses par catégorie
    new Chart(
        document.getElementById('expensesByCategoryChart'),
        expensesByCategoryConfig
    );

    // Données pour le graphique de la balance mensuelle
    // (Ceci est un exemple, vous devriez avoir des données réelles)
    const monthlyBalanceData = {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
        datasets: [
            {
                label: 'Revenus',
                data: [4500, 4200, 4800, 5000, 4600, 5200],
                backgroundColor: 'rgba(46, 204, 113, 0.2)',
                borderColor: 'rgba(46, 204, 113, 1)',
                borderWidth: 2,
                yAxisID: 'y',
            },
            {
                label: 'Dépenses',
                data: [3800, 4100, 3900, 4200, 4300, 4000],
                backgroundColor: 'rgba(231, 76, 60, 0.2)',
                borderColor: 'rgba(231, 76, 60, 1)',
                borderWidth: 2,
                yAxisID: 'y',
            },
            {
                label: 'Balance',
                data: [700, 100, 900, 800, 300, 1200],
                backgroundColor: 'rgba(52, 152, 219, 0.2)',
                borderColor: 'rgba(52, 152, 219, 1)',
                borderWidth: 2,
                type: 'line',
                yAxisID: 'y1',
            }
        ]
    };

    // Configuration du graphique de la balance mensuelle
    const monthlyBalanceConfig = {
        type: 'bar',
        data: monthlyBalanceData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Montant (€)'
                    }
                },
                y1: {
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                    title: {
                        display: true,
                        text: 'Balance (€)'
                    }
                },
            }
        },
    };

    // Création du graphique de la balance mensuelle
    new Chart(
        document.getElementById('monthlyBalanceChart'),
        monthlyBalanceConfig
    );
</script>
@endpush
