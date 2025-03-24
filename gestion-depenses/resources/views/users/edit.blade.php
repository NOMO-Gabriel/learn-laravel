<!-- resources/views/users/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Modifier l\'utilisateur')

@section('header', 'Modifier l\'utilisateur')

@section('content')
    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form.input name="name" label="Nom complet" :value="$user->name" required />
            
            <x-form.input name="email" label="Adresse email" type="email" :value="$user->email" required />
            
            <div class="md:col-span-2">
                <p class="text-sm text-gray-600 mb-4">Laissez les champs de mot de passe vides si vous ne souhaitez pas le modifier.</p>
            </div>
            
            <x-form.input name="password" label="Nouveau mot de passe" type="password" />
            
            <x-form.input name="password_confirmation" label="Confirmer le mot de passe" type="password" />
            
            <div class="md:col-span-2">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Rôle</label>
                <div class="mt-2 space-y-2">
                    @foreach($roles as $role)
                        <div class="flex items-center">
                            <input id="role_{{ $role->id }}" name="role" type="radio" value="{{ $role->name }}" class="h-4 w-4 text-primary-600 focus:ring-primary-500"
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
            
            <div class="md:col-span-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">Photo de profil actuelle</label>
                @if($user->profile_image)
                    <div class="mt-2 flex items-center">
                        <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}" class="h-20 w-20 rounded-full object-cover">
                        <span class="ml-4 text-sm text-gray-500">Téléchargez une nouvelle image pour remplacer l'actuelle</span>
                    </div>
                @else
                    <p class="text-sm text-gray-500">Aucune image de profil</p>
                @endif
            </div>
            
            <div class="md:col-span-2">
                <label for="profile_image" class="block text-gray-700 text-sm font-bold mb-2">Nouvelle photo de profil</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*" class="mt-1 block w-full">
                @error('profile_image')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mt-6 flex justify-between">
            <x-button type="submit" color="success">
                <i class="fas fa-save mr-1"></i> Mettre à jour
            </x-button>
            
            <x-button type="button" color="gray" onclick="window.location='{{ route('users.index') }}'">
                <i class="fas fa-times mr-1"></i> Annuler
            </x-button>
        </div>
    </form>
@endsection