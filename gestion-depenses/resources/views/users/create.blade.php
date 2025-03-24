<!-- resources/views/users/create.blade.php -->
@extends('layouts.app')

@section('title', 'Nouvel utilisateur')

@section('header', 'Ajouter un utilisateur')

@section('content')
    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form.input name="name" label="Nom complet" required />
            
            <x-form.input name="email" label="Adresse email" type="email" required />
            
            <x-form.input name="password" label="Mot de passe" type="password" required />
            
            <x-form.input name="password_confirmation" label="Confirmer le mot de passe" type="password" required />
            
            <div class="md:col-span-2">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Rôle</label>
                <div class="mt-2 space-y-2">
                    @foreach($roles as $role)
                        <div class="flex items-center">
                            <input id="role_{{ $role->id }}" name="role" type="radio" value="{{ $role->name }}" class="h-4 w-4 text-primary-600 focus:ring-primary-500">
                            <label for="role_{{ $role->id }}" class="ml-2 block text-sm text-gray-900">
                                {{ ucfirst($role->name) }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('role')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="profile_image" class="block text-gray-700 text-sm font-bold mb-2">Photo de profil</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*" class="mt-1 block w-full">
                @error('profile_image')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mt-6 flex justify-between">
            <x-button type="submit" color="success">
                <i class="fas fa-save mr-1"></i> Enregistrer
            </x-button>
            
            <x-button type="button" color="gray" onclick="window.location='{{ route('users.index') }}'">
                <i class="fas fa-times mr-1"></i> Annuler
            </x-button>
        </div>
    </form>
@endsection