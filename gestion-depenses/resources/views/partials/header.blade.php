<!-- resources/views/partials/header.blade.php -->
<header class="bg-blue-600 text-white py-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center px-4">
        <h1 class="text-2xl font-bold">Mon Application</h1>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="{{ route('home') }}" class="hover:underline">Accueil</a></li>
                <li><a href="#" class="hover:underline">À propos</a></li>
                <li><a href="#" class="hover:underline">Contact</a></li>
            </ul>
        </nav>
    </div>
</header>
