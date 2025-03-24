# 🎨 Création des Interfaces avec Blade

[⬅️ Étape précédente : Contrôleurs et Routage](05-controllers-web.md)  
[➡️ Étape suivante : Tests des interfaces](07-tests-interfaces.md)  

---

## 📋 Table des matières

### PARTIE 1: LES FONDAMENTAUX DE BLADE
- [Introduction à Blade](#-introduction-à-blade)
- [Structure des templates Blade](#-structure-des-templates-blade)
- [Layouts et héritage](#-layouts-et-héritage)
- [Composants Blade](#-composants-blade)
- [Formulaires et validation](#-formulaires-et-validation)

### PARTIE 2: IMPLÉMENTATION DU PROJET
- [Organisation de notre application](#-organisation-de-notre-application)
- [Mise en place du thème avec Tailwind CSS](#-mise-en-place-du-thème-avec-tailwind-css)
- [Création des composants réutilisables](#-création-des-composants-réutilisables)
- [Création des layouts principaux](#-création-des-layouts-principaux)
- [Création des vues partielles](#-création-des-vues-partielles)
- [Dashboard avec graphiques](#-dashboard-avec-graphiques)
- [Pages de gestion des dépenses](#-pages-de-gestion-des-dépenses)
- [Pages de gestion des revenus](#-pages-de-gestion-des-revenus)
- [Pages de gestion des catégories](#-pages-de-gestion-des-catégories)
- [Pages de gestion des utilisateurs](#-pages-de-gestion-des-utilisateurs)
- [Page de profil utilisateur](#-page-de-profil-utilisateur)

### RESSOURCES
- [Commandes utiles pour les vues Blade](#-commandes-utiles-pour-les-vues-blade)
- [Ressources complémentaires](#-ressources-complémentaires)

---

# PARTIE 1: LES FONDAMENTAUX DE BLADE

## 📝 Introduction à Blade

Blade est le moteur de templates intégré à Laravel. Il offre une syntaxe élégante et puissante pour créer des vues dynamiques en combinant HTML et PHP.

### 🔹 **Avantages de Blade**

- **Syntaxe propre et concise** : Remplace le PHP brut par des directives intuitives
- **Héritage de templates** : Permet de créer des layouts réutilisables
- **Composants et slots** : Facilite la création d'éléments d'interface réutilisables
- **Compilation des vues** : Les vues Blade sont compilées en PHP et mises en cache
- **Sécurité intégrée** : Protection automatique contre les attaques XSS

### 🔹 **Concepts fondamentaux**

| Concept | Description | Exemple |
|---------|-------------|---------|
| **Directives** | Commandes précédées de `@` | `@if`, `@foreach`, `@include` |
| **Expressions** | Affichage de variables | `{{ $variable }}` |
| **Layouts** | Templates réutilisables | `@extends('layouts.app')` |
| **Sections** | Blocs de contenu nommés | `@section('title', 'Mon Titre')` |
| **Components** | Éléments réutilisables | `<x-alert type="error" />` |
| **Includes** | Insertion de sous-vues | `@include('shared.errors')` |

### 🔹 **Exemple simple de vue Blade**

Pour les tests, nous utiliserons la vue `welcome` et la route `home`. Pour commencer, allez dans le fichier `routes/web.php` et commentez le code suivant :  

```php
// Route d'accueil accessible à tous
Route::get('/', function () {
    return view('welcome');
})->name('home');
```

Pour commenter, il suffit d'utiliser `//`.  

Remplacez ensuite cette route par ceci :  

```php
Route::get('/', function () {
    $user = (object) ['name' => 'Gabriel NOMO']; // Simule un utilisateur
    $messages = collect([
        (object) ['content' => 'Bienvenue sur notre site !'],
        (object) ['content' => 'N’oubliez pas de vérifier vos notifications.']
    ]); // Simule une liste de messages

    return view('welcome', compact('user', 'messages'));
})->name('home');
```

Cette route est modifiée de sorte que la vue reçoive deux variables : une variable `messages` et une variable `user`. Vous pouvez remplacer le nom de l'utilisateur par votre prénom. Maintenant, nous pouvons commencer :  

Collez le code suivant dans le fichier `resources/views/welcome.blade.php` :
```  
```php
<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Mon Application' }}</title>
</head>
<body>
    <h1>Bienvenue, {{ $user->name }}</h1>
    
    @if($messages->count() > 0)
        <h2>Vous avez {{ $messages->count() }} messages</h2>
        <ul>
            @foreach($messages as $message)
                <li>{{ $message->content }}</li>
            @endforeach
        </ul>
    @else
        <p>Vous n'avez pas de nouveaux messages.</p>
    @endif
</body>
</html>
```
**lancez votre serveur : `php artisan serve` et observez le resultat dans votre navigateur sur l'url indiquée**.

---

## 🏗️ Structure des templates Blade

Les fichiers Blade sont stockés dans le répertoire `resources/views` et ont l'extension `.blade.php`. Laravel suit une convention d'organisation claire pour maintenir les vues bien structurées.

### 🔹 **Organisation recommandée des dossiers**

```
resources/views/
├── layouts/                  # Templates de base
│   ├── app.blade.php         # Layout principal
│   └── guest.blade.php       # Layout pour visiteurs non connectés
├── components/               # Composants réutilisables
│   ├── alert.blade.php       # Composant alerte
│   ├── button.blade.php      # Composant bouton
│   └── form/                 # Sous-dossier pour composants de formulaire
├── partials/                 # Éléments partiels
│   ├── header.blade.php      # En-tête de page
│   └── sidebar.blade.php     # Barre latérale
├── pages/                    # Pages principales de l'application
│   ├── dashboard.blade.php
│   └── profile.blade.php
└── [module]/                 # Dossiers par module fonctionnel
    ├── index.blade.php       # Liste des éléments
    ├── create.blade.php      # Formulaire de création
    ├── edit.blade.php        # Formulaire d'édition
    └── show.blade.php        # Vue détaillée
```

### 🔹 **Conventions de nommage**

- **Pluriel pour les collections** : `expenses/index.blade.php` pour lister toutes les dépenses
- **Singulier pour les éléments individuels** : `expenses/show.blade.php` pour afficher une dépense
- **Actions dans le nom** : `expenses/create.blade.php`, `expenses/edit.blade.php`
- **Cohérence avec les contrôleurs** : Les noms des vues correspondent souvent aux méthodes du contrôleur

### 🔹 **Bonnes pratiques**

- Organisez les vues par fonctionnalité ou par entité
- Utilisez des composants pour les éléments réutilisables
- Extrayez les parties répétitives dans des vues partielles
- Maintenez une hiérarchie de layouts cohérente
- Utilisez les namespaces pour organiser les vues dans les grands projets

---

## 📄 Layouts et héritage

L'un des avantages majeurs de Blade est son système d'héritage de templates, qui permet de définir un layout principal et de l'étendre dans les vues spécifiques.

### 🔹 **Création du header et du footer**  

Avant de créer le layout principal, nous devons d'abord créer un **header** et un **footer**, car ils seront inclus dans le layout. Sans ces fichiers, le code ne pourra pas compiler.  

#### 📌 **Création du fichier du header**  
Ajoutez le fichier suivant :  

```php
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

```

#### 📌 **Création du fichier du footer**  
Ajoutez également ce fichier :  

```php
<!-- resources/views/partials/footer.blade.php -->
<footer class="bg-gray-800 text-white text-center py-4 mt-10">
    <p class="text-sm">&copy; {{ date('Y') }} Mon Application. Tous droits réservés.</p>
</footer>

```

---

### 🔹 **Création d'un layout principal**  

Maintenant que nous avons un **header** et un **footer**, nous pouvons créer le **layout principal**. Ce fichier servira de base pour toutes nos vues.  

```php
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

```

⚠ **Note importante** : Ce layout inclut `partials.header` et `partials.footer`. Assurez-vous que ces fichiers existent avant de tester, sinon le code ne pourra pas compiler. et lancer également le serveur frontend : `npm run dev`.

---

### 🔹 **Extension d'un layout**  

Une fois le layout créé, nous pouvons l'utiliser dans d'autres vues. Voici un exemple d'extension du layout pour la page d'accueil :  

```php
<!-- resources/views/welcome.blade.php -->
@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    <h1>Tableau de bord</h1>
    <p>Bienvenue sur votre tableau de bord !</p>
@endsection

@push('scripts')
<script>
    console.log('Script spécifique au tableau de bord');
</script>
@endpush
```

Ainsi, la page `welcome.blade.php` hérite du layout `app.blade.php`, ce qui permet de centraliser la structure HTML et de ne modifier que le contenu de chaque page.

---

Avec cette structure, vous avez un **layout propre et réutilisable** ! 🎯🚀
```  


### 🔹 **Sections avec contenu par défaut**

```php
<!-- Dans le layout -->
@section('sidebar')
    <p>Contenu par défaut de la sidebar</p>
@show

<!-- Dans une vue enfant -->
@section('sidebar')
    @parent
    <p>Ajout au contenu par défaut</p>
@endsection
```

### 🔹 **Directives d'héritage principales**

| Directive | Description |
|-----------|-------------|
| `@extends('layout')` | Hérite d'un layout |
| `@section('name')` | Définit une section de contenu |
| `@yield('name')` | Affiche le contenu d'une section |
| `@show` | Termine une section et l'affiche immédiatement |
| `@parent` | Inclut le contenu de la section parente |
| `@push('name')` | Ajoute du contenu à une pile nommée |
| `@stack('name')` | Affiche le contenu d'une pile |

---

## 🧩 Composants Blade

Les composants Blade permettent de créer des éléments d'interface réutilisables. Ils sont particulièrement utiles pour éviter la duplication de code et maintenir la cohérence de l'interface.

### 🔹 **Types de composants**

1. **Composants anonymes** : Simples fichiers Blade sans classe PHP associée
2. **Composants basés sur des classes** : Combinaison d'une classe PHP et d'un template Blade

### 🔹 **Composant anonyme**

```php
<!-- resources/views/components/alert.blade.php -->
@php
    $colors = [
        'success' => 'bg-green-100 border-green-500 text-green-700',
        'danger' => 'bg-red-100 border-red-500 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700',
        'info' => 'bg-blue-100 border-blue-500 text-blue-700',
        'default' => 'bg-gray-100 border-gray-500 text-gray-700' 
    ];

    $alertClass = $colors[$type] ?? $colors['default'];
@endphp

<div 
    x-data="{ show: true }" 
    x-init="setTimeout(() => show = false, 5000)" 
    x-show="show" 
    x-transition.duration.500ms
    {{ $attributes->merge(['class' => "relative border-l-4 p-4 rounded-md $alertClass shadow-md"]) }}
    role="alert"
>
    <!-- Bouton de fermeture -->
    <button 
        @click="show = false" 
        class="absolute top-2 right-2 text-lg font-bold text-gray-700 hover:text-gray-900"
    >
        &times;
    </button>

    @if(isset($title))
        <h4 class="font-bold">{{ $title }}</h4>
    @endif
    
    <p>{{ $slot }}</p>
</div>

```

```php
<!-- Utilisation -->
<x-alert type="success" class="mb-4">
    <x-slot:title>Bravo !</x-slot:title>
    L'opération a été effectuée avec succès.
</x-alert>
```

### 🔹 **Composant basé sur une classe**

```bash
php artisan make:component Button
```

Cela génère deux fichiers :
- `app/View/Components/Button.php` (Classe PHP)
- `resources/views/components/button.blade.php` (Template)

```php
// app/View/Components/Button.php
namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public $type;
    public $color;
    
    public function __construct($type = 'button', $color = 'blue')
    {
        $this->type = $type;
        $this->color = $color;
    }
    
    public function render()
    {
        return view('components.button');
    }
    
    public function colorClasses()
    {
        return match($this->color) {
            'blue' => 'bg-blue-500 hover:bg-blue-700',
            'red' => 'bg-red-500 hover:bg-red-700',
            'green' => 'bg-green-500 hover:bg-green-700',
            default => 'bg-gray-500 hover:bg-gray-700',
        };
    }
}
```

```php
<!-- resources/views/components/button.blade.php -->
<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'font-bold py-2 px-4 rounded text-white ' . $colorClasses()]) }}
>
    {{ $slot }}
</button>
```

### 🔹 **Utilisation des composants**

```php
<x-button type="submit" color="green" class="mt-4">
    Enregistrer
</x-button>

<x-button color="red" onclick="history.back()">
    Annuler
</x-button>
```

### 🔹 **Passage de données aux composants**

- **Attributs** : Passés directement au composant
- **Slots** : Contenu à l'intérieur des balises du composant
- **Slots nommés** : Contenus multiples identifiés par un nom

```php
<x-card>
    <x-slot:header>Titre de la carte</x-slot:header>
    
    Contenu principal de la carte
    
    <x-slot:footer>
        <x-button>Action</x-button>
    </x-slot:footer>
</x-card>
```

---

## 📝 Formulaires et validation

Les formulaires sont un élément essentiel de toute application web. Blade offre plusieurs outils pour faciliter leur création et la gestion des erreurs de validation.

### 🔹 **Création d'un formulaire de base**

```php
<form action="{{ route('expenses.store') }}" method="POST">
    @csrf
    
    <div class="mb-4">
        <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
        <input type="text" name="description" id="description" value="{{ old('description') }}" 
               class="w-full px-3 py-2 border rounded @error('description') border-red-500 @enderror">
        
        @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <div class="mb-4">
        <label for="amount" class="block text-gray-700 font-bold mb-2">Montant</label>
        <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" 
               class="w-full px-3 py-2 border rounded @error('amount') border-red-500 @enderror">
        
        @error('amount')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">
        Enregistrer
    </button>
</form>
```

### 🔹 **Affichage des erreurs de validation**

```php
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong class="font-bold">Oups !</strong>
        <span>Veuillez corriger les erreurs suivantes :</span>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```
### 🔹 **Affichage des erreurs de validation avec x-alert**

```php
@if ($errors->any())
    <x-alert type="danger" title="Oups !">
        <p>Veuillez corriger les erreurs suivantes :</p>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif

```



### 🔹 **Utilisation de la fonction old()**

La fonction `old()` permet de récupérer les anciennes valeurs soumises en cas d'erreur de validation, évitant à l'utilisateur de tout ressaisir.

```php
<input type="text" name="title" value="{{ old('title', $expense->title ?? '') }}">
```

### 🔹 **Validation côté client avec attributs HTML5**

```php
<input type="text" name="description" required minlength="3" maxlength="255">
<input type="number" name="amount" required min="0" step="0.01">
```

### 🔹 **Création d'un composant de formulaire réutilisable**

```php
<!-- resources/views/components/form/input.blade.php -->
@props(['name', 'label', 'type' => 'text', 'value' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 font-bold mb-2">{{ $label }}</label>
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        value="{{ old($name, $value) }}" 
        {{ $attributes->merge(['class' => 'w-full px-3 py-2 border rounded ' . ($errors->has($name) ? 'border-red-500' : '')]) }}
    >
    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
```

### 🔹 **Utilisation de ce composant**

```php
<form action="{{ route('expenses.store') }}" method="POST">
    @csrf
    
    <x-form.input name="description" label="Description" required />
    <x-form.input name="amount" label="Montant" type="number" step="0.01" required />
    
    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">
        Enregistrer
    </button>
</form>
```

Nous avons appris les bases de Blade. Remettons à présent la route home à son état initial.
```php
    // Route d'accueil accessible à tous
Route::get('/', function () {
    return view('welcome');
})->name('home');
```
```php
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
```
# PARTIE 2: IMPLÉMENTATION DU PROJET

## 🗂 Organisation de notre application

Pour notre application de gestion de dépenses, nous allons structurer les vues de manière claire et organisée. Commençons par créer les dossiers nécessaires :

```bash
# Création des dossiers principaux
mkdir -p resources/views/layouts
mkdir -p resources/views/components/form
mkdir -p resources/views/partials
mkdir -p resources/views/dashboard
mkdir -p resources/views/expenses
mkdir -p resources/views/incomes
mkdir -p resources/views/categories
mkdir -p resources/views/users
mkdir -p resources/views/profile

# Création des fichiers de base
touch resources/views/layouts/app.blade.php
touch resources/views/layouts/guest.blade.php
touch resources/views/partials/header.blade.php
touch resources/views/partials/sidebar.blade.php
touch resources/views/partials/footer.blade.php
```

## 🎨 Mise en place du thème avec Tailwind CSS

Notre application utilisera Tailwind CSS pour le style. Laravel 11 inclut déjà Tailwind CSS par défaut, mais nous allons configurer quelques options supplémentaires.

### 🔹 **Configuration de Tailwind CSS**
Si vous ne voyez pas le fichier tailwind.config.js, cela signifie qu'il n'est pas installé. Cependant, Laravel 11 l'installe par défaut, donc vous devriez le trouver. S'il est absent, installez-le avec la commande suivante, sinon passez cette étape :
```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

Éditons le fichier `tailwind.config.js` pour ajouter nos couleurs personnalisées :

```javascript
// tailwind.config.js
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
          fontFamily: {
            sans: ['Figtree', ...defaultTheme.fontFamily.sans],
          },
          colors: {
            'primary': {
              50: '#f0f9ff',
              100: '#e0f2fe',
              200: '#bae6fd',
              300: '#7dd3fc',
              400: '#38bdf8',
              500: '#0ea5e9',
              600: '#0284c7',
              700: '#0369a1',
              800: '#075985',
              900: '#0c4a6e',
            },
          },
        },
      },

    plugins: [forms],
};

```

Configurons notre fichier CSS principal :

```css
/* resources/css/app.css */
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Styles personnalisés */
@layer components {
  .btn {
    @apply font-bold py-2 px-4 rounded;
  }
  .btn-primary {
    @apply bg-primary-500 text-white hover:bg-primary-700;
  }
  .btn-secondary {
    @apply bg-gray-500 text-white hover:bg-gray-700;
  }
  .btn-success {
    @apply bg-green-500 text-white hover:bg-green-700;
  }
  .btn-danger {
    @apply bg-red-500 text-white hover:bg-red-700;
  }
}
```

Installez les dépendances et démarrez le serveur de développement :

```bash
npm install
npm run dev
```

## 🧱 Création des composants réutilisables

Créons les composants de base qui seront utilisés à travers l'application.

### 🔹 **Composant Alert**

```php
<!-- resources/views/components/alert.blade.php -->
@props(['type' => 'info', 'title' => null])

@php
    $classes = match($type) {
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
        default => 'bg-blue-100 border-blue-400 text-blue-700',
    };
@endphp

<div 
    x-data="{ show: true }" 
    x-init="setTimeout(() => show = false, 5000)" 
    x-show="show" 
    x-transition.duration.500ms
    {{ $attributes->merge(['class' => "border px-4 py-3 rounded relative $classes shadow-md"]) }} 
    role="alert"
>
    <!-- Bouton de fermeture -->
    <button 
        @click="show = false" 
        class="absolute top-2 right-2 text-lg font-bold text-gray-700 hover:text-gray-900"
    >
        &times;
    </button>

    @if($title)
        <strong class="font-bold">{{ $title }}</strong>
        <span class="block sm:inline">{{ $slot }}</span>
    @else
        {{ $slot }}
    @endif
</div>

```

### 🔹 **Composant Button**

```php
<!-- resources/views/components/button.blade.php -->
@props(['type' => 'button', 'color' => 'primary'])

@php
    $baseClasses = 'font-bold py-2 px-4 rounded focus:outline-none transition';
    $colorClasses = match($color) {
        'primary' => 'bg-primary-500 hover:bg-primary-700 text-white',
        'success' => 'bg-green-500 hover:bg-green-700 text-white',
        'danger' => 'bg-red-500 hover:bg-red-700 text-white',
        'warning' => 'bg-yellow-500 hover:bg-yellow-700 text-white',
        'gray' => 'bg-gray-500 hover:bg-gray-700 text-white',
        default => 'bg-primary-500 hover:bg-primary-700 text-white',
    };
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "$baseClasses $colorClasses"]) }}
>
    {{ $slot }}
</button>
```

### 🔹 **Composant Input**

```php
<!-- resources/views/components/form/input.blade.php -->
@props(['name', 'label', 'type' => 'text', 'value' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 text-sm font-bold mb-2">{{ $label }}</label>
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        value="{{ old($name, $value) }}" 
        {{ $attributes->merge(['class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ' . ($errors->has($name) ? 'border-red-500' : '')]) }}
    >
    @error($name)
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>
```

### 🔹 **Composant Select**

```php
<!-- resources/views/components/form/select.blade.php -->
@props(['name', 'label', 'options' => [], 'value' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 text-sm font-bold mb-2">{{ $label }}</label>
    <select 
        name="{{ $name }}" 
        id="{{ $name }}" 
        {{ $attributes->merge(['class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ' . ($errors->has($name) ? 'border-red-500' : '')]) }}
    >
        <option value="">Sélectionnez une option</option>
        
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    
    @error($name)
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>
```

### 🔹 **Composant Card**

```php
<!-- resources/views/components/card.blade.php -->
@props(['header' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow overflow-hidden']) }}>
    @if($header)
        <div class="px-6 py-4 border-b bg-gray-50">
            {{ $header }}
        </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $footer }}
        </div>
    @endif
</div>
```

## 📄 Création des layouts principaux

Créons les layouts principaux de notre application.

### 🔹 **Layout principal (app.blade.php)**

```php
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
```

### 🔹 **Layout pour les invités (guest.blade.php)**

```php
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
```

## 🧪 Création des vues partielles

Créons les éléments partiels qui seront inclus dans notre layout principal.

### 🔹 **En-tête (header.blade.php)**

```php
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
```

### 🔹 **Barre latérale (sidebar.blade.php)**

```php
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
```

### 🔹 **Pied de page (footer.blade.php)**

```php
<!-- resources/views/partials/footer.blade.php -->
<footer class="bg-white py-4 border-t">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div class="text-sm text-gray-500">
                &copy; {{ date('Y') }} Gestion Dépenses. Tous droits réservés.
            </div>
            <div class="flex space-x-4">
                <a href="#" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-shield-alt mr-1"></i> Politique de confidentialité
                </a>
                <a href="#" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-file-contract mr-1"></i> Conditions d'utilisation
                </a>
            </div>
        </div>
    </div>
</footer>
```

## 📊 Dashboard avec graphiques

Créons la page de tableau de bord qui montrera des statistiques et des graphiques.

```php
<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('header', 'Tableau de bord')

@section('content')
    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total des revenus</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalIncomes'], 2, ',', ' ') }} €</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total des dépenses</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalExpenses'], 2, ',', ' ') }} €</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Balance</p>
                    <p class="text-2xl font-bold {{ $stats['balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($stats['balance'], 2, ',', ' ') }} €
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-tag text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Catégories</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['categoryCount'] }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Dépenses par catégorie</h3>
            <div class="h-80">
                <canvas id="expensesByCategoryChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Balance mensuelle</h3>
            <div class="h-80">
                <canvas id="monthlyBalanceChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Dernières transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-bold">Dernières dépenses</h3>
                <a href="{{ route('expenses.index') }}" class="text-primary-600 hover:text-primary-800 text-sm">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="p-6">
                @if($latestExpenses->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($latestExpenses as $expense)
                            <div class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $expense->description }}</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <span class="mr-2">{{ $expense->date->format('d/m/Y') }}</span>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $expense->category->name }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-red-600 font-bold">{{ number_format($expense->amount, 2, ',', ' ') }} €</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucune dépense enregistrée</p>
                @endif
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-bold">Derniers revenus</h3>
                <a href="{{ route('incomes.index') }}" class="text-primary-600 hover:text-primary-800 text-sm">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="p-6">
                @if($latestIncomes->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($latestIncomes as $income)
                            <div class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $income->description }}</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <span class="mr-2">{{ $income->date->format('d/m/Y') }}</span>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $income->category->name }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-green-600 font-bold">{{ number_format($income->amount, 2, ',', ' ') }} €</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucun revenu enregistré</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Données pour le graphique des dépenses par catégorie
    const expensesByCategoryData = {
        labels: {!! json_encode($expensesByCategory->pluck('name')) !!},
        datasets: [{
            label: 'Dépenses',
            data: {!! json_encode($expensesByCategory->pluck('total')) !!},
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(255, 159, 64, 0.7)',
                'rgba(199, 199, 199, 0.7)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(199, 199, 199, 1)',
            ],
            borderWidth: 1
        }]
    };

    // Configuration du graphique des dépenses par catégorie
    const expensesByCategoryConfig = {
        type: 'doughnut',
        data: expensesByCategoryData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        },
    };

    // Création du graphique des dépenses par catégorie
    new Chart(
        document.getElementById('expensesByCategoryChart'),
        expensesByCategoryConfig
    );

    // Données pour le graphique de la balance mensuelle
    // (Ceci est un exemple, vous devriez avoir des données réelles)
    const monthlyBalanceData = {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
        datasets: [
            {
                label: 'Revenus',
                data: [4500, 4200, 4800, 5000, 4600, 5200],
                backgroundColor: 'rgba(46, 204, 113, 0.2)',
                borderColor: 'rgba(46, 204, 113, 1)',
                borderWidth: 2,
                yAxisID: 'y',
            },
            {
                label: 'Dépenses',
                data: [3800, 4100, 3900, 4200, 4300, 4000],
                backgroundColor: 'rgba(231, 76, 60, 0.2)',
                borderColor: 'rgba(231, 76, 60, 1)',
                borderWidth: 2,
                yAxisID: 'y',
            },
            {
                label: 'Balance',
                data: [700, 100, 900, 800, 300, 1200],
                backgroundColor: 'rgba(52, 152, 219, 0.2)',
                borderColor: 'rgba(52, 152, 219, 1)',
                borderWidth: 2,
                type: 'line',
                yAxisID: 'y1',
            }
        ]
    };

    // Configuration du graphique de la balance mensuelle
    const monthlyBalanceConfig = {
        type: 'bar',
        data: monthlyBalanceData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Montant (€)'
                    }
                },
                y1: {
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                    title: {
                        display: true,
                        text: 'Balance (€)'
                    }
                },
            }
        },
    };

    // Création du graphique de la balance mensuelle
    new Chart(
        document.getElementById('monthlyBalanceChart'),
        monthlyBalanceConfig
    );
</script>
@endpush
```

## 📊 Pages de gestion des dépenses

Créons les vues nécessaires pour gérer les dépenses.

### 🔹 **Liste des dépenses (index.blade.php)**

```php
<!-- resources/views/expenses/index.blade.php -->
@extends('layouts.app')

@section('title', 'Liste des dépenses')

@section('header', 'Gestion des dépenses')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
        <div>
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Nouvelle dépense
            </a>
        </div>
        
        <div>
            <form action="{{ route('expenses.index') }}" method="GET" class="flex flex-wrap gap-2">
                <select name="category_id" class="rounded border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                
                <input type="date" name="date_start" value="{{ request('date_start') }}" class="rounded border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Date début">
                
                <input type="date" name="date_end" value="{{ request('date_end') }}" class="rounded border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Date fin">
                
                <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-filter"></i>
                </button>
            </form>
        </div>
    </div>
    
    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($expenses as $expense)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $expense->date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $expense->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $expense->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <span class="text-red-600 font-bold">
                                {{ number_format($expense->amount, 2, ',', ' ') }} €
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('expenses.show', $expense) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('expenses.edit', $expense) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette dépense ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Aucune dépense trouvée
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $expenses->links() }}
    </div>
@endsection
```

### 🔹 **Création d'une dépense (create.blade.php)**

```php
<!-- resources/views/expenses/create.blade.php -->
@extends('layouts.app')

@section('title', 'Nouvelle dépense')

@section('header', 'Ajouter une dépense')

@section('content')
    <form action="{{ route('expenses.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form.input name="description" label="Description" required />
            
            <x-form.input name="amount" label="Montant" type="number" step="0.01" required />
            
            <x-form.select name="category_id" label="Catégorie" :options="$categories->pluck('name', 'id')->toArray()" required />
            
            <x-form.input name="date" label="Date" type="date" value="{{ date('Y-m-d') }}" required />
        </div>
        
        <div class="mt-6 flex justify-between">
            <x-button type="submit" color="success">
                <i class="fas fa-save mr-1"></i> Enregistrer
            </x-button>
            
            <x-button type="button" color="gray" onclick="window.location='{{ route('expenses.index') }}'">
                <i class="fas fa-times mr-1"></i> Annuler
            </x-button>
        </div>
    </form>
@endsection
```

### 🔹 **Modification d'une dépense (edit.blade.php)**

```php
<!-- resources/views/expenses/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Modifier la dépense')

@section('header', 'Modifier la dépense')

@section('content')
    <form action="{{ route('expenses.update', $expense) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form.input name="description" label="Description" :value="$expense->description" required />
            
            <x-form.input name="amount" label="Montant" type="number" step="0.01" :value="$expense->amount" required />
            
            <x-form.select name="category_id" label="Catégorie" :options="$categories->pluck('name', 'id')->toArray()" :value="$expense->category_id" required />
            
            <x-form.input name="date" label="Date" type="date" :value="$expense->date->format('Y-m-d')" required />
        </div>
        
        <div class="mt-6 flex justify-between">
            <x-button type="submit" color="success">
                <i class="fas fa-save mr-1"></i> Mettre à jour
            </x-button>
            
            <x-button type="button" color="gray" onclick="window.location='{{ route('expenses.index') }}'">
                <i class="fas fa-times mr-1"></i> Annuler
            </x-button>
        </div>
    </form>
@endsection
```

### 🔹 **Détails d'une dépense (show.blade.php)**

```php
<!-- resources/views/expenses/show.blade.php -->
@extends('layouts.app')

@section('title', 'Détails de la dépense')

@section('header', 'Détails de la dépense')

@section('content')
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="border-b px-6 py-4">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">{{ $expense->description }}</h2>
                <span class="text-red-600 font-bold text-xl">{{ number_format($expense->amount, 2, ',', ' ') }} €</span>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Date</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $expense->date->format('d/m/Y') }}</p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Catégorie</h3>
                    <p class="mt-1">
                        <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $expense->category->name }}
                        </span>
                    </p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Utilisateur</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $expense->user->name }}</p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Créé le</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $expense->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-4">
            <div class="flex justify-between">
                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-primary">
                    <i class="fas fa-edit mr-1"></i> Modifier
                </a>
                
                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette dépense ?')">
                        <i class="fas fa-trash mr-1"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('expenses.index') }}" class="text-primary-600 hover:text-primary-800">
            <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
        </a>
    </div>
@endsection
```

## 💰 Pages de gestion des revenus

Les pages pour les revenus sont similaires à celles des dépenses avec quelques adaptations.

### 🔹 **Liste des revenus (index.blade.php)**

```php
<!-- resources/views/incomes/index.blade.php -->
@extends('layouts.app')

@section('title', 'Liste des revenus')

@section('header', 'Gestion des revenus')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
        <div>
            <a href="{{ route('incomes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Nouveau revenu
            </a>
        </div>
        
        <div>
            <form action="{{ route('incomes.index') }}" method="GET" class="flex flex-wrap gap-2">
                <select name="category_id" class="rounded border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                
                <input type="date" name="date_start" value="{{ request('date_start') }}" class="rounded border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Date début">
                
                <input type="date" name="date_end" value="{{ request('date_end') }}" class="rounded border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Date fin">
                
                <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-filter"></i>
                </button>
            </form>
        </div>
    </div>
    
    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($incomes as $income)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $income->date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $income->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $income->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <span class="text-green-600 font-bold">
                                {{ number_format($income->amount, 2, ',', ' ') }} €
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('incomes.show', $income) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('incomes.edit', $income) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('incomes.destroy', $income) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce revenu ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Aucun revenu trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $incomes->links() }}
    </div>
@endsection
```

Les vues `create.blade.php`, `edit.blade.php` et `show.blade.php` pour les revenus sont similaires à celles des dépenses, avec des adaptations pour les libellés et les couleurs (vert au lieu de rouge pour les montants).

## 🏷️ Pages de gestion des catégories

Créons les vues pour gérer les catégories.

### 🔹 **Liste des catégories (index.blade.php)**

```php
<!-- resources/views/categories/index.blade.php -->
@extends('layouts.app')

@section('title', 'Liste des catégories')

@section('header', 'Gestion des catégories')

@section('content')
    <div class="mb-6">
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Nouvelle catégorie
        </a>
    </div>
    
    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dépenses</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenus</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                            {{ $category->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                {{ $category->expenses_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $category->incomes_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('categories.show', $category) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('categories.edit', $category) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Aucune catégorie trouvée
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
```

### 🔹 **Création d'une catégorie (create.blade.php)**

```php
<!-- resources/views/categories/create.blade.php -->
@extends('layouts.app')

@section('title', 'Nouvelle catégorie')

@section('header', 'Ajouter une catégorie')

@section('content')
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        
        <x-form.input name="name" label="Nom de la catégorie" required />
        
        <div class="mt-6 flex justify-between">
            <x-button type="submit" color="success">
                <i class="fas fa-save mr-1"></i> Enregistrer
            </x-button>
            
            <x-button type="button" color="gray" onclick="window.location='{{ route('categories.index') }}'">
                <i class="fas fa-times mr-1"></i> Annuler
            </x-button>
        </div>
    </form>
@endsection
```

### 🔹 **Modification d'une catégorie (edit.blade.php)**

```php
<!-- resources/views/categories/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Modifier la catégorie')

@section('header', 'Modifier la catégorie')

@section('content')
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        
        <x-form.input name="name" label="Nom de la catégorie" :value="$category->name" required />
        
        <div class="mt-6 flex justify-between">
            <x-button type="submit" color="success">
                <i class="fas fa-save mr-1"></i> Mettre à jour
            </x-button>
            
            <x-button type="button" color="gray" onclick="window.location='{{ route('categories.index') }}'">
                <i class="fas fa-times mr-1"></i> Annuler
            </x-button>
        </div>
    </form>
@endsection
```

### 🔹 **Détails d'une catégorie (show.blade.php)**

```php
<!-- resources/views/categories/show.blade.php -->
@extends('layouts.app')

@section('title', 'Détails de la catégorie')

@section('header', 'Détails de la catégorie : ' . $category->name)

@section('content')
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b">
            <h2 class="text-xl font-bold">Informations</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Nom</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $category->name }}</p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Créée le</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $category->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Nombre de dépenses</h3>
                    <p class="mt-1">
                        <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            {{ $category->expenses->count() }}
                        </span>
                    </p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Nombre de revenus</h3>
                    <p class="mt-1">
                        <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $category->incomes->count() }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-bold">Dernières dépenses</h2>
            </div>
            <div class="p-6">
                @if($expenses->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($expenses as $expense)
                            <div class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $expense->description }}</p>
                                    <p class="text-sm text-gray-500">{{ $expense->date->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-red-600 font-bold">{{ number_format($expense->amount, 2, ',', ' ') }} €</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucune dépense pour cette catégorie</p>
                @endif
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-bold">Derniers revenus</h2>
            </div>
            <div class="p-6">
                @if($incomes->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($incomes as $income)
                            <div class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $income->description }}</p>
                                    <p class="text-sm text-gray-500">{{ $income->date->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-green-600 font-bold">{{ number_format($income->amount, 2, ',', ' ') }} €</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucun revenu pour cette catégorie</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="mt-6 flex justify-between">
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
            <i class="fas fa-edit mr-1"></i> Modifier
        </a>
        
        <form action="{{ route('categories.destroy', $category) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                <i class="fas fa-trash mr-1"></i> Supprimer
            </button>
        </form>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('categories.index') }}" class="text-primary-600 hover:text-primary-800">
            <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
        </a>
    </div>
@endsection
```

## 👥 Pages de gestion des utilisateurs

Créons les vues pour la gestion des utilisateurs, accessibles uniquement aux administrateurs.

### 🔹 **Liste des utilisateurs (index.blade.php)**

```php
<!-- resources/views/users/index.blade.php -->
@extends('layouts.app')

@section('title', 'Liste des utilisateurs')

@section('header', 'Gestion des utilisateurs')

@section('content')
    <div class="mb-6">
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus mr-1"></i> Nouvel utilisateur
        </a>
    </div>
    
    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->profile_image_url }}" alt="{{ $user->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">Inscrit le {{ $user->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @foreach($user->roles as $role)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $role->name === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Actif' : 'Bloqué' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', $user) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form action="{{ route('users.toggleActive', $user) }}" method="POST" class="inline mr-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="{{ $user->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}" title="{{ $user->is_active ? 'Bloquer' : 'Débloquer' }}" onclick="return confirm('Êtes-vous sûr de vouloir {{ $user->is_active ? 'bloquer' : 'débloquer' }} cet utilisateur ?')">
                                    <i class="fas {{ $user->is_active ? 'fa-lock' : 'fa-unlock' }}"></i>
                                </button>
                            </form>
                            
                            @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Aucun utilisateur trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
```

Les autres vues pour les utilisateurs (`create.blade.php`, `edit.blade.php` et `show.blade.php`) suivent une structure similaire aux précédentes, adaptées aux champs spécifiques des utilisateurs.

## 👤 Page de profil utilisateur

Créons la page de profil utilisateur qui permet à chaque utilisateur de gérer ses informations.

```php
<!-- resources/views/profile/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Mon profil')

@section('header', 'Mon profil')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-center">
                    <div class="relative mx-auto w-32 h-32 mb-4">
                        <img class="rounded-full w-full h-full object-cover" src="{{ auth()->user()->profile_image_url }}" alt="{{ auth()->user()->name }}">
                        <button type="button" onclick="document.getElementById('profile_image_upload').click()" class="absolute bottom-0 right-0 bg-primary-500 text-white rounded-full p-2 hover:bg-primary-600">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    
                    <form action="{{ route('profile.updateImage') }}" method="POST" enctype="multipart/form-data" id="profile_image_form">
                        @csrf
                        @method('PATCH')
                        <input type="file" name="profile_image" id="profile_image_upload" class="hidden" onchange="document.getElementById('profile_image_form').submit();">
                    </form>
                    
                    <h3 class="text-lg font-medium text-gray-900">{{ auth()->user()->name }}</h3>
                    <p class="text-gray-500">{{ auth()->user()->email }}</p>
                </div>
                
                <div class="mt-6 border-t pt-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-500">Rôle</span>
                        <span class="text-sm font-medium text-gray-900">
                            @foreach(auth()->user()->roles as $role)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $role->name === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-500">Inscrit le</span>
                        <span class="text-sm font-medium text-gray-900">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Statut</span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ auth()->user()->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ auth()->user()->is_active ? 'Actif' : 'Bloqué' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="md:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-xl font-bold">Informations personnelles</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 gap-6">
        
                            <x-form.input name="name" label="Nom complet" :value="auth()->user()->name" />
                            
                            <x-form.input name="email" label="Adresse email" type="email" :value="auth()->user()->email" />
                            
                            <div>
                                <x-button type="submit" color="success">
                                    <i class="fas fa-save mr-1"></i> Mettre à jour
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-xl font-bold">Modification du mot de passe</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 gap-6">
                            <x-form.input name="current_password" label="Mot de passe actuel" type="password" />
                            
                            <x-form.input name="password" label="Nouveau mot de passe" type="password" />
                            
                            <x-form.input name="password_confirmation" label="Confirmer le nouveau mot de passe" type="password" />
                            
                            <div>
                                <x-button type="submit" color="primary">
                                    <i class="fas fa-key mr-1"></i> Changer le mot de passe
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-xl font-bold text-red-600">Suppression du compte</h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 mb-4">
                        Une fois votre compte supprimé, toutes vos ressources et données seront définitivement effacées. Avant de supprimer votre compte, veuillez télécharger toutes les données ou informations que vous souhaitez conserver.
                    </p>
                    
                    <button type="button" onclick="document.getElementById('delete-account-modal').classList.remove('hidden')" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i> Supprimer mon compte
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal pour la suppression du compte -->
    <div id="delete-account-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-xl font-bold text-red-600 mb-4">Êtes-vous sûr ?</h3>
            
            <p class="text-gray-700 mb-4">
                Cette action est irréversible. Veuillez confirmer votre mot de passe pour supprimer définitivement votre compte.
            </p>
            
            <form action="{{ route('profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                
                <x-form.input name="password" label="Mot de passe" type="password" />
                
                <div class="mt-6 flex justify-between">
                    <x-button type="submit" color="danger">
                        <i class="fas fa-trash mr-1"></i> Supprimer définitivement
                    </x-button>
                    
                    <x-button type="button" color="gray" onclick="document.getElementById('delete-account-modal').classList.add('hidden')">
                        <i class="fas fa-times mr-1"></i> Annuler
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
```

## 📜 Commandes utiles pour les vues Blade

| Commande | Description |
|----------|------------|
| `php artisan make:component Alert` | Crée un composant Blade (classe et vue) |
| `php artisan make:component Form/Input --view` | Crée un composant Blade vue uniquement dans un sous-dossier |
| `php artisan view:clear` | Efface le cache des vues compilées |
| `php artisan view:cache` | Met en cache les vues Blade compilées |
| `php artisan storage:link` | Crée un lien symbolique de storage vers public |
| `php artisan vendor:publish --tag=laravel-pagination` | Publie les vues de pagination pour personnalisation |

## 📚 Ressources complémentaires

- [Documentation officielle de Blade](https://laravel.com/docs/11.x/blade)
- [Tailwind CSS](https://tailwindcss.com/docs) - Pour la mise en page et le style
- [Alpine.js](https://alpinejs.dev/start-here) - Pour les interactions JavaScript simples
- [Chart.js](https://www.chartjs.org/docs/latest/) - Pour les graphiques

---

## 📌 Code source de cette étape

Le code source correspondant à cette étape est disponible sur la branche `step-5`.

---

## 📌 Prochaine étape

Dans la prochaine étape, nous allons tester nos interfaces pour nous assurer que tout fonctionne correctement. **[➡️ Étape suivante : Tests des interfaces](07-tests-interfaces.md)**.