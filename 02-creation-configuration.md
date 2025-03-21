# 🚀 Création et configuration du projet Laravel 11

[⬅️ Étape précédente : Prérequis et installation](01-requirements.md)  
[➡️ Étape suivante : Création des modèles et migrations](03-modeles-migrations.md)

---

## 📌 Table des matières
- [Introduction à la création d'un projet Laravel](#-introduction-à-la-création-dun-projet-laravel)
- [Méthodes de création d'un projet Laravel](#-méthodes-de-création-dun-projet-laravel)
- [Création du projet](#-création-du-projet-gestion-depenses)
- [Exploration de la structure du projet](#-exploration-de-la-structure-du-projet)
- [Configuration de l'environnement](#-configuration-de-lenvironnement)
- [Configuration de la base de données](#-configuration-de-la-base-de-données)
- [Configuration supplémentaire](#-configuration-supplémentaire)
- [Lancement du serveur de développement](#-lancement-du-serveur-de-développement)
- [Bonnes pratiques pour démarrer un projet](#-bonnes-pratiques-pour-démarrer-un-projet)
- [Dépannage des problèmes courants](#-dépannage-des-problèmes-courants)

---

## 🌟 Introduction à la création d'un projet Laravel

Laravel est un framework qui suit la philosophie "Convention over Configuration", ce qui signifie qu'il favorise les conventions plutôt que la configuration explicite. Cela se traduit par une structure de projet standardisée qui facilite la maintenance et la collaboration.

Créer un nouveau projet Laravel signifie générer cette structure initiale avec tous les fichiers nécessaires :
- Le système de routage
- La configuration de base
- L'architecture MVC (Modèle-Vue-Contrôleur)
- Les outils de développement essentiels

Le projet que nous allons créer, **Gestion-Dépenses**, deviendra une application complète de suivi des finances personnelles, en utilisant pleinement les fonctionnalités de Laravel 11.

---

## 🛠️ Méthodes de création d'un projet Laravel

Il existe plusieurs façons de créer un nouveau projet Laravel. Voici les trois méthodes principales :

### 1. Via Composer create-project

C'est la méthode traditionnelle qui utilise Composer pour télécharger le squelette d'application Laravel :

```bash
composer create-project --prefer-dist laravel/laravel nom-du-projet
```

**Avantages :**
- Fonctionne sur tous les systèmes
- Ne nécessite que Composer
- Permet de spécifier une version exacte

**Inconvénients :**
- Plus lent car télécharge toutes les dépendances à chaque fois

### 2. Via l'installateur Laravel

L'installateur Laravel est un outil global qui simplifie la création de projets :

```bash
laravel new nom-du-projet
```

**Avantages :**
- Plus rapide que Composer
- Commande plus simple
- Peut offrir des options interactives

**Inconvénients :**
- Nécessite l'installation préalable de l'installateur Laravel
- Moins flexible pour les versions spécifiques

### 3. Via Laravel Sail (Docker)

Pour ceux qui préfèrent Docker, Laravel Sail offre un environnement conteneurisé :

```bash
curl -s "https://laravel.build/nom-du-projet" | bash
```

**Avantages :**
- Environnement isolé et reproductible
- Inclut MySQL, Redis, etc.
- Ne nécessite pas PHP installé localement

**Inconvénients :**
- Nécessite Docker
- Courbe d'apprentissage supplémentaire
- Plus lourd en ressources

Pour notre projet, nous utiliserons la méthode Composer qui est la plus universelle.

---

## 📦 Création du projet Gestion-Dépenses

Ouvrez votre terminal et exécutez la commande suivante pour créer un nouveau projet Laravel nommé "gestion-depenses" :

```bash
composer create-project --prefer-dist laravel/laravel gestion-depenses
```

Cette commande effectue plusieurs actions importantes :
1. **Télécharge** le squelette d'application Laravel
2. **Installe** toutes les dépendances requises via Composer
3. **Génère** une clé d'application aléatoire (APP_KEY)
4. **Crée** la structure complète du projet
5. **Configure** les autorisations de base des dossiers

> 💡 **Note :** L'option `--prefer-dist` indique à Composer de préférer les versions distribuées (optimisées) des packages plutôt que les sources complètes, ce qui accélère l'installation.

Une fois la création terminée, accédez au dossier du projet :

```bash
cd gestion-depenses
```

---

## 📂 Exploration de la structure du projet

Laravel utilise une structure de dossiers organisée qui suit le modèle MVC (Modèle-Vue-Contrôleur). Voici un aperçu des principaux dossiers et fichiers que vous trouverez dans votre nouveau projet :

### Dossiers principaux

| Dossier | Description | Utilisation dans notre projet |
|---------|-------------|-------------------------------|
| **app/** | Contient la logique métier de l'application | Nous y placerons nos modèles, contrôleurs et services |
| **app/Http/Controllers/** | Contient les contrôleurs | Pour gérer les requêtes utilisateurs (dépenses, revenus, etc.) |
| **app/Models/** | Contient les modèles Eloquent | Pour représenter nos entités (User, Expense, Income, Category) |
| **bootstrap/** | Contient le fichier d'amorçage de l'application | Généralement pas modifié directement |
| **config/** | Contient tous les fichiers de configuration | Nous y configurerons la base de données, le cache, etc. |
| **database/** | Contient les migrations, seeders et factories | Pour définir et peupler notre base de données |
| **public/** | Dossier racine du serveur web | Contient le fichier index.php et les assets publics |
| **resources/** | Contient les assets non compilés (views, JS, CSS) | Pour nos templates Blade et assets frontend |
| **routes/** | Contient les définitions de routes | Pour définir les URLs de notre application |
| **storage/** | Contient les fichiers générés par l'application | Pour les logs, caches, et fichiers uploadés |
| **tests/** | Contient les tests automatisés | Pour tester notre application |
| **vendor/** | Contient les dépendances installées par Composer | Géré automatiquement par Composer |

### Fichiers clés

| Fichier | Description | Importance |
|---------|-------------|------------|
| **.env** | Contient les variables d'environnement | Crucial pour la configuration spécifique à l'environnement |
| **artisan** | Interface en ligne de commande de Laravel | Outil principal pour les tâches de développement |
| **composer.json** | Définit les dépendances du projet | Gère les packages et scripts d'installation |
| **package.json** | Définit les dépendances frontend | Pour les outils frontend comme Vite |
| **routes/web.php** | Définit les routes web | Où nous définirons les URLs de notre application |
| **routes/api.php** | Définit les routes API | Pour notre API REST |

Cette structure de dossiers favorise la séparation des préoccupations et facilite la maintenance et l'évolutivité du projet.

---

## ⚙️ Configuration de l'environnement

Laravel utilise un fichier `.env` pour stocker les variables d'environnement. Ce fichier contient des informations sensibles comme les identifiants de base de données et ne doit jamais être commité dans votre dépôt Git.

### 1. Configurer le fichier .env

Le fichier `.env.example` est fourni comme modèle. Commencez par en créer une copie :

```bash
cp .env.example .env
```

Ce fichier `.env` contient de nombreuses variables importantes :

| Variable | Description | Valeur typique |
|----------|-------------|----------------|
| **APP_NAME** | Nom de l'application | "Gestion Dépenses" |
| **APP_ENV** | Environnement actuel | "local" en développement |
| **APP_KEY** | Clé de chiffrement unique | Générée automatiquement |
| **APP_DEBUG** | Mode débogage activé | "true" en développement |
| **APP_URL** | URL de base de l'application | "http://localhost:8000" |
| **DB_CONNECTION** | Type de base de données | "mysql" ou "sqlite" |
| **DB_HOST** | Hôte de la base de données | "127.0.0.1" |
| **DB_PORT** | Port de la base de données | "3306" pour MySQL |
| **DB_DATABASE** | Nom de la base de données | "gestion_depenses" |
| **DB_USERNAME** | Utilisateur de la base de données | "root" |
| **DB_PASSWORD** | Mot de passe | Vide ou votre mot de passe |

Modifiez le fichier `.env` pour personnaliser votre application :

```dotenv
APP_NAME="Gestion Dépenses"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

### 2. Générer la clé d'application

La clé d'application est utilisée pour sécuriser les sessions, les cookies et les données chiffrées. Si elle n'a pas été générée automatiquement lors de la création du projet, générez-la avec :

```bash
php artisan key:generate
```

Cette commande va :
1. Générer une chaîne aléatoire de 32 caractères
2. L'insérer dans votre fichier `.env` comme valeur pour `APP_KEY`
3. Afficher un message de confirmation

> ⚠️ **Important :** Sans cette clé, les sessions utilisateur et autres données chiffrées ne fonctionneront pas correctement !

---

## 🗃️ Configuration de la base de données

Notre application de gestion de dépenses nécessite une base de données pour stocker les utilisateurs, catégories, dépenses et revenus. Laravel supporte plusieurs SGBD (MySQL, PostgreSQL, SQLite, SQL Server).

### Option 1 : Utilisation de MySQL

MySQL est un choix robuste pour les applications en production.

#### 1. Créer la base de données

Avant de configurer Laravel, créez la base de données dans MySQL :

```bash
# Se connecter à MySQL (remplacez 'root' par votre utilisateur si nécessaire)
mysql -u root -p

# Dans le prompt MySQL, créer la base de données
CREATE DATABASE gestion_depenses;
EXIT;
```

#### 2. Configurer le fichier .env pour MySQL

Modifiez les paramètres de base de données dans le fichier `.env` :

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_depenses
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

> 💡 **Note :** Remplacez `votre_mot_de_passe` par le mot de passe de votre utilisateur MySQL. Si vous utilisez XAMPP avec l'utilisateur root par défaut, vous pouvez laisser le mot de passe vide.

### Option 2 : Utilisation de SQLite

SQLite est idéal pour le développement et les petites applications car il ne nécessite pas de serveur séparé.

#### 1. Créer le fichier SQLite

```bash
touch database/database.sqlite
```

#### 2. Configurer le fichier .env pour SQLite

Modifiez les paramètres de base de données dans le fichier `.env` :

```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=/chemin/absolu/vers/gestion-depenses/database/database.sqlite
```

Pour obtenir le chemin absolu, vous pouvez utiliser :
- Sur Linux/macOS : `pwd`
- Sur Windows : `cd` pour afficher le répertoire courant

> 💡 **Avantage de SQLite :** Pas besoin d'installer ou de configurer un serveur de base de données séparé, ce qui simplifie grandement le développement et les tests.

### Tester la connexion à la base de données

Pour vérifier que votre configuration fonctionne correctement :

```bash
php artisan db:show
```

Cette commande affichera les informations sur votre connexion à la base de données.

---

## 🔧 Configuration supplémentaire

### Configuration du fuseau horaire

Par défaut, Laravel utilise UTC. Pour adapter l'application à votre région, modifiez le fichier `config/app.php` :

```php
// Pour la France, utilisez 'Europe/Paris'
'timezone' => 'Europe/Paris',
```

### Configuration de la locale

Pour adapter les formats de date, de nombre et autres à votre région :

```php
// Pour le français
'locale' => 'fr',
'fallback_locale' => 'en',
```

### Configuration du mail (pour les tests)

Pour le développement, vous pouvez configurer Laravel pour utiliser Mailtrap ou le pilote "log" qui écrit simplement les emails dans les logs :

```dotenv
MAIL_MAILER=log
```

Cela permet de tester les fonctionnalités d'email sans envoyer de vrais messages.

---

## 🖥️ Lancement du serveur de développement

Laravel inclut un serveur de développement intégré que vous pouvez démarrer avec Artisan :

```bash
php artisan serve
```

Par défaut, ce serveur démarre sur http://localhost:8000.

> 💡 **Options utiles :**
> - Changer le port : `php artisan serve --port=8080`
> - Rendre accessible sur le réseau : `php artisan serve --host=0.0.0.0`
> - Ouvrir automatiquement dans le navigateur : `php artisan serve --open`

Une fois le serveur démarré, vous devriez voir un message similaire à :

```
Starting Laravel development server: http://127.0.0.1:8000
[Thu Jul 21 21:00:30 2023] PHP 8.2.0 Development Server started at http://127.0.0.1:8000
```

Ouvrez votre navigateur et accédez à l'URL indiquée. Vous devriez voir la page d'accueil de Laravel :

![Page d'accueil de Laravel 11](https://user-images.githubusercontent.com/placeholder/laravel-welcome-page.png)

Cette page confirme que votre installation Laravel fonctionne correctement !

### 🔄 Développement avec rechargement automatique

Pour un workflow plus fluide, vous pouvez utiliser l'outil de rechargement automatique Laravel inclus dans la configuration Vite :

```bash
# Installer les dépendances npm
npm install

# Démarrer le serveur de développement avec rechargement automatique
npm run dev
```

Dans un autre terminal, lancez le serveur PHP :

```bash
php artisan serve
```

Maintenant, lorsque vous modifiez des fichiers PHP, Blade, CSS ou JS, le navigateur se rechargera automatiquement.

---

## 📝 Bonnes pratiques pour démarrer un projet

Voici quelques bonnes pratiques à adopter dès le début de votre projet Laravel :

### 1. Initialiser un dépôt Git

```bash
git init
git add .
git commit -m "Initial commit: Laravel project setup"
```

N'oubliez pas de créer un fichier `.gitignore` approprié (Laravel en fournit déjà un par défaut qui exclut le dossier `vendor/`, les fichiers d'environnement, etc.).

### 2. Définir des conventions de codage

Suivez le [PSR-12](https://www.php-fig.org/psr/psr-12/) pour la syntaxe PHP et les conventions de Laravel pour la structure des fichiers.

### 3. Configurer l'éditeur/IDE

Si vous utilisez VS Code, installez les extensions recommandées pour Laravel (voir la section de configuration de l'éditeur dans les prérequis).

### 4. Documenter les choix initiaux

Créez un fichier `ARCHITECTURE.md` pour documenter vos choix techniques, l'architecture et les conventions du projet.

### 5. Mettre en place un workflow de validation

Avant chaque commit, vérifiez :
- Que les tests passent
- Que le code respecte les standards
- Qu'il n'y a pas de changements accidentels dans les fichiers de configuration

---

## 🩹 Dépannage des problèmes courants

### 🔴 Problème : La page Laravel affiche une erreur 500

**Causes possibles :**
- Le fichier `.env` n'existe pas
- La clé d'application n'a pas été générée
- Permissions incorrectes sur certains dossiers

**Solutions :**
1. Vérifiez que le fichier `.env` existe : `ls -la`
2. Régénérez la clé d'application : `php artisan key:generate`
3. Corrigez les permissions : `chmod -R 775 storage bootstrap/cache`

### 🔴 Problème : Erreur de connexion à la base de données

**Causes possibles :**
- Identifiants incorrects dans `.env`
- La base de données n'existe pas
- Le service MySQL n'est pas démarré

**Solutions :**
1. Vérifiez vos identifiants
2. Créez la base de données manuellement
3. Démarrez le service MySQL : 
   - Windows (XAMPP) : via le panneau de contrôle
   - Linux : `sudo systemctl start mysql`
   - macOS : `brew services start mysql`

### 🔴 Problème : Le port 8000 est déjà utilisé

**Solutions :**
1. Utilisez un autre port : `php artisan serve --port=8080`
2. Trouvez et arrêtez le processus qui utilise le port 8000

### 🔴 Problème : Modules PHP manquants

**Solution :**
Installez les extensions PHP requises selon votre système d'exploitation (voir le document des prérequis).

---

## 📌 Code source de cette étape

Le code source correspondant à cette étape est disponible sur la branche `step-1`.

---

## 📌 Prochaine étape

Maintenant que notre projet est créé et configuré, nous allons passer à la création des modèles et des migrations pour structurer notre base de données. **[➡️ Étape suivante : Création des modèles et migrations](03-modeles-migrations.md)**.