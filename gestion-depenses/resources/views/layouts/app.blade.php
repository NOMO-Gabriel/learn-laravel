<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mon Application')</title>
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen">
    <header>
        @include('partials.header') <!-- Inclusion du header -->
    </header>

    
    
    <div class="container mx-auto flex-grow px-4 py-8">
        @yield('content') <!-- Contenu spécifique de chaque page -->
    </div>
    
    <footer>
        @include('partials.footer') <!-- Inclusion du footer -->
    </footer>
    
    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
