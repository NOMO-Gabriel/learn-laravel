<!-- resources/views/partials/sidebar.blade.php -->
<aside class="bg-gray-800 text-white w-64 min-h-screen p-4">
    <nav>
        <div class="mb-8">
            <h3 class="text-xs uppercase text-gray-400 font-bold tracking-wider mb-2">
                Principal
            </h3>
            <ul>
                <li class="mb-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center p-2 rounded {{ request()->routeIs('dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                        Tableau de bord
                    </a>
                </li>
                
                <li class="mb-2">
                    <a href="{{ route('expenses.index') }}" class="flex items-center p-2 rounded {{ request()->routeIs('expenses.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-arrow-down w-5 mr-3"></i>
                        Dépenses
                    </a>
                </li>
                
                <li class="mb-2">
                    <a href="{{ route('incomes.index') }}" class="flex items-center p-2 rounded {{ request()->routeIs('incomes.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-arrow-up w-5 mr-3"></i>
                        Revenus
                    </a>
                </li>
                
                <li class="mb-2">
                    <a href="{{ route('categories.index') }}" class="flex items-center p-2 rounded {{ request()->routeIs('categories.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-tags w-5 mr-3"></i>
                        Catégories
                    </a>
                </li>
            </ul>
        </div>
        
        @role('admin')
        <div>
            <h3 class="text-xs uppercase text-gray-400 font-bold tracking-wider mb-2">
                Administration
            </h3>
            <ul>
                <li class="mb-2">
                    <a href="{{ route('users.index') }}" class="flex items-center p-2 rounded {{ request()->routeIs('users.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-users w-5 mr-3"></i>
                        Utilisateurs
                    </a>
                </li>
            </ul>
        </div>
        @endrole
    </nav>
</aside>