<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Dépenses - Suivez vos finances personnelles</title>
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Navigation -->
    <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-4">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-primary-600">
                    <i class="fas fa-wallet mr-2"></i>
                    Gestion Dépenses
                </a>
            </div>
            
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-primary-600 hover:text-primary-800 font-medium">
                        <i class="fas fa-tachometer-alt mr-1"></i> Mon Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-800 font-medium">Connexion</a>
                    <a href="{{ route('register') }}" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out">Inscription</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-500 to-primary-700 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Prenez le contrôle de vos finances personnelles</h1>
                    <p class="text-lg md:text-xl mb-8 opacity-90">Suivez vos dépenses, analysez vos habitudes financières et atteignez vos objectifs d'épargne.</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="bg-white text-primary-700 hover:text-primary-800 py-3 px-6 rounded-md font-semibold shadow-lg hover:shadow-xl transition duration-300 text-center">
                                Accéder à mon Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="bg-white text-primary-700 hover:text-primary-800 py-3 px-6 rounded-md font-semibold shadow-lg hover:shadow-xl transition duration-300 text-center">
                                Commencer gratuitement
                            </a>
                            <a href="{{ route('login') }}" class="bg-transparent border-2 border-white hover:bg-white hover:text-primary-700 text-white py-3 px-6 rounded-md font-semibold transition duration-300 text-center">
                                Se connecter
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <img src="{{ asset('images/dashboard-preview.png') }}" alt="Dashboard Preview" class="w-full max-w-md rounded-lg shadow-2xl" onerror="this.src='https://via.placeholder.com/600x400?text=Dashboard+Preview'">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white" id="fonctionnalites">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Fonctionnalités principales</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Notre application de gestion de dépenses vous offre tous les outils nécessaires pour maîtriser votre budget.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <div class="text-primary-500 mb-4">
                        <i class="fas fa-chart-pie text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Tableau de bord intuitif</h3>
                    <p class="text-gray-600">Visualisez vos finances en un coup d'œil avec des graphiques et statistiques personnalisés.</p>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <div class="text-primary-500 mb-4">
                        <i class="fas fa-tags text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Catégorisation intelligente</h3>
                    <p class="text-gray-600">Organisez vos dépenses et revenus par catégories pour mieux comprendre vos habitudes.</p>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <div class="text-primary-500 mb-4">
                        <i class="fas fa-chart-line text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Suivi des tendances</h3>
                    <p class="text-gray-600">Analysez l'évolution de vos finances au fil du temps et identifiez des opportunités d'économies.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-16 bg-gray-50" id="comment-utiliser">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Comment utiliser l'application</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Suivez ces étapes simples pour commencer à gérer efficacement vos finances.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-4 text-2xl font-bold">1</div>
                    <h3 class="text-xl font-semibold mb-2">Inscription</h3>
                    <p class="text-gray-600">Créez gratuitement votre compte en quelques secondes.</p>
                </div>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-4 text-2xl font-bold">2</div>
                    <h3 class="text-xl font-semibold mb-2">Configurer votre profil</h3>
                    <p class="text-gray-600">Personnalisez votre profil et vos catégories de dépenses.</p>
                </div>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-4 text-2xl font-bold">3</div>
                    <h3 class="text-xl font-semibold mb-2">Enregistrer transactions</h3>
                    <p class="text-gray-600">Ajoutez facilement vos dépenses et revenus quotidiens.</p>
                </div>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-4 text-2xl font-bold">4</div>
                    <h3 class="text-xl font-semibold mb-2">Analyser vos finances</h3>
                    <p class="text-gray-600">Consultez les rapports et prenez de meilleures décisions financières.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Vos données sont sécurisées</h2>
                    <p class="text-lg text-gray-600 mb-6">La sécurité et la confidentialité de vos informations financières sont notre priorité absolue.</p>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <i class="fas fa-shield-alt text-primary-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">Chiffrement de bout en bout de toutes vos données</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-lock text-primary-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">Authentification sécurisée et gestion des rôles</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-user-shield text-primary-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">Nous ne partageons jamais vos informations avec des tiers</span>
                        </li>
                    </ul>
                </div>
                <div class="md:w-1/2">
                    <img src="{{ asset('images/security.png') }}" alt="Sécurité des données" class="w-full max-w-md mx-auto rounded-lg shadow-lg" onerror="this.src='https://via.placeholder.com/600x400?text=Sécurité+des+données'">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-primary-500 to-primary-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-6">Prêt à prendre le contrôle de vos finances ?</h2>
            <p class="text-xl mb-8 max-w-3xl mx-auto opacity-90">Rejoignez des milliers d'utilisateurs qui gèrent efficacement leur budget avec notre application.</p>
            <div class="inline-block">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-white text-primary-700 hover:text-primary-800 py-3 px-8 rounded-md font-semibold shadow-lg hover:shadow-xl transition duration-300 text-center">
                        Accéder à mon Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-primary-700 hover:text-primary-800 py-3 px-8 rounded-md font-semibold shadow-lg hover:shadow-xl transition duration-300 text-center">
                        S'inscrire gratuitement
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Gestion Dépenses</h3>
                    <p class="text-gray-400">Votre outil complet pour la gestion de finances personnelles.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liens rapides</h3>
                    <ul class="space-y-2">
                        <li><a href="#fonctionnalites" class="text-gray-400 hover:text-white">Fonctionnalités</a></li>
                        <li><a href="#comment-utiliser" class="text-gray-400 hover:text-white">Comment utiliser</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white">Connexion</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white">Inscription</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Ressources</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Centre d'aide</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Légal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Confidentialité</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Conditions d'utilisation</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Gestion Dépenses. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>