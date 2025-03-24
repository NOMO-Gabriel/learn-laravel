<!-- resources/views/welcome.blade.php -->
@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')<!-- Utilisation -->
<x-alert type="success" class="mb-4">
    <x-slot:title>Bravo !</x-slot:title>
    L'opération a été effectuée avec succès.
</x-alert>
<x-button type="submit" color="green" class="mt-4">
    Enregistrer
</x-button>

<x-button color="red" onclick="history.back()">
    Annuler
</x-button>

    <h1>Tableau de bord</h1>
    <p>Bienvenue sur votre tableau de bord !</p>
    <form action="{{ route('expenses.store') }}" method="POST">
    @csrf
    
    <x-form.input name="description" label="Description" required />
    <x-form.input name="amount" label="Montant" type="number" step="0.01" required />
    
    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">
        Enregistrer
    </button>
</form>

@endsection



