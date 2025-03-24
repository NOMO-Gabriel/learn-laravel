<!-- resources/views/categories/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Modifier la catégorie')

@section('header', 'Modifier la catégorie')

@section('content')
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        
        <x-form.input name="name" label="Nom de la catégorie" :value="$category->name" required />
        
        <div class="mt-6 flex justify-between">
            <x-button type="submit" color="success">
                <i class="fas fa-save mr-1"></i> Mettre à jour
            </x-button>
            
            <x-button type="button" color="gray" onclick="window.location='{{ route('categories.index') }}'">
                <i class="fas fa-times mr-1"></i> Annuler
            </x-button>
        </div>
    </form>
@endsection