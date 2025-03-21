# Architecture du projet Gestion de Dépenses

Ce document décrit l'architecture technique et les conventions adoptées pour le projet de gestion de dépenses.

## 🏗️ Architecture générale

Le projet suit l'architecture MVC (Modèle-Vue-Contrôleur) fournie par Laravel 11 :
- **Modèles :** Représentation des données et logique métier
- **Vues :** Interface utilisateur (templates Blade)
- **Contrôleurs :** Coordination entre modèles et vues

## 📊 Structure de la base de données

### Entités principales
- **User :** Utilisateurs de l'application
- **Category :** Catégories de dépenses et revenus
- **Expense :** Dépenses des utilisateurs
- **Income :** Revenus des utilisateurs

### Relations
- Un utilisateur peut avoir plusieurs dépenses et revenus (One-to-Many)
- Chaque dépense/revenu appartient à une catégorie (Many-to-One)
- Chaque dépense/revenu appartient à un utilisateur (Many-to-One)

## 🧩 Patterns de conception

- **Repository Pattern :** Pour l'abstraction de la couche de données
- **Service Layer :** Pour encapsuler la logique métier complexe
- **Form Request Validation :** Pour la validation des données en entrée

## 🔧 Conventions de nommage

### Base de données
- Tables : pluriel, snake_case (ex: `users`, `expense_categories`)
- Clés primaires : `id`
- Clés étrangères : nom de la table référencée au singulier + `_id` (ex: `user_id`)
- Timestamps : `created_at`, `updated_at`

### Code
- Classes : PascalCase (ex: `ExpenseController`)
- Méthodes et variables : camelCase (ex: `calculateTotal()`)
- Constantes : UPPER_SNAKE_CASE (ex: `MAX_AMOUNT`)
- Fichiers Blade : kebab-case (ex: `expense-list.blade.php`)

## 🛠️ Choix technologiques

- **PHP 8.2+** : Utilisation des fonctionnalités modernes (types, attributs, etc.)
- **Laravel 11** : Framework principal
- **MySQL/SQLite** : Base de données relationnelle
- **Blade** : Moteur de templates
- **Laravel Breeze** : Authentification légère
- **Bootstrap** : Framework CSS pour l'interface

## 📚 Standards de codage

Le projet suit les standards PSR-12 pour la syntaxe PHP et les conventions de Laravel.

### Règles spécifiques
- Commentaires en français
- Documentation des méthodes publiques
- Tests pour les fonctions critiques
- Commits atomiques avec messages explicites

## 🔄 Workflow de développement

1. Développement sur branches feature/fix
2. Tests et validation locale
3. Pull Request vers main
4. Revue de code
5. Merge et déploiement

## 📋 Structure des dossiers personnalisée

- `app/Services/` : Contient les services métier
- `app/Repositories/` : Contient les repositories
- `app/DTOs/` : Objets de transfert de données
- `resources/js/components/` : Composants JavaScript