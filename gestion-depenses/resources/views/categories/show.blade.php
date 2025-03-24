<!-- resources/views/categories/show.blade.php -->
@extends('layouts.app')

@section('title', 'Détails de la catégorie')

@section('header', 'Détails de la catégorie : ' . $category->name)

@section('content')
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b">
            <h2 class="text-xl font-bold">Informations</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Nom</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $category->name }}</p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Créée le</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $category->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Nombre de dépenses</h3>
                    <p class="mt-1">
                        <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            {{ $category->expenses->count() }}
                        </span>
                    </p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Nombre de revenus</h3>
                    <p class="mt-1">
                        <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $category->incomes->count() }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-bold">Dernières dépenses</h2>
            </div>
            <div class="p-6">
                @if($expenses->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($expenses as $expense)
                            <div class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $expense->description }}</p>
                                    <p class="text-sm text-gray-500">{{ $expense->date->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-red-600 font-bold">{{ number_format($expense->amount, 2, ',', ' ') }} €</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucune dépense pour cette catégorie</p>
                @endif
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-bold">Derniers revenus</h2>
            </div>
            <div class="p-6">
                @if($incomes->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($incomes as $income)
                            <div class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $income->description }}</p>
                                    <p class="text-sm text-gray-500">{{ $income->date->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-green-600 font-bold">{{ number_format($income->amount, 2, ',', ' ') }} €</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucun revenu pour cette catégorie</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="mt-6 flex justify-between">
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
            <i class="fas fa-edit mr-1"></i> Modifier
        </a>
        
        <form action="{{ route('categories.destroy', $category) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                <i class="fas fa-trash mr-1"></i> Supprimer
            </button>
        </form>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('categories.index') }}" class="text-primary-600 hover:text-primary-800">
            <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
        </a>
    </div>
@endsection