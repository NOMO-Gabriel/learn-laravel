<!-- resources/views/layouts/guest.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestion Dépenses')</title>
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-6 text-center">
                <h1 class="text-3xl font-bold text-primary-600">
                    Gestion Dépenses
                </h1>
                <p class="mt-2 text-gray-600">@yield('subtitle', 'Votre application de gestion financière')</p>
            </div>
            
            @if (session('status'))
                <x-alert type="success" class="mb-4">
                    {{ session('status') }}
                </x-alert>
            @endif
            
            @yield('content')
        </div>
    </div>
</body>
</html>
