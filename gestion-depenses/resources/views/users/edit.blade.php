<!-- resources/views/users/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Modifier l\'utilisateur')

@section('header', 'Modifier l\'utilisateur')

@section('content')
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
            <h2 class="text-lg font-semibold">Informations utilisateur</h2>
            <div class="mt-2 flex items-center">
                <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}" class="h-12 w-12 rounded-full mr-4">
                <div>
                    <p class="font-medium">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </div>
        
        <div class="mb-4">
            <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Rôle</label>
            <div class="mt-2 space-y-2">
                @foreach($roles as $role)
                    <div class="flex items-center">
                        <input id="role_{{ $role->id }}" name="role" type="radio" value="{{ $role->name }}" 
                               class="h-4 w-4 text-primary-600 focus:ring-primary-500" 
                               {{ $user->hasRole($role->name) ? 'checked' : '' }}>
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
        
        <div class="mt-6 flex justify-between">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-save mr-1"></i> Mettre à jour
            </button>
            
            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-times mr-1"></i> Annuler
            </a>
        </div>
    </form>
@endsection