<!-- resources/views/categories/create.blade.php -->
@extends('layouts.app')

@section('title', 'Nouvelle catégorie')

@section('header', 'Ajouter une catégorie')

@section('content')
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        
        <x-form.input name="name" label="Nom de la catégorie" required />
        
        <div class="mt-6 flex justify-between">
            <x-button type="submit" color="success">
                <i class="fas fa-save mr-1"></i> Enregistrer
            </x-button>
            
            <x-button type="button" color="gray" onclick="window.location='{{ route('categories.index') }}'">
                <i class="fas fa-times mr-1"></i> Annuler
            </x-button>
        </div>
    </form>
@endsection
