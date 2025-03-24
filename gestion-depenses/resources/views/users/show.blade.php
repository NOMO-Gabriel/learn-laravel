<!-- resources/views/users/show.blade.php -->
@extends('layouts.app')

@section('title', 'Détails de l\'utilisateur')

@section('header', 'Détails de l\'utilisateur')

@section('content')
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/3 bg-gray-50 p-8 border-r">
                <div class="text-center">
                    <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}" class="h-32 w-32 rounded-full mx-auto object-cover">
                    <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-500">{{ $user->email }}</p>
                    
                    <div class="mt-4">
                        @foreach($user->roles as $role)
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $role->name === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </div>
                    
                    <div class="mt-2">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Actif' : 'Bloqué' }}
                        </span>
                    </div>
                </div>
                
                <div class="mt-8 border-t pt-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-medium text-gray-500">Inscrit le</span>
                        <span class="text-gray-900">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-medium text-gray-500">Dernière connexion</span>
                        <span class="text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="md:w-2/3 p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Statistiques de l'utilisateur</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <div class="bg-white rounded-lg border p-4">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-100 text-red-600">
                                <i class="fas fa-arrow-down text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total des dépenses</p>
                                <p class="text-xl font-bold text-gray-900">{{ number_format($user->expenses->sum('amount'), 2, ',', ' ') }} €</p>
                                <p class="text-sm text-gray-500">{{ $user->expenses->count() }} dépenses</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg border p-4">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-arrow-up text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total des revenus</p>
                                <p class="text-xl font-bold text-gray-900">{{ number_format($user->incomes->sum('amount'), 2, ',', ' ') }} €</p>
                                <p class="text-sm text-gray-500">{{ $user->incomes->count() }} revenus</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h3 class="text-lg font-bold text-gray-900 mb-4">Activité récente</h3>
                
                <div class="border rounded-lg divide-y">
                    @if($user->expenses->count() > 0 || $user->incomes->count() > 0)
                        @php
                            $activities = collect()
                                ->merge($user->expenses->map(function($expense) {
                                    return [
                                        'type' => 'expense',
                                        'data' => $expense,
                                        'date' => $expense->created_at
                                    ];
                                }))
                                ->merge($user->incomes->map(function($income) {
                                    return [
                                        'type' => 'income',
                                        'data' => $income,
                                        'date' => $income->created_at
                                    ];
                                }))
                                ->sortByDesc('date')
                                ->take(10);
                        @endphp
                        
                        @foreach($activities as $activity)
                            <div class="p-4 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $activity['data']->description }}</p>
                                    <div class="flex items-center mt-1">
                                        <span class="text-xs text-gray-500 mr-2">{{ $activity['data']->date->format('d/m/Y') }}</span>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $activity['type'] === 'expense' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $activity['type'] === 'expense' ? 'Dépense' : 'Revenu' }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <span class="{{ $activity['type'] === 'expense' ? 'text-red-600' : 'text-green-600' }} font-bold">
                                        {{ $activity['type'] === 'expense' ? '-' : '+' }}{{ number_format($activity['data']->amount, 2, ',', ' ') }} €
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-4 text-center text-gray-500">
                            Aucune activité pour cet utilisateur
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 border-t">
            <div class="flex justify-between">
                <div>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-1"></i> Modifier
                    </a>
                    
                    <form action="{{ route('users.toggleActive', $user) }}" method="POST" class="inline-block ml-2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn {{ $user->is_active ? 'btn-danger' : 'btn-success' }}" onclick="return confirm('Êtes-vous sûr de vouloir {{ $user->is_active ? 'bloquer' : 'débloquer' }} cet utilisateur ?')">
                            <i class="fas {{ $user->is_active ? 'fa-lock' : 'fa-unlock' }} mr-1"></i> 
                            {{ $user->is_active ? 'Bloquer' : 'Débloquer' }}
                        </button>
                    </form>
                </div>
                
                @if($user->id !== auth()->id())
                    <form action="{{ route('users.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible et supprimera toutes ses données.')">
                            <i class="fas fa-trash mr-1"></i> Supprimer
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('users.index') }}" class="text-primary-600 hover:text-primary-800">
            <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
        </a>
    </div>
@endsection