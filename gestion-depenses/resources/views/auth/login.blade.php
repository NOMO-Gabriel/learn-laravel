<!-- resources/views/auth/login.blade.php -->
@extends('layouts.guest')

@section('title', 'Connexion')
@section('subtitle', 'Connectez-vous à votre compte')

@section('content')
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
            <input id="password" type="password" name="password" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('password') border-red-500 @enderror">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4 flex items-center">
            <input id="remember_me" type="checkbox" name="remember" 
                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
            <label for="remember_me" class="ml-2 block text-sm text-gray-700">Se souvenir de moi</label>
        </div>

        <div class="flex items-center justify-between mt-6">
            <div class="text-sm">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-primary-600 hover:text-primary-500">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>
            
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Se connecter
            </button>
        </div>
        
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Vous n'avez pas de compte ?
                <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-500">
                    Inscrivez-vous
                </a>
            </p>
        </div>
    </form>
@endsection