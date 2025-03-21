# 💰 Gestionnaire Personnel de Dépenses - Tutoriel Laravel 11

<p align="center">
  <img src="https://user-images.githubusercontent.com/placeholder/expense-manager-banner.png" alt="Gestionnaire de Dépenses Banner" width="700">
</p>

<p align="center">
  <a href="https://laravel.com/docs/11.x"><img src="https://img.shields.io/badge/Laravel-v11.0-FF2D20?style=flat-square&logo=laravel" alt="Laravel v11.0"></a>
  <a href="https://www.php.net/releases/8.2/en.php"><img src="https://img.shields.io/badge/PHP-%E2%89%A58.2-8892BF?style=flat-square&logo=php" alt="PHP ≥8.2"></a>
  <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License"></a>
  <a href="#"><img src="https://img.shields.io/badge/Status-En%20développement-yellow?style=flat-square" alt="Status"></a>
</p>

## 📌 Présentation du projet

Le **Gestionnaire Personnel de Dépenses** est une application web complète développée avec Laravel 11 qui vous permet de suivre, analyser et gérer efficacement vos finances personnelles. Plus qu'un simple tutoriel, ce projet offre une application fonctionnelle que vous pourrez adapter et étendre selon vos besoins.

### 🎯 Pourquoi ce projet ?

La gestion financière personnelle est un défi pour beaucoup. Ce projet répond à plusieurs problèmes concrets :

- **Manque de visibilité** sur les habitudes de dépenses
- **Difficulté à suivre** les revenus et dépenses au fil du temps
- **Absence d'outils adaptés** pour catégoriser les transactions
- **Besoin d'analyse** pour identifier les tendances et opportunités d'économies

Ce tutoriel vous guide pas à pas dans la création d'une solution complète, du backend au frontend, en utilisant les meilleures pratiques de développement Laravel.

## 📚 Concepts Laravel abordés

Ce projet couvre les notions essentielles et avancées de Laravel 11 :

| Concept | Description | Niveau |
|---------|-------------|--------|
| **Routes (Web/API)** | Définition des endpoints de l'application | Fondamental |
| **Modèles Eloquent** | ORM pour la gestion des données et relations | Fondamental |
| **Migrations** | Versionnement de la structure de base de données | Fondamental |
| **Controllers** | Logique métier et traitement des requêtes | Fondamental |
| **Blade** | Création d'interfaces dynamiques | Fondamental |
| **Middleware** | Filtrage des requêtes HTTP | Intermédiaire |
| **Authentification** | Sécurisation avec Laravel Breeze | Intermédiaire |
| **Validation** | Vérification des données entrantes | Intermédiaire |
| **Seeders/Factories** | Génération de données de test | Intermédiaire |
| **API RESTful** | Création d'une API complète | Intermédiaire |
| **JWT** | Sécurisation de l'API avec JSON Web Tokens | Avancé |
| **Gestion des rôles** | Contrôle d'accès avec Laravel Permission | Avancé |
| **Stockage de fichiers** | Upload et gestion avec Storage | Avancé |
| **Export PDF/Excel** | Génération de rapports | Avancé |

## 💰 Fonctionnalités de l'application

### 🧑‍💼 Pour les utilisateurs
- 📊 **Dashboard** avec statistiques et graphiques
- 💰 **Ajout/modification** de dépenses et revenus  
- 🏷️ **Catégorisation** des transactions
- 📄 **Bilans périodiques** (mensuel, annuel)
- 📱 **Gestion de profil** avec photo
- 📁 **Export des données** en PDF/Excel

### 👑 Pour les administrateurs
- 👥 **Gestion des utilisateurs** (ajout, blocage, suppression)
- 📊 **Dashboard analytique** avec statistiques globales
- 🏷️ **Gestion des catégories** système

## 🏗️ Architecture et modélisation

L'application est construite autour de quatre entités principales qui forment le cœur du système :

<p align="center">
  <img src="https://user-images.githubusercontent.com/placeholder/entity-relation-diagram.png" alt="Diagramme Entité-Relation" width="600">
</p>

### 📊 Structure de la base de données

| Entité | Description | Relations |
|--------|-------------|-----------|
| **User** | Utilisateur du système | `hasMany` Expense, Income |
| **Expense** | Dépense avec montant, date et description | `belongsTo` User, Category |
| **Income** | Revenu avec montant, date et description | `belongsTo` User, Category |
| **Category** | Catégorie pour classer les transactions | `hasMany` Expense, Income |

Cette structure permet une grande flexibilité tout en maintenant des relations claires entre les données.

### 📐 Architecture MVC

Le projet suit l'architecture Modèle-Vue-Contrôleur (MVC) de Laravel :

- **Modèles** (`/app/Models`) : Représentent les données et les relations
- **Vues** (`/resources/views`) : Interfaces utilisateur en Blade
- **Contrôleurs** (`/app/Http/Controllers`) : Logique de traitement des requêtes

Cette séparation des responsabilités facilite la maintenance et l'extension du code.

## ⏱️ Parcours d'apprentissage

Ce tutoriel est structuré pour un apprentissage progressif. Chaque étape s'appuie sur les connaissances acquises précédemment.

| Étape | Contenu | Durée estimée | Compétences acquises |
|-------|---------|---------------|----------------------|
| **Prérequis** | Installation des outils | 1-2h | Environnement de développement Laravel |
| **Configuration** | Création et paramétrage du projet | 30min | Configuration Laravel de base |
| **Modèles/Migrations** | Structure de la base de données | 2-3h | Eloquent ORM, relations, migrations |
| **Fixtures** | Remplissage de la base | 1-2h | Seeders, factories, données de test |
| **Contrôleurs/Routes** | Logique métier et routage | 2-3h | Controllers, routes, middleware |
| **Interfaces Blade** | Création des vues | 3-4h | Templates Blade, formulaires, composants |
| **Tests interfaces** | Vérification des fonctionnalités | 1-2h | Tests manuels, débogage |
| **API** | Création de l'API RESTful | 2-3h | API Resources, contrôleurs API |
| **Tests API** | Validation avec Postman | 1-2h | Tests d'API, client HTTP |
| **Sécurité JWT** | Protection de l'API | 2h | JWT, authentification API |
| **Tests finaux** | Vérification complète | 1-2h | Tests système |

### 🎓 Niveau requis

- **Prérequis** : Connaissances de base en PHP et programmation orientée objet
- **Recommandé** : Notions de HTML, CSS, JavaScript et bases de données SQL
- **Bonus** : Expérience avec les frameworks MVC (mais pas obligatoire)

## 🚀 Comment utiliser ce tutoriel

### 1️⃣ Suivre le parcours complet

Pour une expérience d'apprentissage optimale, suivez les étapes dans l'ordre proposé :

0. [Requirements](01-requirements.md) - Installation des outils nécessaires
1. [Création et configuration du projet](02-creation-configuration.md)
2. [Création des modèles et migrations](03-modeles-migrations.md)
3. [Remplissage de la base de données avec des fixtures](04-fixtures.md)

### 2️⃣ Développement de l'application Blade

4. [Création des contrôleurs et routage (web.php)](05-controllers-web.md)
5. [Création des interfaces (Dashboard, profil, gestion)](06-interfaces.md)
6. [Tests des interfaces](07-tests-interfaces.md)

### 3️⃣ Développement de l'API

7. [Création des contrôleurs d'API](08-controllers-api.md)
8. [Tests avec Postman](09-tests-api.md)
9. [Sécurisation de l'API avec JWT](10-auth-jwt.md)
10. [Tests finaux avec Postman](11-tests-final.md)

## ❓ Dépannage courant

Voici quelques problèmes fréquemment rencontrés et leurs solutions :

### 🔴 Les migrations échouent

**Problème** : Les migrations échouent avec des erreurs de clés étrangères.  
**Solution** : Vérifiez l'ordre des migrations. Les tables référencées doivent être créées avant celles qui les référencent.

### 🔴 Les relations Eloquent ne fonctionnent pas

**Problème** : Les relations entre modèles retournent null ou des erreurs.  
**Solution** : Vérifiez que les noms de méthodes et de clés étrangères respectent les conventions Laravel. Assurez-vous que les modèles sont correctement importés.

### 🔴 Les formulaires ne soumettent pas les données

**Problème** : Les données de formulaire ne sont pas envoyées.  
**Solution** : Vérifiez que chaque formulaire inclut le jeton CSRF (`@csrf`) et que les champs correspondent aux attributs `$fillable` du modèle.

### 🔴 L'authentification ne fonctionne pas

**Problème** : Problèmes avec l'inscription ou la connexion.  
**Solution** : Vérifiez la configuration de Breeze et les routes d'authentification. Assurez-vous que les migrations ont été exécutées correctement.

## 🤝 Comment contribuer

Si vous souhaitez contribuer à ce tutoriel :

1. **Signalez des problèmes** en ouvrant une issue
2. **Proposez des améliorations** via des pull requests
3. **Partagez le projet** avec la communauté Laravel

## 📄 Licence

Ce projet est distribué sous la licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus d'informations.

---

## ⭐ Soutenez ce projet !

Si vous aimez cette formation, **n'oubliez pas de follow** le compte et de **mettre une étoile ⭐ sur le repository GitHub** ! Cela m'encouragera à produire encore plus de contenus de qualité. Merci pour votre soutien ! 🙌

## 🎉 Félicitations et à vous de jouer !

En complétant ce tutoriel, vous aurez développé une application web complète et acquis des compétences précieuses en développement Laravel. Ces connaissances vous permettront de créer vos propres applications et de contribuer à des projets professionnels.

N'hésitez pas à personnaliser et étendre cette application pour répondre à vos besoins spécifiques !