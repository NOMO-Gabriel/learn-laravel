# 🎯 Création et configuration du projet

[⬅️ Étape précédente : Prérequis](01-requirements.md)

Dans cette étape, nous allons créer notre projet Laravel 11 et le configurer pour qu’il soit prêt à être développé.

---

## 📌 1. Création du projet Laravel

Dans votre terminal, exécutez la commande suivante pour créer un nouveau projet Laravel :

```sh
composer create-project --prefer-dist laravel/laravel gestion-depenses
```

Ensuite, accédez au dossier du projet :

```sh
cd gestion-depenses
```

---

## ⚙️ 2. Configuration de l'environnement

Dupliquez le fichier `.env.example` et renommez-le en `.env` :

```sh
cp .env.example .env
```

Puis, générez la clé d'application Laravel :

```sh
php artisan key:generate
```

---

## 🛠️ 3. Configuration de la base de données

### 🔹 Option 1 : Utilisation de MySQL

Ouvrez le fichier `.env` et modifiez ces lignes selon votre configuration MySQL :

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_depenses
DB_USERNAME=root
DB_PASSWORD=
```

Ensuite, créez la base de données (remplacez `root` et le mot de passe si nécessaire) :

```sh
mysql -u root -p -e "CREATE DATABASE gestion_depenses;"
```

### 🔹 Option 2 : Utilisation de SQLite

Si vous préférez SQLite, modifiez le fichier `.env` comme suit :

```ini
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Puis, créez le fichier SQLite :

```sh
touch database/database.sqlite
```

Assurez-vous que Laravel puisse accéder au fichier SQLite en ajustant les permissions si nécessaire :

```sh
chmod 664 database/database.sqlite
```

---

## 🚀 4. Lancer le serveur de développement

Exécutez la commande suivante pour démarrer le serveur Laravel :

```sh
php artisan serve
```

Si le port `8000` est déjà occupé, Laravel attribuera automatiquement un autre port. Regardez bien la sortie du terminal pour voir l’URL correcte.

---

## 📂 5. Structure du projet

Lorsque vous créez un projet Laravel, vous obtenez une structure de fichiers et de dossiers bien organisée. Voici un aperçu des principaux éléments :

- `app/` : Contient la logique métier de l’application (modèles, contrôleurs, services, etc.).
- `bootstrap/` : Contient le fichier d’amorçage de l’application.
- `config/` : Contient tous les fichiers de configuration.
- `database/` : Contient les migrations, les seeders et le fichier SQLite si vous utilisez cette base de données.
- `public/` : Contient le point d’entrée de l’application (`index.php`), ainsi que les fichiers accessibles publiquement (CSS, JS, images).
- `resources/` : Contient les vues Blade, les fichiers CSS et JS (avant compilation).
- `routes/` : Contient les fichiers de définition des routes (`web.php`, `api.php`, etc.).
- `storage/` : Contient les logs, les fichiers de cache et les fichiers uploadés.
- `tests/` : Contient les tests unitaires et fonctionnels.
- `.env` : Fichier de configuration des variables d’environnement (base de données, clé API, etc.).
- `artisan` : Outil en ligne de commande pour exécuter des tâches Laravel.
- `composer.json` : Fichier de configuration des dépendances PHP.

---

## 📌 Code source de cette étape

Le code source correspondant à cette étape est disponible sur la branche `step-2`.

---

## 📌 Prochaine étape

Nous allons maintenant créer les modèles et migrations pour structurer notre base de données. **[➡️ Étape suivante : Création des modèles et migrations](03-modeles-migrations.md)**.
