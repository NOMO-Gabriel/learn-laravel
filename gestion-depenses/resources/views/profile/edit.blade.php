<!-- resources/views/profile/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Mon profil')

@section('header', 'Mon profil')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-center">
                    <div class="relative mx-auto w-32 h-32 mb-4">
                        <img class="rounded-full w-full h-full object-cover" src="{{ auth()->user()->profile_image_url }}" alt="{{ auth()->user()->name }}">
                        <button type="button" onclick="document.getElementById('profile_image_upload').click()" class="absolute bottom-0 right-0 bg-primary-500 text-white rounded-full p-2 hover:bg-primary-600">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    
                    <form action="{{ route('profile.updateImage') }}" method="POST" enctype="multipart/form-data" id="profile_image_form">
                        @csrf
                        @method('PATCH')
                        <input type="file" name="profile_image" id="profile_image_upload" class="hidden" onchange="document.getElementById('profile_image_form').submit();">
                    </form>
                    
                    <h3 class="text-lg font-medium text-gray-900">{{ auth()->user()->name }}</h3>
                    <p class="text-gray-500">{{ auth()->user()->email }}</p>
                </div>
                
                <div class="mt-6 border-t pt-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-500">Rôle</span>
                        <span class="text-sm font-medium text-gray-900">
                            @foreach(auth()->user()->roles as $role)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $role->name === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-500">Inscrit le</span>
                        <span class="text-sm font-medium text-gray-900">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Statut</span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ auth()->user()->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ auth()->user()->is_active ? 'Actif' : 'Bloqué' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="md:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-xl font-bold">Informations personnelles</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 gap-6">
        
                            <x-form.input name="name" label="Nom complet" :value="auth()->user()->name" />
                            
                            <x-form.input name="email" label="Adresse email" type="email" :value="auth()->user()->email" />
                            
                            <div>
                                <x-button type="submit" color="success">
                                    <i class="fas fa-save mr-1"></i> Mettre à jour
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-xl font-bold">Modification du mot de passe</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 gap-6">
                            <x-form.input name="current_password" label="Mot de passe actuel" type="password" />
                            
                            <x-form.input name="password" label="Nouveau mot de passe" type="password" />
                            
                            <x-form.input name="password_confirmation" label="Confirmer le nouveau mot de passe" type="password" />
                            
                            <div>
                                <x-button type="submit" color="primary">
                                    <i class="fas fa-key mr-1"></i> Changer le mot de passe
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-xl font-bold text-red-600">Suppression du compte</h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 mb-4">
                        Une fois votre compte supprimé, toutes vos ressources et données seront définitivement effacées. Avant de supprimer votre compte, veuillez télécharger toutes les données ou informations que vous souhaitez conserver.
                    </p>
                    
                    <button type="button" onclick="document.getElementById('delete-account-modal').classList.remove('hidden')" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i> Supprimer mon compte
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal pour la suppression du compte -->
    <div id="delete-account-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-xl font-bold text-red-600 mb-4">Êtes-vous sûr ?</h3>
            
            <p class="text-gray-700 mb-4">
                Cette action est irréversible. Veuillez confirmer votre mot de passe pour supprimer définitivement votre compte.
            </p>
            
            <form action="{{ route('profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                
                <x-form.input name="password" label="Mot de passe" type="password" />
                
                <div class="mt-6 flex justify-between">
                    <x-button type="submit" color="danger">
                        <i class="fas fa-trash mr-1"></i> Supprimer définitivement
                    </x-button>
                    
                    <x-button type="button" color="gray" onclick="document.getElementById('delete-account-modal').classList.add('hidden')">
                        <i class="fas fa-times mr-1"></i> Annuler
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection