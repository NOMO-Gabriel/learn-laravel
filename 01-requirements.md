# 🛠️ Prérequis - Installation des outils nécessaires

Avant de commencer le projet, assurez-vous d'avoir les outils requis installés sur votre machine. Ce document vous guidera à travers l'installation et la configuration de l'environnement de développement pour notre application Laravel.

## 📋 Table des matières
- [Outils requis et leurs rôles](#-1-outils-requis-et-leurs-rôles)
- [Installation selon votre système d'exploitation](#-2-installation-des-outils-en-fonction-de-los)
  - [Installation Windows](#-installation-windows)
  - [Installation Linux](#-installation-linux-ubuntudebian)
  - [Installation macOS](#-installation-macos)
- [Vérification de l'installation](#-3-vérification-de-linstallation)
- [Alternative : Utiliser Docker](#-4-alternative--utiliser-docker)
- [Configuration de l'éditeur de code](#-5-configuration-de-léditeur-de-code)
- [Configuration avancée](#-6-configuration-avancée)
- [Dépannage courant](#-7-dépannage-courant)

---

## ✅ 1. Outils requis et leurs rôles

Voici les outils que nous utiliserons, et pourquoi ils sont importants :

| Outil | Version | Rôle dans le projet |
|-------|---------|---------------------|
| **PHP** | ≥ 8.2 | Langage de programmation côté serveur sur lequel repose Laravel |
| **Composer** | Dernière | Gestionnaire de dépendances PHP, permet d'installer Laravel et ses packages |
| **Laravel** | 11 | Framework PHP qui constitue la base de notre application |
| **MySQL** | ≥ 8.0 | Système de gestion de base de données relationnelle (alternatives : SQLite, PostgreSQL) |
| **Node.js & npm** | ≥ 18 | Environnement JavaScript pour compiler les assets front-end (CSS, JavaScript) |
| **Git** | Dernière | Système de contrôle de version pour suivre les modifications du code |
| **Postman/Insomnia** | Dernière | Outil pour tester les API REST que nous allons développer |
| **Navigateur moderne** | - | Pour tester l'application et utiliser les outils de développement |
| **Éditeur de code** | - | IDE ou éditeur de texte pour écrire et modifier le code source |

---

## 💻 2. Installation des outils en fonction de l'OS

- [Installation Windows](#-installation-windows)
- [Installation Linux](#-installation-linux-ubuntudebian)
- [Installation macOS](#-installation-macos)

### 🏁 Installation Windows

#### 1. Installer PHP
Deux options s'offrent à vous :

**Option A : XAMPP (recommandée pour les débutants)**
- Téléchargez [XAMPP](https://www.apachefriends.org/fr/download.html) qui inclut PHP, MySQL et Apache
- Pendant l'installation, assurez-vous de sélectionner PHP et MySQL
- Après l'installation, ajoutez PHP au PATH Windows :
  - Allez dans `Paramètres système avancés` > `Variables d'environnement`
  - Modifiez la variable `Path`
  - Ajoutez `C:\xampp\php` (ou votre chemin d'installation XAMPP)

**Option B : PHP Standalone**
- Téléchargez la dernière version de PHP 8.2+ depuis [windows.php.net](https://windows.php.net/download/)
- Extrayez l'archive ZIP dans un dossier, par exemple `C:\php`
- Ajoutez ce dossier au PATH comme expliqué précédemment
- Renommez `php.ini-development` en `php.ini`
- Ouvrez `php.ini` et décommentez ces extensions (retirez le `;` au début) :
  ```ini
  extension=curl
  extension=fileinfo
  extension=mbstring
  extension=openssl
  extension=pdo_mysql
  extension=pdo_sqlite
  extension=sqlite3
  ```

#### 2. Installer Composer
- Téléchargez et exécutez l'[installateur Composer](https://getcomposer.org/download/)
- Suivez les instructions en vous assurant que l'installateur détecte correctement votre installation PHP

#### 3. Installer Laravel
```powershell
composer global require laravel/installer
```
Ajoutez ensuite le dossier `%USERPROFILE%\AppData\Roaming\Composer\vendor\bin` à votre PATH.

#### 4. Installer MySQL
**Option A : Avec XAMPP**
- MySQL est déjà inclus, activez le service depuis le panneau de contrôle XAMPP

**Option B : Installation indépendante**
- Téléchargez et installez [MySQL Community Server](https://dev.mysql.com/downloads/mysql/)
- Pendant l'installation, notez le mot de passe root que vous définissez

#### 5. Installer SQLite
SQLite est généralement déjà activé si vous avez suivi les instructions pour PHP. Vérifiez avec :
```powershell
php -m | findstr sqlite
```

#### 6. Installer Node.js et npm
- Téléchargez et installez la [version LTS de Node.js](https://nodejs.org/)
- npm est inclus avec Node.js

#### 7. Installer Git
- Téléchargez et installez [Git pour Windows](https://git-scm.com/download/win)
- Pendant l'installation, choisissez l'option "Use Git from the Windows Command Prompt"

#### 8. Installer Postman ou Insomnia
- [Postman](https://www.postman.com/downloads/)
- [Insomnia](https://insomnia.rest/download/)

#### 9. Installer un éditeur de code
- [VS Code](https://code.visualstudio.com/) (recommandé)
- [PHPStorm](https://www.jetbrains.com/phpstorm/) (payant, mais puissant)
- [Sublime Text](https://www.sublimetext.com/)

---

### 🐧 Installation Linux (Ubuntu/Debian)

#### 1. Mettre à jour le système
```bash
sudo apt update && sudo apt upgrade -y
```

#### 2. Installer PHP et extensions nécessaires
```bash
# Ajouter le PPA pour PHP 8.2 ou supérieur
sudo add-apt-repository ppa:ondrej/php
sudo apt update

# Installer PHP et les extensions requises
sudo apt install php8.2 php8.2-cli php8.2-common php8.2-curl php8.2-mbstring php8.2-xml php8.2-zip php8.2-mysql php8.2-sqlite3 php8.2-bcmath php8.2-gd unzip -y
```

#### 3. Installer Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
```

#### 4. Installer Laravel
```bash
composer global require laravel/installer
```
Ajoutez ensuite le répertoire Composer bin à votre PATH dans votre fichier `~/.bashrc` ou `~/.zshrc` :
```bash
echo 'export PATH="$PATH:$HOME/.config/composer/vendor/bin"' >> ~/.bashrc
source ~/.bashrc
```

#### 5. Installer MySQL
```bash
sudo apt install mysql-server -y
sudo systemctl start mysql
sudo systemctl enable mysql
sudo mysql_secure_installation
```
Suivez les instructions pour sécuriser votre installation MySQL

#### 6. Installer SQLite
```bash
sudo apt install sqlite3 -y
```

#### 7. Installer Node.js et npm
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

#### 8. Installer Git
```bash
sudo apt install git -y
```

#### 9. Installer Postman ou Insomnia
**Postman**
```bash
sudo snap install postman
```
**Insomnia**
```bash
sudo snap install insomnia
```

#### 10. Installer un éditeur de code
**Visual Studio Code**
```bash
sudo snap install code --classic
```
**PHPStorm**
```bash
sudo snap install phpstorm --classic
```
**Sublime Text**
```bash
sudo snap install sublime-text --classic
```

---

### 🍏 Installation macOS

#### 1. Installer Homebrew (si pas encore installé)
```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

#### 2. Installer PHP
```bash
brew install php
```

#### 3. Installer Composer
```bash
brew install composer
```

#### 4. Installer Laravel
```bash
composer global require laravel/installer
```
Ajoutez le répertoire Composer bin à votre PATH :
```bash
echo 'export PATH="$PATH:$HOME/.composer/vendor/bin"' >> ~/.zshrc
source ~/.zshrc
```

#### 5. Installer MySQL
```bash
brew install mysql
brew services start mysql
```
Sécurisez votre installation MySQL :
```bash
mysql_secure_installation
```

#### 6. Installer SQLite
```bash
brew install sqlite
```

#### 7. Installer Node.js et npm
```bash
brew install node
```

#### 8. Installer Git
```bash
brew install git
```

#### 9. Installer Postman ou Insomnia
**Postman**
```bash
brew install --cask postman
```
**Insomnia**
```bash
brew install --cask insomnia
```

#### 10. Installer un éditeur de code
**Visual Studio Code**
```bash
brew install --cask visual-studio-code
```
**PHPStorm**
```bash
brew install --cask phpstorm
```
**Sublime Text**
```bash
brew install --cask sublime-text
```

---

## 🔍 3. Vérification de l'installation

Après avoir installé tous les outils, vérifiez que tout fonctionne correctement :

```bash
# Vérifier la version de PHP (doit être ≥ 8.2)
php -v

# Vérifier Composer
composer -V

# Vérifier Laravel
laravel --version

# Vérifier MySQL
mysql --version

# Vérifier SQLite
sqlite3 --version

# Vérifier Node.js et npm
node -v
npm -v

# Vérifier Git
git --version
```

Si vous obtenez des erreurs ou si certaines commandes ne sont pas reconnues, assurez-vous que les outils sont bien installés et que les chemins sont correctement configurés dans les variables d'environnement.

### Test d'un projet Laravel minimal

Pour vous assurer que tout est prêt, créez un petit projet Laravel de test :

```bash
# Créer un nouveau projet Laravel
laravel new test-project
cd test-project

# Lancer le serveur de développement
php artisan serve
```

Visitez http://localhost:8000 dans votre navigateur. Si vous voyez la page de bienvenue de Laravel, félicitations ! Votre environnement est correctement configuré.

---

## 🐳 4. Alternative : Utiliser Docker

Si vous préférez ne pas installer chaque outil individuellement, Docker offre une excellente alternative pour créer un environnement de développement standardisé.

### Prérequis
- Installer [Docker Desktop](https://www.docker.com/products/docker-desktop/)

### Option 1 : Laravel Sail (Officiel)

Laravel Sail est un environnement de développement léger fourni avec Laravel 11. Pour créer un nouveau projet avec Sail :

```bash
# Créer un projet Laravel avec PHP, MySQL et Redis
curl -s "https://laravel.build/gestion-depenses?with=mysql,redis" | bash

# Démarrer les conteneurs Docker
cd gestion-depenses
./vendor/bin/sail up
```

### Option 2 : Docker Compose manuel

Vous pouvez également utiliser un fichier `docker-compose.yml` personnalisé. Voici un exemple de base :

```yaml
version: '3'
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www/html
    depends_on:
      - mysql
    networks:
      - app-network

  mysql:
    image: mysql:8.0
    ports:
      - "3306:3306"
    environment:
      MYSQL_DATABASE: gestion_depenses
      MYSQL_ROOT_PASSWORD: root
      MYSQL_USER: user
      MYSQL_PASSWORD: password
    volumes:
      - mysql-data:/var/lib/mysql
    networks:
      - app-network

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    ports:
      - "8080:80"
    environment:
      PMA_HOST: mysql
    depends_on:
      - mysql
    networks:
      - app-network

networks:
  app-network:
    driver: bridge

volumes:
  mysql-data:
```

Et un exemple de `Dockerfile` :

```Dockerfile
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Composer dependencies
RUN composer install --no-interaction --no-plugins --no-scripts

# Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

# Install npm dependencies and build assets
RUN npm install && npm run build

# Expose port 8000
EXPOSE 8000

# Start Laravel server
CMD php artisan serve --host=0.0.0.0 --port=8000
```

Pour utiliser ce setup :

```bash
# Créer la structure du projet Laravel
composer create-project --prefer-dist laravel/laravel gestion-depenses
cd gestion-depenses

# Copier les fichiers docker-compose.yml et Dockerfile

# Démarrer les conteneurs
docker-compose up -d
```

---

## 🛠️ 5. Configuration de l'éditeur de code

Un bon éditeur de code avec les bonnes extensions peut considérablement améliorer votre productivité. Voici quelques recommandations pour Visual Studio Code, l'éditeur le plus populaire pour le développement Laravel :

### Extensions recommandées pour VS Code

1. **PHP Intelephense** - Support PHP avec autocomplétion, analyse de code, etc.
2. **Laravel Blade Snippets** - Snippets pour les templates Blade
3. **Laravel Artisan** - Exécuter les commandes Artisan directement depuis VS Code
4. **Laravel Snippets** - Snippets pour Laravel
5. **Laravel Extra Intellisense** - Autocomplétion pour les routes, vues, etc.
6. **DotENV** - Support pour les fichiers .env
7. **PHP Namespace Resolver** - Aide à l'import des classes PHP
8. **PHP Debug** - Support pour le débogage PHP avec XDebug
9. **EditorConfig** - Support pour EditorConfig
10. **Tailwind CSS IntelliSense** - Si vous utilisez Tailwind CSS

### Installation rapide des extensions

Pour installer toutes ces extensions en une seule commande, vous pouvez utiliser :

```bash
code --install-extension bmewburn.vscode-intelephense-client \
     --install-extension onecentlin.laravel-blade \
     --install-extension ryannaddy.laravel-artisan \
     --install-extension amiralizadeh9480.laravel-extra-intellisense \
     --install-extension shufo.vscode-blade-formatter \
     --install-extension mikestead.dotenv \
     --install-extension MehediDracula.php-namespace-resolver \
     --install-extension xdebug.php-debug \
     --install-extension editorconfig.editorconfig \
     --install-extension bradlc.vscode-tailwindcss
```

### Configuration recommandée

Créez un fichier `.vscode/settings.json` à la racine de votre projet avec cette configuration :

```json
{
    "editor.formatOnSave": true,
    "php.suggest.basic": false,
    "[php]": {
        "editor.defaultFormatter": "bmewburn.vscode-intelephense-client"
    },
    "[blade]": {
        "editor.defaultFormatter": "shufo.vscode-blade-formatter"
    },
    "blade.format.indentSize": 4,
    "emmet.includeLanguages": {
        "blade": "html"
    }
}
```

---

## ⚙️ 6. Configuration avancée

Cette section contient des configurations optionnelles pour optimiser votre environnement de développement.

### Optimisation de PHP

Éditez votre fichier `php.ini` pour améliorer les performances et la mémoire allouée :

```ini
; Augmenter la limite de mémoire
memory_limit = 512M

; Augmenter le temps d'exécution maximum
max_execution_time = 120

; Optimiser la taille des uploads
upload_max_filesize = 32M
post_max_size = 32M

; Activer les extensions utiles pour Laravel
extension=curl
extension=fileinfo
extension=gd
extension=mbstring
extension=exif
extension=mysqli
extension=openssl
extension=pdo_mysql
extension=pdo_sqlite
```

### Configuration Git globale

Paramétrez Git avec vos informations :

```bash
git config --global user.name "Votre Nom"
git config --global user.email "votre.email@exemple.com"
git config --global init.defaultBranch main
git config --global core.editor "code --wait"
```

### Configuration de Xdebug pour le débogage

Xdebug est un puissant outil de débogage pour PHP. Pour l'installer :

**Windows (XAMPP)**
1. Téléchargez la DLL correspondante à votre version de PHP sur [xdebug.org](https://xdebug.org/download)
2. Placez-la dans le dossier `C:\xampp\php\ext`
3. Ajoutez ces lignes à votre `php.ini` :
   ```ini
   [XDebug]
   zend_extension=xdebug
   xdebug.mode=debug
   xdebug.start_with_request=yes
   xdebug.client_port=9003
   ```

**Linux**
```bash
sudo apt-get install php8.2-xdebug
```
Puis éditez `/etc/php/8.2/cli/conf.d/20-xdebug.ini` avec la configuration ci-dessus.

**macOS**
```bash
pecl install xdebug
```
Ajoutez ensuite la configuration à votre `php.ini`.

### Configuration de composer.json optimisée

Pour optimiser votre fichier `composer.json` :

```json
{
    "config": {
        "optimize-autoloader": true,
        "preferred-install": "dist",
        "sort-packages": true
    }
}
```

---

## 🩹 7. Dépannage courant

Voici quelques problèmes courants que vous pourriez rencontrer et leurs solutions :

### 🔴 PHP n'est pas reconnu comme commande

**Problème** : Lorsque vous tapez `php -v`, vous obtenez une erreur indiquant que la commande n'est pas reconnue.

**Solution** :
- Vérifiez que PHP est correctement installé
- Assurez-vous que le chemin vers PHP est dans votre variable d'environnement PATH
- Redémarrez votre terminal ou votre ordinateur
- Sous Windows, essayez d'utiliser le chemin complet : `C:\xampp\php\php.exe -v`

### 🔴 Erreur "Access denied" avec MySQL

**Problème** : Vous ne pouvez pas vous connecter à MySQL.

**Solution** :
- Vérifiez les identifiants dans le fichier `.env`
- Assurez-vous que le service MySQL est démarré
- Réinitialisez le mot de passe root (si nécessaire) :
  ```bash
  # Windows (xampp)
  C:\xampp\mysql\bin\mysql -u root -p
  
  # Linux
  sudo mysql -u root -p
  ```
  Puis dans l'invite MySQL : 
  ```sql
  ALTER USER 'root'@'localhost' IDENTIFIED BY 'nouveau_mot_de_passe';
  FLUSH PRIVILEGES;
  ```

### 🔴 Composer montre des erreurs de dépendance PHP

**Problème** : Composer affiche des erreurs concernant des extensions PHP manquantes.

**Solution** :
- Installez les extensions manquantes :
  ```bash
  # Windows (xampp)
  # Décommenter les extensions dans php.ini
  
  # Linux
  sudo apt-get install php8.2-{extension_manquante}
  
  # macOS
  brew install php@8.2-{extension_manquante}
  ```

### 🔴 Laravel Installer n'est pas reconnu

**Problème** : La commande `laravel` n'est pas reconnue.

**Solution** :
- Vérifiez que Composer global bin est dans votre PATH
- Installez Laravel directement avec Composer :
  ```bash
  composer create-project --prefer-dist laravel/laravel gestion-depenses
  ```

### 🔴 Le serveur artisan ne démarre pas (port occupé)

**Problème** : Lorsque vous exécutez `php artisan serve`, vous obtenez une erreur indiquant que le port 8000 est déjà utilisé.

**Solution** :
- Utilisez un port différent :
  ```bash
  php artisan serve --port=8080
  ```
- Trouvez et arrêtez le processus qui utilise le port 8000 :
  ```bash
  # Windows
  netstat -ano | findstr :8000
  taskkill /PID {PID} /F
  
  # Linux/macOS
  lsof -i :8000
  kill -9 {PID}
  ```

### 🔴 Problèmes de permissions sur Linux/macOS

**Problème** : Erreurs de permissions lors de l'accès aux fichiers ou dossiers.

**Solution** :
```bash
# Donner les bonnes permissions au dossier storage
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache
```

---

## 🌐 Prochaine étape
Une fois ces outils installés, passez à l'étape suivante : **[Création et configuration du projet](02-creation-configuration.md)**.