<!-- resources/views/auth/forgot-password.blade.php -->
@extends('layouts.guest')

@section('title', 'Réinitialisation du mot de passe')
@section('subtitle', 'Récupérez l\'accès à votre compte')

@section('content')
    <div class="mb-4 text-sm text-gray-600">
        Vous avez oublié votre mot de passe ? Pas de problème. Indiquez-nous votre adresse e-mail et nous vous enverrons un lien de réinitialisation qui vous permettra d'en choisir un nouveau.
    </div>

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-500 text-sm">
                Retour à la connexion
            </a>
            
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Envoyer le lien
            </button>
        </div>
    </form>
@endsection