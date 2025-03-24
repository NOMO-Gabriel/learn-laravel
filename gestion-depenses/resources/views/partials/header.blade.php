<!-- resources/views/partials/header.blade.php -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-primary-600">
                <i class="fas fa-wallet mr-2"></i>
                Gestion Dépenses
            </a>
        </div>
        
        <div class="flex items-center">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center text-gray-700 hover:text-primary-500 focus:outline-none">
                    <img src="{{ auth()->user()->profile_image_url }}" alt="Photo de profil" class="h-8 w-8 rounded-full object-cover">
                    <span class="ml-2">{{ auth()->user()->name }}</span>
                    <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2"></i> Mon profil
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>