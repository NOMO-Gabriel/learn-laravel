<!-- resources/views/incomes/show.blade.php -->
@extends('layouts.app')

@section('title', 'Détails du revenu')

@section('header', 'Détails du revenu')

@section('content')
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="border-b px-6 py-4">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">{{ $income->description }}</h2>
                <span class="text-green-600 font-bold text-xl">{{ number_format($income->amount, 2, ',', ' ') }} €</span>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Date</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $income->date->format('d/m/Y') }}</p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Catégorie</h3>
                    <p class="mt-1">
                        <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $income->category->name }}
                        </span>
                    </p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Utilisateur</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $income->user->name }}</p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Créé le</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $income->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-4">
            <div class="flex justify-between">
                <a href="{{ route('incomes.edit', $income) }}" class="btn btn-primary">
                    <i class="fas fa-edit mr-1"></i> Modifier
                </a>
                
                <form action="{{ route('incomes.destroy', $income) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce revenu ?')">
                        <i class="fas fa-trash mr-1"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('incomes.index') }}" class="text-primary-600 hover:text-primary-800">
            <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
        </a>
    </div>
@endsection