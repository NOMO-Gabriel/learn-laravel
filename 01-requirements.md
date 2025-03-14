# 🛠️ Prérequis - Installation des outils nécessaires

Avant de commencer le projet, assurez-vous d’avoir les outils suivants installés sur votre machine.

## ✅ 1. Outils requis
- **PHP ≥ 8.2**
- **Composer** (gestionnaire de dépendances PHP)
- **Laravel 11**
- **MySQL** (ou SQLite, PostgreSQL selon votre choix)
- **Node.js ≥ 18** et **npm** (pour la gestion des assets)
- **Git** (facultatif, mais recommandé)
- **Postman ou Insomnia** (pour tester l’API)
- **Un navigateur** moderne (Chrome, Firefox, Edge, Brave)
- **Un éditeur de code** (VS Code, PHPStorm, Sublime Text)

---

## 💻 2. Installation des outils en fonction de l'OS

- [Installation Windows](#-installation-windows)
- [Installation Linux](#-installation-linux-ubuntudebian)
- [Installation macOS](#-installation-macos)

### 🏁 Installation Windows

1. **Installer PHP**  
   Téléchargez et installez **[XAMPP](https://www.apachefriends.org/fr/download.html)** ou **[PHP](https://windows.php.net/download/)**.  
   ➜ Ajoutez PHP au `PATH` pour pouvoir l'utiliser en ligne de commande.

2. **Installer Composer**  
   - Téléchargez et installez **[Composer](https://getcomposer.org/download/)**.

3. **Installer Laravel**  
   Exécutez la commande :
   ```sh
   composer global require laravel/installer
   ```

4. **Installer MySQL**  
   - Avec **XAMPP** : Activez MySQL dans le panneau de contrôle.  
   - Sinon, installez **MySQL** depuis **[ce lien](https://dev.mysql.com/downloads/installer/)**.

5. **Installer Node.js et npm**  
   Téléchargez et installez **[Node.js](https://nodejs.org/)**.

6. **Installer Postman ou Insomnia**  
   - **[Postman](https://www.postman.com/downloads/)**
   - **[Insomnia](https://insomnia.rest/download/)**

7. **Installer un éditeur de code**  
   - **[VS Code](https://code.visualstudio.com/) (recommandé)**
   - **[PHPStorm](https://www.jetbrains.com/phpstorm/)**
   - **[Sublime Text](https://www.sublimetext.com/)**

8. **Vérifier les installations**  
   ```sh
   php -v        # Vérifie la version de PHP
   composer -V   # Vérifie Composer
   mysql --version  # Vérifie MySQL
   node -v       # Vérifie Node.js
   npm -v        # Vérifie npm
   ```

---

### 🐧 Installation Linux (Ubuntu/Debian)

1. **Mettre à jour le système**
   ```sh
   sudo apt update && sudo apt upgrade -y
   ```

2. **Installer PHP, Composer et extensions**
   ```sh
   sudo apt install php-cli php-mbstring php-xml unzip curl -y
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer
   ```

3. **Installer Laravel**
   ```sh
   composer global require laravel/installer
   ```

4. **Installer MySQL**
   ```sh
   sudo apt install mysql-server -y
   sudo systemctl start mysql
   sudo systemctl enable mysql
   ```

5. **Installer Node.js et npm**
   ```sh
   curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
   sudo apt install -y nodejs
   ```

6. **Installer Postman ou Insomnia**
   - **Postman**  
     ```sh
     sudo snap install postman
     ```
   - **Insomnia**  
     ```sh
     sudo snap install insomnia
     ```

7. **Installer un éditeur de code**
   - **VS Code**  
     ```sh
     sudo snap install code --classic
     ```
   - **PHPStorm**  
     ```sh
     sudo snap install phpstorm --classic
     ```
   - **Sublime Text**  
     ```sh
     sudo snap install sublime-text --classic
     ```

8. **Vérifier les installations**
   ```sh
   php -v
   composer -V
   mysql --version
   node -v
   npm -v
   ```

---

### 🍏 Installation macOS

1. **Installer Homebrew (si pas encore installé)**
   ```sh
   /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
   ```

2. **Installer PHP et Composer**
   ```sh
   brew install php
   brew install composer
   ```

3. **Installer Laravel**
   ```sh
   composer global require laravel/installer
   ```

4. **Installer MySQL**
   ```sh
   brew install mysql
   brew services start mysql
   ```

5. **Installer Node.js et npm**
   ```sh
   brew install node
   ```

6. **Installer Postman ou Insomnia**
   - **Postman**  
     ```sh
     brew install --cask postman
     ```
   - **Insomnia**  
     ```sh
     brew install --cask insomnia
     ```

7. **Installer un éditeur de code**
   - **VS Code**  
     ```sh
     brew install --cask visual-studio-code
     ```
   - **PHPStorm**  
     ```sh
     brew install --cask phpstorm
     ```
   - **Sublime Text**  
     ```sh
     brew install --cask sublime-text
     ```

8. **Vérifier les installations**
   ```sh
   php -v
   composer -V
   mysql --version
   node -v
   npm -v
   ```

---

## 🌐 3. Choisir un navigateur
Utilisez un navigateur moderne pour tester votre application :
- **Google Chrome** ➜ [Télécharger](https://www.google.com/chrome/)
- **Mozilla Firefox** ➜ [Télécharger](https://www.mozilla.org/fr/firefox/)
- **Microsoft Edge** ➜ [Télécharger](https://www.microsoft.com/edge)
- **Brave** ➜ [Télécharger](https://brave.com/)

---

## 🚀 Prochaine étape
Une fois ces outils installés, passez à l'étape suivante : **[Création et configuration du projet](02-creation-configuration.md)**.
```
