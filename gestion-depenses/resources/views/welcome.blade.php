<!-- resources/views/welcome.blade.php -->
@extends('layouts.app')

@section('title', 'Bienvenue')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800">Bienvenue sur notre site !</h1>
    <p class="text-gray-600 mt-2">Nous sommes ravis de vous accueillir.</p>

    <a href="{{ route('home') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
        Accueil
    </a>
@endsection
