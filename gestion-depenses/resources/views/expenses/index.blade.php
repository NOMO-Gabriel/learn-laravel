<!-- resources/views/expenses/index.blade.php -->
@extends('layouts.app')

@section('title', 'Liste des dépenses')

@section('header', 'Gestion des dépenses')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
        <div>
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Nouvelle dépense
            </a>
        </div>
        
        <div>
            <form action="{{ route('expenses.index') }}" method="GET" class="flex flex-wrap gap-2">
                <select name="category_id" class="rounded border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                
                <input type="date" name="date_start" value="{{ request('date_start') }}" class="rounded border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Date début">
                
                <input type="date" name="date_end" value="{{ request('date_end') }}" class="rounded border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Date fin">
                
                <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-filter"></i>
                </button>
            </form>
        </div>
    </div>
    
    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($expenses as $expense)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $expense->date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $expense->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $expense->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <span class="text-red-600 font-bold">
                                {{ number_format($expense->amount, 2, ',', ' ') }} €
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('expenses.show', $expense) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('expenses.edit', $expense) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette dépense ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Aucune dépense trouvée
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $expenses->links() }}
    </div>
@endsection