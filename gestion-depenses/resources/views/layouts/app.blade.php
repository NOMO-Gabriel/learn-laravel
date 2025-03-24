<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestion Dépenses')</title>
    
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('partials.header')
        
        <div class="flex">
            @include('partials.sidebar')
            
            <main class="flex-1 p-4 overflow-hidden">
                @if (session('success'))
                    <x-alert type="success" class="mb-4">
                        {{ session('success') }}
                    </x-alert>
                @endif
                
                @if (session('error'))
                    <x-alert type="error" class="mb-4">
                        {{ session('error') }}
                    </x-alert>
                @endif
                
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h1 class="text-2xl font-bold mb-4">@yield('header')</h1>
                    @yield('content')
                </div>
            </main>
        </div>
        
        @include('partials.footer')
    </div>
    
    @stack('scripts')
</body>
</html>
