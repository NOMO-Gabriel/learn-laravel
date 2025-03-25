<!-- resources/views/users/show.blade.php -->
@extends('layouts.app')

@section('title', 'Détails de l\'utilisateur')

@section('header', 'Détails de l\'utilisateur')

@section('content')
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex items-center mb-6">
                <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}" class="h-24 w-24 rounded-full object-cover mr-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach($user->roles as $role)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $role->name === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                        
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Actif' : 'Bloqué' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="border-t pt-4 mt-4">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Informations du compte</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Inscrit le</p>
                        <p class="text-gray-900">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Dernière mise à jour</p>
                        <p class="text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 border-t">
            <div class="flex justify-between">
                <div>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-1"></i> Modifier
                    </a>
                    
                    @if(auth()->id() !== $user->id)
                        <form action="{{ route('users.toggleActive', $user) }}" method="POST" class="inline-block ml-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn {{ $user->is_active ? 'btn-danger' : 'btn-success' }}" onclick="return confirm('Êtes-vous sûr de vouloir {{ $user->is_active ? 'bloquer' : 'débloquer' }} cet utilisateur ?')">
                                <i class="fas {{ $user->is_active ? 'fa-lock' : 'fa-unlock' }} mr-1"></i> 
                                {{ $user->is_active ? 'Bloquer' : 'Débloquer' }}
                            </button>
                        </form>
                    @endif
                </div>
                
                @if(auth()->id() !== $user->id)
                    <form action="{{ route('users.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible et supprimera toutes ses données.')">
                            <i class="fas fa-trash mr-1"></i> Supprimer
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('users.index') }}" class="text-primary-600 hover:text-primary-800">
            <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
        </a>
    </div>
@endsection