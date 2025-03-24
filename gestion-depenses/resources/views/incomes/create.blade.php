<!-- resources/views/incomes/create.blade.php -->
@extends('layouts.app')

@section('title', 'Nouveau revenu')

@section('header', 'Ajouter un revenu')

@section('content')
    <form action="{{ route('incomes.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form.input name="description" label="Description" required />
            
            <x-form.input name="amount" label="Montant" type="number" step="0.01" required />
            
            <x-form.select name="category_id" label="Catégorie" :options="$categories->pluck('name', 'id')->toArray()" required />
            
            <x-form.input name="date" label="Date" type="date" value="{{ date('Y-m-d') }}" required />
        </div>
        
        <div class="mt-6 flex justify-between">
            <x-button type="submit" color="success">
                <i class="fas fa-save mr-1"></i> Enregistrer
            </x-button>
            
            <x-button type="button" color="gray" onclick="window.location='{{ route('incomes.index') }}'">
                <i class="fas fa-times mr-1"></i> Annuler
            </x-button>
        </div>
    </form>
@endsection