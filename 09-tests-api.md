# 🧪 Tests de l'API avec Postman

[⬅️ Étape précédente : Création des contrôleurs d'API](08-controllers-api.md)  
[➡️ Étape suivante : Sécurisation de l'API avec JWT](10-auth-jwt.md)

---

## 📑 Table des matières

### PARTIE 1: PRÉPARATION
- [Introduction aux tests d'API](#introduction-aux-tests-dapi)
- [Installation et configuration de Postman](#installation-et-configuration-de-postman)
- [Création d'une collection pour les tests](#création-dune-collection-pour-les-tests)
- [Gestion des variables d'environnement](#gestion-des-variables-denvironnement)
- [Vue d'ensemble des endpoints à tester](#vue-densemble-des-endpoints-à-tester)

### PARTIE 2: TESTS DES ENDPOINTS D'AUTHENTIFICATION
- [Test de l'inscription (Register)](#test-de-linscription-register)
- [Test de la connexion (Login)](#test-de-la-connexion-login)
- [Test de la déconnexion (Logout)](#test-de-la-déconnexion-logout)
- [Test de la récupération du profil utilisateur](#test-de-la-récupération-du-profil-utilisateur)

### PARTIE 3: TESTS DES ENDPOINTS UTILISATEURS
- [Test de récupération de la liste des utilisateurs](#test-de-récupération-de-la-liste-des-utilisateurs)
- [Test de récupération d'un utilisateur spécifique](#test-de-récupération-dun-utilisateur-spécifique)
- [Test de création d'un utilisateur](#test-de-création-dun-utilisateur)
- [Test de mise à jour d'un utilisateur](#test-de-mise-à-jour-dun-utilisateur)
- [Test de suppression d'un utilisateur](#test-de-suppression-dun-utilisateur)
- [Test de la modification du statut d'un utilisateur](#test-de-la-modification-du-statut-dun-utilisateur)

### PARTIE 4: TESTS DES ENDPOINTS CATÉGORIES
- [Test de récupération de la liste des catégories](#test-de-récupération-de-la-liste-des-catégories)
- [Test de récupération d'une catégorie spécifique](#test-de-récupération-dune-catégorie-spécifique)
- [Test de création d'une catégorie](#test-de-création-dune-catégorie)
- [Test de mise à jour d'une catégorie](#test-de-mise-à-jour-dune-catégorie)
- [Test de suppression d'une catégorie](#test-de-suppression-dune-catégorie)

### PARTIE 5: TESTS DES ENDPOINTS DÉPENSES
- [Test de récupération de la liste des dépenses](#test-de-récupération-de-la-liste-des-dépenses)
- [Test de récupération d'une dépense spécifique](#test-de-récupération-dune-dépense-spécifique)
- [Test de création d'une dépense](#test-de-création-dune-dépense)
- [Test de mise à jour d'une dépense](#test-de-mise-à-jour-dune-dépense)
- [Test de suppression d'une dépense](#test-de-suppression-dune-dépense)

### PARTIE 6: TESTS DES ENDPOINTS REVENUS
- [Test de récupération de la liste des revenus](#test-de-récupération-de-la-liste-des-revenus)
- [Test de récupération d'un revenu spécifique](#test-de-récupération-dun-revenu-spécifique)
- [Test de création d'un revenu](#test-de-création-dun-revenu)
- [Test de mise à jour d'un revenu](#test-de-mise-à-jour-dun-revenu)
- [Test de suppression d'un revenu](#test-de-suppression-dun-revenu)

### PARTIE 7: TESTS DES ENDPOINTS PROFIL
- [Test de récupération du profil](#test-de-récupération-du-profil)
- [Test de mise à jour du profil](#test-de-mise-à-jour-du-profil)
- [Test de mise à jour de l'image de profil](#test-de-mise-à-jour-de-limage-de-profil)
- [Test de suppression du compte](#test-de-suppression-du-compte)

### PARTIE 8: TESTS AVANCÉS
- [Test des filtres et de la pagination](#test-des-filtres-et-de-la-pagination)
- [Test des permissions et rôles](#test-des-permissions-et-rôles)
- [Test des cas d'erreur](#test-des-cas-derreur)
- [Tests de charge basiques](#tests-de-charge-basiques)

### PARTIE 9: AUTOMATISATION ET INTÉGRATION CONTINUE
- [Exportation et partage des collections](#exportation-et-partage-des-collections)
- [Automatisation des tests avec Newman](#automatisation-des-tests-avec-newman)
- [Intégration dans un pipeline CI/CD](#intégration-dans-un-pipeline-cicd)

### RESSOURCES ET ANNEXES
- [Commandes et outils utiles](#commandes-et-outils-utiles)
- [Ressources complémentaires](#ressources-complémentaires)
- [Troubleshooting courant](#troubleshooting-courant)

---

# PARTIE 1: PRÉPARATION

## Introduction aux tests d'API

Les tests d'API sont essentiels pour s'assurer que notre interface de programmation fonctionne correctement. Ils permettent de vérifier :

1. **La disponibilité des endpoints** - Chaque endpoint répond-il comme prévu ?
2. **La validation des données** - Les données invalides sont-elles correctement rejetées ?
3. **Les autorisations** - Les restrictions d'accès sont-elles respectées ?
4. **Les performances** - Les temps de réponse sont-ils acceptables ?
5. **La gestion d'erreurs** - Les erreurs sont-elles gérées proprement ?

Pour une API REST, il est important de tester tous les verbes HTTP (GET, POST, PUT, PATCH, DELETE) ainsi que les codes de statut appropriés (200, 201, 400, 401, 403, 404, 422, 500, etc.).

### Types de tests d'API

| Type de test | Description | Objectif |
|-------------|-------------|----------|
| **Tests fonctionnels** | Vérifier que les endpoints répondent correctement aux requêtes valides | Confirmer que l'API fait ce qu'elle est censée faire |
| **Tests de validation** | Vérifier que l'API valide correctement les entrées | Prévenir les comportements inattendus avec des données incorrectes |
| **Tests d'autorisation** | Vérifier que seuls les utilisateurs autorisés peuvent accéder aux ressources | Sécuriser l'API contre les accès non autorisés |
| **Tests de cas limites** | Tester des cas extrêmes (valeurs nulles, très grandes, etc.) | Assurer la robustesse de l'API |
| **Tests de charge** | Tester le comportement sous forte demande | Vérifier les performances sous charge |

## Installation et configuration de Postman

[Postman](https://www.postman.com/) est l'un des outils les plus populaires pour tester les API. Voici comment l'installer et le configurer :

### 🔹 Installation de Postman

1. Rendez-vous sur le [site officiel de Postman](https://www.postman.com/downloads/)
2. Téléchargez la version correspondant à votre système d'exploitation (Windows, macOS, Linux)
3. Installez l'application en suivant les instructions
4. Créez un compte (facultatif mais recommandé pour la synchronisation)

### 🔹 Interface de Postman

L'interface de Postman se compose de plusieurs éléments :

1. **Barre latérale gauche** : Collections, environnements, et autres outils
2. **Barre d'onglets** : Affiche les requêtes ouvertes
3. **Zone de requête** : URL, méthode HTTP, paramètres, headers, etc.
4. **Zone de réponse** : Affiche les résultats de la requête
5. **Onglet Tests** : Permet d'écrire des scripts de test en JavaScript

## Création d'une collection pour les tests

Pour organiser nos tests d'API, nous allons créer une collection Postman :

1. Cliquez sur le bouton **New** ou **+**
2. Sélectionnez **Collection**
3. Nommez votre collection "Gestion Dépenses API"
4. (Optionnel) Ajoutez une description
5. Cliquez sur **Create**

### 🔹 Organisation des dossiers

Pour une meilleure organisation, structurez votre collection avec des dossiers :

1. Auth - Pour les endpoints d'authentification
2. Users - Pour les endpoints liés aux utilisateurs
3. Categories - Pour les endpoints liés aux catégories
4. Expenses - Pour les endpoints liés aux dépenses
5. Incomes - Pour les endpoints liés aux revenus
6. Profile - Pour les endpoints liés au profil utilisateur

Pour créer un dossier, faites un clic droit sur la collection et sélectionnez **Add Folder**.

## Gestion des variables d'environnement

Les variables d'environnement dans Postman permettent de stocker des valeurs réutilisables, comme l'URL de base de l'API ou les tokens d'authentification.

### 🔹 Création d'un environnement

1. Cliquez sur l'icône d'engrenage (⚙️) en haut à droite
2. Sélectionnez **Add** pour créer un nouvel environnement
3. Nommez-le "Gestion Dépenses - Local"
4. Ajoutez les variables suivantes :
   - `base_url` : `http://localhost:8000/api`
   - `token` : laisser vide (sera rempli après la connexion)
   - `admin_email` : `admin@example.com`
   - `admin_password` : `password`
   - `user_email` : `john@example.com`
   - `user_password` : `password`
5. Cliquez sur **Save**

### 🔹 Utilisation des variables

Dans vos requêtes, vous pouvez utiliser les variables avec la syntaxe `{{variable}}` :

- URL : `{{base_url}}/v1/users`
- Headers : `Authorization: Bearer {{token}}`

## Vue d'ensemble des endpoints à tester

Voici la liste complète des endpoints de notre API à tester :

### Authentification

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| POST | `/api/v1/register` | Inscription d'un nouvel utilisateur |
| POST | `/api/v1/login` | Connexion d'un utilisateur |
| POST | `/api/v1/logout` | Déconnexion de l'utilisateur |
| POST | `/api/v1/logout-all` | Déconnexion de tous les appareils |
| GET | `/api/v1/user` | Récupération des informations de l'utilisateur connecté |

### Utilisateurs

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/v1/users` | Liste de tous les utilisateurs |
| GET | `/api/v1/users/{id}` | Détails d'un utilisateur spécifique |
| POST | `/api/v1/users` | Création d'un nouvel utilisateur |
| PUT | `/api/v1/users/{id}` | Mise à jour d'un utilisateur |
| DELETE | `/api/v1/users/{id}` | Suppression d'un utilisateur |
| PATCH | `/api/v1/users/{id}/toggle-active` | Activation/désactivation d'un utilisateur |

### Catégories

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/v1/categories` | Liste de toutes les catégories |
| GET | `/api/v1/categories/{id}` | Détails d'une catégorie spécifique |
| POST | `/api/v1/categories` | Création d'une nouvelle catégorie |
| PUT | `/api/v1/categories/{id}` | Mise à jour d'une catégorie |
| DELETE | `/api/v1/categories/{id}` | Suppression d'une catégorie |

### Dépenses

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/v1/expenses` | Liste de toutes les dépenses |
| GET | `/api/v1/expenses/{id}` | Détails d'une dépense spécifique |
| POST | `/api/v1/expenses` | Création d'une nouvelle dépense |
| PUT | `/api/v1/expenses/{id}` | Mise à jour d'une dépense |
| DELETE | `/api/v1/expenses/{id}` | Suppression d'une dépense |

### Revenus

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/v1/incomes` | Liste de tous les revenus |
| GET | `/api/v1/incomes/{id}` | Détails d'un revenu spécifique |
| POST | `/api/v1/incomes` | Création d'un nouveau revenu |
| PUT | `/api/v1/incomes/{id}` | Mise à jour d'un revenu |
| DELETE | `/api/v1/incomes/{id}` | Suppression d'un revenu |

### Profil

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/v1/profile` | Récupération du profil de l'utilisateur |
| PUT | `/api/v1/profile` | Mise à jour du profil de l'utilisateur |
| POST | `/api/v1/profile/image` | Mise à jour de l'image de profil |
| DELETE | `/api/v1/profile` | Suppression du compte utilisateur |

Nous allons maintenant tester chacun de ces endpoints de manière systématique.

# PARTIE 2: TESTS DES ENDPOINTS D'AUTHENTIFICATION

## Test de l'inscription (Register)

### 🔹 Configuration du test

**Méthode :** POST  
**URL :** `{{base_url}}/v1/register`  
**Headers :**
- Content-Type: application/json

**Body (JSON) :**
```json
{
    "name": "Test User",
    "email": "testuser@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "device_name": "PostmanTest"
}
```

### 🔹 Test et validation

**Ce que nous testons :**
- L'inscription d'un nouvel utilisateur
- La réception d'un token d'authentification
- L'attribution du rôle "user" par défaut
- Le format de la réponse

**Comment procéder :**
1. Envoyez la requête avec les données ci-dessus
2. Vérifiez que le code de statut est `201 Created`
3. Vérifiez que la réponse contient un token et les informations de l'utilisateur
4. Vérifiez que l'utilisateur a bien le rôle "user"

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 201", function () {
    pm.response.to.have.status(201);
});

pm.test("Response contains token", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.token).to.exist;
    pm.expect(jsonData.token.length).to.be.greaterThan(20);
    
    // Stockage du token pour les futures requêtes
    pm.environment.set("token", jsonData.token);
});

pm.test("User has correct data", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.user).to.exist;
    pm.expect(jsonData.user.name).to.eql("Test User");
    pm.expect(jsonData.user.email).to.eql("testuser@example.com");
    pm.expect(jsonData.user.is_active).to.be.true;
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Échec de validation d'email unique**  
- **Symptôme** :# 🧪 Tests de l'API avec Postman

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Échec de validation d'email unique**  
- **Symptôme** : Erreur 422 avec message "The email has already been taken"
- **Résolution** : Utilisez un email unique pour chaque test ou supprimez l'utilisateur de test avant de relancer

**Bug potentiel #2 : Problème avec la confirmation de mot de passe**  
- **Symptôme** : Erreur 422 avec message "The password confirmation does not match"
- **Résolution** : Assurez-vous que les champs `password` et `password_confirmation` ont des valeurs identiques

## Test de la connexion (Login)

### 🔹 Configuration du test

**Méthode :** POST  
**URL :** `{{base_url}}/v1/login`  
**Headers :**
- Content-Type: application/json

**Body (JSON) :**
```json
{
    "email": "{{admin_email}}",
    "password": "{{admin_password}}",
    "device_name": "PostmanTest"
}
```

### 🔹 Test et validation

**Ce que nous testons :**
- La connexion d'un utilisateur existant
- La réception d'un token d'authentification
- L'accès aux données de l'utilisateur

**Comment procéder :**
1. Envoyez la requête avec les identifiants d'un administrateur
2. Vérifiez que le code de statut est `200 OK`
3. Vérifiez que la réponse contient un token et les informations de l'utilisateur
4. Stockez le token pour les futures requêtes

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response contains token", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.token).to.exist;
    pm.expect(jsonData.token.length).to.be.greaterThan(20);
    
    // Stockage du token pour les futures requêtes
    pm.environment.set("token", jsonData.token);
});

pm.test("User has admin role", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.user).to.exist;
    pm.expect(jsonData.user.roles).to.include("admin");
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Identifiants incorrects**  
- **Symptôme** : Erreur 422 avec message "The provided credentials are incorrect"
- **Résolution** : Vérifiez que les variables d'environnement pour email et mot de passe sont correctes

**Bug potentiel #2 : Utilisateur inactif**  
- **Symptôme** : Erreur 403 avec message sur la désactivation du compte
- **Résolution** : Assurez-vous que l'utilisateur est actif dans la base de données

## Test de la déconnexion (Logout)

### 🔹 Configuration du test

**Méthode :** POST  
**URL :** `{{base_url}}/v1/logout`  
**Headers :**
- Content-Type: application/json
- Authorization: Bearer {{token}}

**Body (JSON) :** *Aucun*

### 🔹 Test et validation

**Ce que nous testons :**
- La déconnexion d'un utilisateur (révocation du token)
- Le message de confirmation

**Comment procéder :**
1. Assurez-vous d'avoir un token valide (connectez-vous si nécessaire)
2. Envoyez la requête de déconnexion
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez que la réponse contient un message de succès

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Logout successful message", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.message).to.exist;
    pm.expect(jsonData.message).to.include("Successfully logged out");
});

// Suppression du token de l'environnement (optionnel)
// pm.environment.unset("token");
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Token manquant ou invalide**  
- **Symptôme** : Erreur 401 "Unauthenticated"
- **Résolution** : Assurez-vous d'avoir un token valide dans les variables d'environnement

**Bug potentiel #2 : Multiple déconnexions**  
- **Symptôme** : La requête fonctionne mais le token est déjà invalide
- **Résolution** : Ce n'est pas un bug, mais notez qu'un token révoqué ne peut pas être utilisé à nouveau

## Test de la récupération du profil utilisateur

### 🔹 Configuration du test

**Méthode :** GET  
**URL :** `{{base_url}}/v1/user`  
**Headers :**
- Authorization: Bearer {{token}}

### 🔹 Test et validation

**Ce que nous testons :**
- L'accès aux informations de l'utilisateur authentifié
- La correspondance des données avec l'utilisateur connecté

**Comment procéder :**
1. Connectez-vous pour obtenir un token valide
2. Envoyez la requête `GET /api/v1/user`
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez que les données de l'utilisateur sont correctes

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("User data is present", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('id');
    pm.expect(jsonData).to.have.property('name');
    pm.expect(jsonData).to.have.property('email');
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Authentification expirée**  
- **Symptôme** : Erreur 401 "Unauthenticated"
- **Résolution** : Reconnectez-vous pour obtenir un nouveau token

# PARTIE 3: TESTS DES ENDPOINTS UTILISATEURS

## Test de récupération de la liste des utilisateurs

### 🔹 Configuration du test

**Méthode :** GET  
**URL :** `{{base_url}}/v1/users`  
**Headers :**
- Authorization: Bearer {{token}}

**Paramètres (optionnels) :**
- `search` : Filtre de recherche
- `status` : Filtre par statut (active/inactive)
- `role` : Filtre par rôle
- `sort` : Champ de tri
- `direction` : Sens du tri (asc/desc)
- `per_page` : Nombre d'éléments par page
- `page` : Numéro de page
- `include` : Relations à inclure (roles,expenses,incomes)

### 🔹 Test et validation

**Ce que nous testons :**
- L'accès à la liste des utilisateurs (réservé aux admins)
- La pagination des résultats
- Le filtrage et le tri
- L'inclusion des relations

**Comment procéder :**
1. Connectez-vous en tant qu'administrateur
2. Envoyez la requête `GET /api/v1/users`
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez la structure de la réponse paginée
5. Testez différentes options de filtrage et tri

**Tests avec différents paramètres :**
1. Liste simple : `GET /api/v1/users`
2. Avec pagination : `GET /api/v1/users?per_page=5&page=1`
3. Avec filtre : `GET /api/v1/users?status=active`
4. Avec recherche : `GET /api/v1/users?search=admin`
5. Avec tri : `GET /api/v1/users?sort=created_at&direction=desc`
6. Avec inclusion : `GET /api/v1/users?include=roles`

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response has pagination structure", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('data');
    pm.expect(jsonData).to.have.property('links');
    pm.expect(jsonData).to.have.property('meta');
    pm.expect(jsonData.meta).to.have.property('current_page');
    pm.expect(jsonData.meta).to.have.property('total');
});

pm.test("Users have the required fields", function () {
    var jsonData = pm.response.json();
    if (jsonData.data.length > 0) {
        pm.expect(jsonData.data[0]).to.have.property('id');
        pm.expect(jsonData.data[0]).to.have.property('name');
        pm.expect(jsonData.data[0]).to.have.property('email');
        pm.expect(jsonData.data[0]).to.have.property('is_active');
    }
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Accès non autorisé**  
- **Symptôme** : Erreur 403 "This action is unauthorized"
- **Résolution** : Assurez-vous d'être connecté avec un compte administrateur

**Bug potentiel #2 : Paramètres de tri invalides**  
- **Symptôme** : Le tri ne fonctionne pas comme prévu
- **Résolution** : Vérifiez que le champ de tri est dans la liste des champs autorisés

## Test de récupération d'un utilisateur spécifique

### 🔹 Configuration du test

**Méthode :** GET  
**URL :** `{{base_url}}/v1/users/:id`  
**Headers :**
- Authorization: Bearer {{token}}

**Paramètres (optionnels) :**
- `include` : Relations à inclure (roles,expenses,incomes)

### 🔹 Test et validation

**Ce que nous testons :**
- L'accès aux détails d'un utilisateur spécifique
- L'inclusion optionnelle des relations

**Comment procéder :**
1. Connectez-vous en tant qu'administrateur
2. Obtenez l'ID d'un utilisateur existant
3. Envoyez la requête `GET /api/v1/users/:id`
4. Vérifiez que le code de statut est `200 OK`
5. Vérifiez que les données de l'utilisateur sont correctes

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("User data is correct", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('id');
    pm.expect(jsonData).to.have.property('name');
    pm.expect(jsonData).to.have.property('email');
    pm.expect(jsonData).to.have.property('is_active');
});

pm.test("Included relationships are present when requested", function () {
    // Vérifiez uniquement si le paramètre include a été utilisé
    if (pm.request.url.query.has('include')) {
        var jsonData = pm.response.json();
        var includes = pm.request.url.query.get('include').split(',');
        
        if (includes.includes('roles')) {
            pm.expect(jsonData).to.have.property('roles');
        }
        
        if (includes.includes('expenses')) {
            pm.expect(jsonData).to.have.property('expenses');
        }
        
        if (includes.includes('incomes')) {
            pm.expect(jsonData).to.have.property('incomes');
        }
    }
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Utilisateur inexistant**  
- **Symptôme** : Erreur 404 "Not Found"
- **Résolution** : Vérifiez que l'ID de l'utilisateur est correct

**Bug potentiel #2 : Inclusion de relations inexistantes**  
- **Symptôme** : Les relations demandées ne sont pas incluses
- **Résolution** : Vérifiez que le paramètre `include` contient uniquement des relations valides

## Test de création d'un utilisateur

### 🔹 Configuration du test

**Méthode :** POST  
**URL :** `{{base_url}}/v1/users`  
**Headers :**
- Content-Type: application/json
- Authorization: Bearer {{token}}

**Body (JSON) :**
```json
{
    "name": "New Admin User",
    "email": "newa
    # 🧪 Tests de l'API avec Postman

[⬅️ Étape précédente : Création des contrôleurs d'API](08-controllers-api.md)  
[➡️ Étape suivante : Sécurisation de l'API avec JWT](10-auth-jwt.md)

---

## 📑 Table des matières

### PARTIE 1: PRÉPARATION
- [Introduction aux tests d'API](#introduction-aux-tests-dapi)
- [Installation et configuration de Postman](#installation-et-configuration-de-postman)
- [Création d'une collection pour les tests](#création-dune-collection-pour-les-tests)
- [Gestion des variables d'environnement](#gestion-des-variables-denvironnement)
- [Vue d'ensemble des endpoints à tester](#vue-densemble-des-endpoints-à-tester)

### PARTIE 2: TESTS DES ENDPOINTS D'AUTHENTIFICATION
- [Test de l'inscription (Register)](#test-de-linscription-register)
- [Test de la connexion (Login)](#test-de-la-connexion-login)
- [Test de la déconnexion (Logout)](#test-de-la-déconnexion-logout)
- [Test de la récupération du profil utilisateur](#test-de-la-récupération-du-profil-utilisateur)

### PARTIE 3: TESTS DES ENDPOINTS UTILISATEURS
- [Test de récupération de la liste des utilisateurs](#test-de-récupération-de-la-liste-des-utilisateurs)
- [Test de récupération d'un utilisateur spécifique](#test-de-récupération-dun-utilisateur-spécifique)
- [Test de création d'un utilisateur](#test-de-création-dun-utilisateur)
- [Test de mise à jour d'un utilisateur](#test-de-mise-à-jour-dun-utilisateur)
- [Test de suppression d'un utilisateur](#test-de-suppression-dun-utilisateur)
- [Test de la modification du statut d'un utilisateur](#test-de-la-modification-du-statut-dun-utilisateur)

### PARTIE 4: TESTS DES ENDPOINTS CATÉGORIES
- [Test de récupération de la liste des catégories](#test-de-récupération-de-la-liste-des-catégories)
- [Test de récupération d'une catégorie spécifique](#test-de-récupération-dune-catégorie-spécifique)
- [Test de création d'une catégorie](#test-de-création-dune-catégorie)
- [Test de mise à jour d'une catégorie](#test-de-mise-à-jour-dune-catégorie)
- [Test de suppression d'une catégorie](#test-de-suppression-dune-catégorie)

### PARTIE 5: TESTS DES ENDPOINTS DÉPENSES
- [Test de récupération de la liste des dépenses](#test-de-récupération-de-la-liste-des-dépenses)
- [Test de récupération d'une dépense spécifique](#test-de-récupération-dune-dépense-spécifique)
- [Test de création d'une dépense](#test-de-création-dune-dépense)
- [Test de mise à jour d'une dépense](#test-de-mise-à-jour-dune-dépense)
- [Test de suppression d'une dépense](#test-de-suppression-dune-dépense)

### PARTIE 6: TESTS DES ENDPOINTS REVENUS
- [Test de récupération de la liste des revenus](#test-de-récupération-de-la-liste-des-revenus)
- [Test de récupération d'un revenu spécifique](#test-de-récupération-dun-revenu-spécifique)
- [Test de création d'un revenu](#test-de-création-dun-revenu)
- [Test de mise à jour d'un revenu](#test-de-mise-à-jour-dun-revenu)
- [Test de suppression d'un revenu](#test-de-suppression-dun-revenu)

### PARTIE 7: TESTS DES ENDPOINTS PROFIL
- [Test de récupération du profil](#test-de-récupération-du-profil)
- [Test de mise à jour du profil](#test-de-mise-à-jour-du-profil)
- [Test de mise à jour de l'image de profil](#test-de-mise-à-jour-de-limage-de-profil)
- [Test de suppression du compte](#test-de-suppression-du-compte)

### PARTIE 8: TESTS AVANCÉS
- [Test des filtres et de la pagination](#test-des-filtres-et-de-la-pagination)
- [Test des permissions et rôles](#test-des-permissions-et-rôles)
- [Test des cas d'erreur](#test-des-cas-derreur)
- [Tests de charge basiques](#tests-de-charge-basiques)

### PARTIE 9: AUTOMATISATION ET INTÉGRATION CONTINUE
- [Exportation et partage des collections](#exportation-et-partage-des-collections)
- [Automatisation des tests avec Newman](#automatisation-des-tests-avec-newman)
- [Intégration dans un pipeline CI/CD](#intégration-dans-un-pipeline-cicd)

### RESSOURCES ET ANNEXES
- [Commandes et outils utiles](#commandes-et-outils-utiles)
- [Ressources complémentaires](#ressources-complémentaires)
- [Troubleshooting courant](#troubleshooting-courant)

---

# PARTIE 1: PRÉPARATION

## Introduction aux tests d'API

Les tests d'API sont essentiels pour s'assurer que notre interface de programmation fonctionne correctement. Ils permettent de vérifier :

1. **La disponibilité des endpoints** - Chaque endpoint répond-il comme prévu ?
2. **La validation des données** - Les données invalides sont-elles correctement rejetées ?
3. **Les autorisations** - Les restrictions d'accès sont-elles respectées ?
4. **Les performances** - Les temps de réponse sont-ils acceptables ?
5. **La gestion d'erreurs** - Les erreurs sont-elles gérées proprement ?

Pour une API REST, il est important de tester tous les verbes HTTP (GET, POST, PUT, PATCH, DELETE) ainsi que les codes de statut appropriés (200, 201, 400, 401, 403, 404, 422, 500, etc.).

### Types de tests d'API

| Type de test | Description | Objectif |
|-------------|-------------|----------|
| **Tests fonctionnels** | Vérifier que les endpoints répondent correctement aux requêtes valides | Confirmer que l'API fait ce qu'elle est censée faire |
| **Tests de validation** | Vérifier que l'API valide correctement les entrées | Prévenir les comportements inattendus avec des données incorrectes |
| **Tests d'autorisation** | Vérifier que seuls les utilisateurs autorisés peuvent accéder aux ressources | Sécuriser l'API contre les accès non autorisés |
| **Tests de cas limites** | Tester des cas extrêmes (valeurs nulles, très grandes, etc.) | Assurer la robustesse de l'API |
| **Tests de charge** | Tester le comportement sous forte demande | Vérifier les performances sous charge |

## Installation et configuration de Postman

[Postman](https://www.postman.com/) est l'un des outils les plus populaires pour tester les API. Voici comment l'installer et le configurer :

### 🔹 Installation de Postman

1. Rendez-vous sur le [site officiel de Postman](https://www.postman.com/downloads/)
2. Téléchargez la version correspondant à votre système d'exploitation (Windows, macOS, Linux)
3. Installez l'application en suivant les instructions
4. Créez un compte (facultatif mais recommandé pour la synchronisation)

### 🔹 Interface de Postman

L'interface de Postman se compose de plusieurs éléments :

1. **Barre latérale gauche** : Collections, environnements, et autres outils
2. **Barre d'onglets** : Affiche les requêtes ouvertes
3. **Zone de requête** : URL, méthode HTTP, paramètres, headers, etc.
4. **Zone de réponse** : Affiche les résultats de la requête
5. **Onglet Tests** : Permet d'écrire des scripts de test en JavaScript

## Création d'une collection pour les tests

Pour organiser nos tests d'API, nous allons créer une collection Postman :

1. Cliquez sur le bouton **New** ou **+**
2. Sélectionnez **Collection**
3. Nommez votre collection "Gestion Dépenses API"
4. (Optionnel) Ajoutez une description
5. Cliquez sur **Create**

### 🔹 Organisation des dossiers

Pour une meilleure organisation, structurez votre collection avec des dossiers :

1. Auth - Pour les endpoints d'authentification
2. Users - Pour les endpoints liés aux utilisateurs
3. Categories - Pour les endpoints liés aux catégories
4. Expenses - Pour les endpoints liés aux dépenses
5. Incomes - Pour les endpoints liés aux revenus
6. Profile - Pour les endpoints liés au profil utilisateur

Pour créer un dossier, faites un clic droit sur la collection et sélectionnez **Add Folder**.

## Gestion des variables d'environnement

Les variables d'environnement dans Postman permettent de stocker des valeurs réutilisables, comme l'URL de base de l'API ou les tokens d'authentification.

### 🔹 Création d'un environnement

1. Cliquez sur l'icône d'engrenage (⚙️) en haut à droite
2. Sélectionnez **Add** pour créer un nouvel environnement
3. Nommez-le "Gestion Dépenses - Local"
4. Ajoutez les variables suivantes :
   - `base_url` : `http://localhost:8000/api`
   - `token` : laisser vide (sera rempli après la connexion)
   - `admin_email` : `admin@example.com`
   - `admin_password` : `password`
   - `user_email` : `john@example.com`
   - `user_password` : `password`
5. Cliquez sur **Save**

### 🔹 Utilisation des variables

Dans vos requêtes, vous pouvez utiliser les variables avec la syntaxe `{{variable}}` :

- URL : `{{base_url}}/v1/users`
- Headers : `Authorization: Bearer {{token}}`

## Vue d'ensemble des endpoints à tester

Voici la liste complète des endpoints de notre API à tester :

### Authentification

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| POST | `/api/v1/register` | Inscription d'un nouvel utilisateur |
| POST | `/api/v1/login` | Connexion d'un utilisateur |
| POST | `/api/v1/logout` | Déconnexion de l'utilisateur |
| POST | `/api/v1/logout-all` | Déconnexion de tous les appareils |
| GET | `/api/v1/user` | Récupération des informations de l'utilisateur connecté |

### Utilisateurs

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/v1/users` | Liste de tous les utilisateurs |
| GET | `/api/v1/users/{id}` | Détails d'un utilisateur spécifique |
| POST | `/api/v1/users` | Création d'un nouvel utilisateur |
| PUT | `/api/v1/users/{id}` | Mise à jour d'un utilisateur |
| DELETE | `/api/v1/users/{id}` | Suppression d'un utilisateur |
| PATCH | `/api/v1/users/{id}/toggle-active` | Activation/désactivation d'un utilisateur |

### Catégories

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/v1/categories` | Liste de toutes les catégories |
| GET | `/api/v1/categories/{id}` | Détails d'une catégorie spécifique |
| POST | `/api/v1/categories` | Création d'une nouvelle catégorie |
| PUT | `/api/v1/categories/{id}` | Mise à jour d'une catégorie |
| DELETE | `/api/v1/categories/{id}` | Suppression d'une catégorie |

### Dépenses

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/v1/expenses` | Liste de toutes les dépenses |
| GET | `/api/v1/expenses/{id}` | Détails d'une dépense spécifique |
| POST | `/api/v1/expenses` | Création d'une nouvelle dépense |
| PUT | `/api/v1/expenses/{id}` | Mise à jour d'une dépense |
| DELETE | `/api/v1/expenses/{id}` | Suppression d'une dépense |

### Revenus

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/v1/incomes` | Liste de tous les revenus |
| GET | `/api/v1/incomes/{id}` | Détails d'un revenu spécifique |
| POST | `/api/v1/incomes` | Création d'un nouveau revenu |
| PUT | `/api/v1/incomes/{id}` | Mise à jour d'un revenu |
| DELETE | `/api/v1/incomes/{id}` | Suppression d'un revenu |

### Profil

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/v1/profile` | Récupération du profil de l'utilisateur |
| PUT | `/api/v1/profile` | Mise à jour du profil de l'utilisateur |
| POST | `/api/v1/profile/image` | Mise à jour de l'image de profil |
| DELETE | `/api/v1/profile` | Suppression du compte utilisateur |

Nous allons maintenant tester chacun de ces endpoints de manière systématique.

# PARTIE 2: TESTS DES ENDPOINTS D'AUTHENTIFICATION

## Test de l'inscription (Register)

### 🔹 Configuration du test

**Méthode :** POST  
**URL :** `{{base_url}}/v1/register`  
**Headers :**
- Content-Type: application/json

**Body (JSON) :**
```json
{
    "name": "Test User",
    "email": "testuser@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "device_name": "PostmanTest"
}
```

### 🔹 Test et validation

**Ce que nous testons :**
- L'inscription d'un nouvel utilisateur
- La réception d'un token d'authentification
- L'attribution du rôle "user" par défaut
- Le format de la réponse

**Comment procéder :**
1. Envoyez la requête avec les données ci-dessus
2. Vérifiez que le code de statut est `201 Created`
3. Vérifiez que la réponse contient un token et les informations de l'utilisateur
4. Vérifiez que l'utilisateur a bien le rôle "user"

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 201", function () {
    pm.response.to.have.status(201);
});

pm.test("Response contains token", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.token).to.exist;
    pm.expect(jsonData.token.length).to.be.greaterThan(20);
    
    // Stockage du token pour les futures requêtes
    pm.environment.set("token", jsonData.token);
});

pm.test("User has correct data", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.user).to.exist;
    pm.expect(jsonData.user.name).to.eql("Test User");
    pm.expect(jsonData.user.email).to.eql("testuser@example.com");
    pm.expect(jsonData.user.is_active).to.be.true;
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Échec de validation d'email unique**  
- **Symptôme** : Erreur 422 avec message "The email has already been taken"
- **Résolution** : Utilisez un email unique pour chaque test ou supprimez l'utilisateur de test avant de relancer

**Bug potentiel #2 : Problème avec la confirmation de mot de passe**  
- **Symptôme** : Erreur 422 avec message "The password confirmation does not match"
- **Résolution** : Assurez-vous que les champs `password` et `password_confirmation` ont des valeurs identiques

## Test de la connexion (Login)

### 🔹 Configuration du test

**Méthode :** POST  
**URL :** `{{base_url}}/v1/login`  
**Headers :**
- Content-Type: application/json

**Body (JSON) :**
```json
{
    "email": "{{admin_email}}",
    "password": "{{admin_password}}",
    "device_name": "PostmanTest"
}
```

### 🔹 Test et validation

**Ce que nous testons :**
- La connexion d'un utilisateur existant
- La réception d'un token d'authentification
- L'accès aux données de l'utilisateur

**Comment procéder :**
1. Envoyez la requête avec les identifiants d'un administrateur
2. Vérifiez que le code de statut est `200 OK`
3. Vérifiez que la réponse contient un token et les informations de l'utilisateur
4. Stockez le token pour les futures requêtes

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response contains token", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.token).to.exist;
    pm.expect(jsonData.token.length).to.be.greaterThan(20);
    
    // Stockage du token pour les futures requêtes
    pm.environment.set("token", jsonData.token);
});

pm.test("User has admin role", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.user).to.exist;
    pm.expect(jsonData.user.roles).to.include("admin");
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Identifiants incorrects**  
- **Symptôme** : Erreur 422 avec message "The provided credentials are incorrect"
- **Résolution** : Vérifiez que les variables d'environnement pour email et mot de passe sont correctes

**Bug potentiel #2 : Utilisateur inactif**  
- **Symptôme** : Erreur 403 avec message sur la désactivation du compte
- **Résolution** : Assurez-vous que l'utilisateur est actif dans la base de données

## Test de la déconnexion (Logout)

### 🔹 Configuration du test

**Méthode :** POST  
**URL :** `{{base_url}}/v1/logout`  
**Headers :**
- Content-Type: application/json
- Authorization: Bearer {{token}}

**Body (JSON) :** *Aucun*

### 🔹 Test et validation

**Ce que nous testons :**
- La déconnexion d'un utilisateur (révocation du token)
- Le message de confirmation

**Comment procéder :**
1. Assurez-vous d'avoir un token valide (connectez-vous si nécessaire)
2. Envoyez la requête de déconnexion
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez que la réponse contient un message de succès

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Logout successful message", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.message).to.exist;
    pm.expect(jsonData.message).to.include("Successfully logged out");
});

// Suppression du token de l'environnement (optionnel)
// pm.environment.unset("token");
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Token manquant ou invalide**  
- **Symptôme** : Erreur 401 "Unauthenticated"
- **Résolution** : Assurez-vous d'avoir un token valide dans les variables d'environnement

**Bug potentiel #2 : Multiple déconnexions**  
- **Symptôme** : La requête fonctionne mais le token est déjà invalide
- **Résolution** : Ce n'est pas un bug, mais notez qu'un token révoqué ne peut pas être utilisé à nouveau

## Test de la récupération du profil utilisateur

### 🔹 Configuration du test

**Méthode :** GET  
**URL :** `{{base_url}}/v1/user`  
**Headers :**
- Authorization: Bearer {{token}}

### 🔹 Test et validation

**Ce que nous testons :**
- L'accès aux informations de l'utilisateur authentifié
- La correspondance des données avec l'utilisateur connecté

**Comment procéder :**
1. Connectez-vous pour obtenir un token valide
2. Envoyez la requête `GET /api/v1/user`
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez que les données de l'utilisateur sont correctes

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("User data is present", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('id');
    pm.expect(jsonData).to.have.property('name');
    pm.expect(jsonData).to.have.property('email');
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Authentification expirée**  
- **Symptôme** : Erreur 401 "Unauthenticated"
- **Résolution** : Reconnectez-vous pour obtenir un nouveau token

# PARTIE 3: TESTS DES ENDPOINTS UTILISATEURS

## Test de récupération de la liste des utilisateurs

### 🔹 Configuration du test

**Méthode :** GET  
**URL :** `{{base_url}}/v1/users`  
**Headers :**
- Authorization: Bearer {{token}}

**Paramètres (optionnels) :**
- `search` : Filtre de recherche
- `status` : Filtre par statut (active/inactive)
- `role` : Filtre par rôle
- `sort` : Champ de tri
- `direction` : Sens du tri (asc/desc)
- `per_page` : Nombre d'éléments par page
- `page` : Numéro de page
- `include` : Relations à inclure (roles,expenses,incomes)

### 🔹 Test et validation

**Ce que nous testons :**
- L'accès à la liste des utilisateurs (réservé aux admins)
- La pagination des résultats
- Le filtrage et le tri
- L'inclusion des relations

**Comment procéder :**
1. Connectez-vous en tant qu'administrateur
2. Envoyez la requête `GET /api/v1/users`
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez la structure de la réponse paginée
5. Testez différentes options de filtrage et tri

**Tests avec différents paramètres :**
1. Liste simple : `GET /api/v1/users`
2. Avec pagination : `GET /api/v1/users?per_page=5&page=1`
3. Avec filtre : `GET /api/v1/users?status=active`
4. Avec recherche : `GET /api/v1/users?search=admin`
5. Avec tri : `GET /api/v1/users?sort=created_at&direction=desc`
6. Avec inclusion : `GET /api/v1/users?include=roles`

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response has pagination structure", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('data');
    pm.expect(jsonData).to.have.property('links');
    pm.expect(jsonData).to.have.property('meta');
    pm.expect(jsonData.meta).to.have.property('current_page');
    pm.expect(jsonData.meta).to.have.property('total');
});

pm.test("Users have the required fields", function () {
    var jsonData = pm.response.json();
    if (jsonData.data.length > 0) {
        pm.expect(jsonData.data[0]).to.have.property('id');
        pm.expect(jsonData.data[0]).to.have.property('name');
        pm.expect(jsonData.data[0]).to.have.property('email');
        pm.expect(jsonData.data[0]).to.have.property('is_active');
    }
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Accès non autorisé**  
- **Symptôme** : Erreur 403 "This action is unauthorized"
- **Résolution** : Assurez-vous d'être connecté avec un compte administrateur

**Bug potentiel #2 : Paramètres de tri invalides**  
- **Symptôme** : Le tri ne fonctionne pas comme prévu
- **Résolution** : Vérifiez que le champ de tri est dans la liste des champs autorisés

## Test de récupération d'un utilisateur spécifique

### 🔹 Configuration du test

**Méthode :** GET  
**URL :** `{{base_url}}/v1/users/:id`  
**Headers :**
- Authorization: Bearer {{token}}

**Paramètres (optionnels) :**
- `include` : Relations à inclure (roles,expenses,incomes)

### 🔹 Test et validation

**Ce que nous testons :**
- L'accès aux détails d'un utilisateur spécifique
- L'inclusion optionnelle des relations

**Comment procéder :**
1. Connectez-vous en tant qu'administrateur
2. Obtenez l'ID d'un utilisateur existant
3. Envoyez la requête `GET /api/v1/users/:id`
4. Vérifiez que le code de statut est `200 OK`
5. Vérifiez que les données de l'utilisateur sont correctes

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("User data is correct", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('id');
    pm.expect(jsonData).to.have.property('name');
    pm.expect(jsonData).to.have.property('email');
    pm.expect(jsonData).to.have.property('is_active');
});

pm.test("Included relationships are present when requested", function () {
    // Vérifiez uniquement si le paramètre include a été utilisé
    if (pm.request.url.query.has('include')) {
        var jsonData = pm.response.json();
        var includes = pm.request.url.query.get('include').split(',');
        
        if (includes.includes('roles')) {
            pm.expect(jsonData).to.have.property('roles');
        }
        
        if (includes.includes('expenses')) {
            pm.expect(jsonData).to.have.property('expenses');
        }
        
        if (includes.includes('incomes')) {
            pm.expect(jsonData).to.have.property('incomes');
        }
    }
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Utilisateur inexistant**  
- **Symptôme** : Erreur 404 "Not Found"
- **Résolution** : Vérifiez que l'ID de l'utilisateur est correct

**Bug potentiel #2 : Inclusion de relations inexistantes**  
- **Symptôme** : Les relations demandées ne sont pas incluses
- **Résolution** : Vérifiez que le paramètre `include` contient uniquement des relations valides

## Test de création d'un utilisateur

### 🔹 Configuration du test

**Méthode :** POST  
**URL :** `{{base_url}}/v1/users`  
**Headers :**
- Content-Type: application/json
- Authorization: Bearer {{token}}

**Body (JSON) :**
```json
{
    "name": "New Admin User",
    "email": "newadmin@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "admin"
}
```

### 🔹 Test et validation

**Ce que nous testons :**
- La création d'un nouvel utilisateur par un administrateur
- L'attribution d'un rôle spécifique
- Le format et la validation des données

**Comment procéder :**
1. Connectez-vous en tant qu'administrateur
2. Envoyez la requête avec les données d'un nouvel utilisateur
3. Vérifiez que le code de statut est `201 Created`
4. Vérifiez que les données de l'utilisateur créé sont correctes
5. Vérifiez que le rôle a été correctement attribué

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 201", function () {
    pm.response.to.have.status(201);
});

pm.test("User created with correct data", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('id');
    pm.expect(jsonData.name).to.eql("New Admin User");
    pm.expect(jsonData.email).to.eql("newadmin@example.com");
    pm.expect(jsonData.is_active).to.be.true;
    
    // Stockage de l'ID pour les tests suivants
    if (jsonData.id) {
        pm.environment.set("new_user_id", jsonData.id);
    }
});

pm.test("User has admin role", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.roles).to.include("admin");
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Validation de l'email**  
- **Symptôme** : Erreur 422 "The email has already been taken"
- **Résolution** : Utilisez un email unique pour chaque test

**Bug potentiel #2 : Rôle inexistant**  
- **Symptôme** : Erreur 422 "The selected role is invalid"
- **Résolution** : Vérifiez que le rôle existe dans la base de données

## Test de mise à jour d'un utilisateur

### 🔹 Configuration du test

**Méthode :** PUT  
**URL :** `{{base_url}}/v1/users/:id`  
**Headers :**
- Content-Type: application/json
- Authorization: Bearer {{token}}

**Body (JSON) :**
```json
{
    "name": "Updated User Name",
    "email": "updated@example.com",
    "role": "user"
}
```

### 🔹 Test et validation

**Ce que nous testons :**
- La mise à jour des informations d'un utilisateur
- La possibilité de modifier le rôle
- La validation des données

**Comment procéder :**
1. Utilisez l'ID de l'utilisateur créé précédemment
2. Envoyez la requête avec les données mises à jour
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez que les données ont été correctement mises à jour

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("User updated with correct data", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('id');
    pm.expect(jsonData.name).to.eql("Updated User Name");
    pm.expect(jsonData.email).to.eql("updated@example.com");
});

pm.test("User role has been changed", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.roles).to.include("user");
    pm.expect(jsonData.roles).to.not.include("admin");
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Email déjà utilisé**  
- **Symptôme** : Erreur 422 "The email has already been taken"
- **Résolution** : Utilisez un email unique ou conservez l'email actuel

**Bug potentiel #2 : Tentative de modification par un non-administrateur**  
- **Symptôme** : Erreur 403 "This action is unauthorized"
- **Résolution** : Assurez-vous d'être connecté avec un compte administrateur

## Test de suppression d'un utilisateur

### 🔹 Configuration du test

**Méthode :** DELETE  
**URL :** `{{base_url}}/v1/users/:id`  
**Headers :**
- Authorization: Bearer {{token}}

### 🔹 Test et validation

**Ce que nous testons :**
- La suppression d'un utilisateur
- Les autorisations pour cette action
- La réponse vide avec code 204

**Comment procéder :**
1. Utilisez l'ID de l'utilisateur mis à jour précédemment
2. Envoyez la requête DELETE
3. Vérifiez que le code de statut est `204 No Content`
4. Vérifiez que l'utilisateur ne peut plus être récupéré (404)

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 204", function () {
    pm.response.to.have.status(204);
});

// Créez une requête suivante pour vérifier que l'utilisateur n'existe plus
// GET /api/v1/users/:id devrait retourner 404
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Tentative de suppression de son propre compte**  
- **Symptôme** : Erreur 403 "You cannot delete your own account"
- **Résolution** : Utilisez un ID d'utilisateur différent de celui connecté

**Bug potentiel #2 : Utilisateur inexistant**  
- **Symptôme** : Erreur 404 "Not Found"
- **Résolution** : Vérifiez que l'ID utilisateur existe

## Test de la modification du statut d'un utilisateur

### 🔹 Configuration du test

**Méthode :** PATCH  
**URL :** `{{base_url}}/v1/users/:id/toggle-active`  
**Headers :**
- Authorization: Bearer {{token}}

### 🔹 Test et validation

**Ce que nous testons :**
- L'activation/désactivation d'un utilisateur
- Les autorisations pour cette action
- Le changement effectif du statut

**Comment procéder :**
1. Créez un nouvel utilisateur pour ce test
2. Notez son statut initial (généralement actif)
3. Envoyez la requête PATCH pour changer son statut
4. Vérifiez que le code de statut est `200 OK`
5. Vérifiez que le statut a été inversé

**Scripts Postman pour automatiser la validation :**
```javascript
// Stockez le statut initial
var initialStatus = null;

// Premier test pour récupérer le statut
pm.sendRequest({
    url: pm.environment.get("base_url") + "/v1/users/" + pm.environment.get("test_user_id"),
    method: 'GET',
    header: {
        'Authorization': 'Bearer ' + pm.environment.get("token")
    }
}, function (err, res) {
    if (!err) {
        initialStatus = res.json().is_active;
        
        // Maintenant exécutez le toggle
        pm.test("Status code is 200", function () {
            pm.response.to.have.status(200);
        });
        
        pm.test("User status has been toggled", function () {
            var jsonData = pm.response.json();
            pm.expect(jsonData.is_active).to.equal(!initialStatus);
        });
    }
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Tentative de bloquer son propre compte**  
- **Symptôme** : Erreur 403 "You cannot block your own account"
- **Résolution** : Utilisez un ID d'utilisateur différent de celui connecté

**Bug potentiel #2 : Tentative de bloquer l'administrateur principal**  
- **Symptôme** : Erreur 403 "Cannot modify the status of the primary administrator"
- **Résolution** : Évitez d'essayer de bloquer l'utilisateur avec ID 1

# PARTIE 4: TESTS DES ENDPOINTS CATÉGORIES

## Test de récupération de la liste des catégories

### 🔹 Configuration du test

**Méthode :** GET  
**URL :** `{{base_url}}/v1/categories`  
**Headers :**
- Authorization: Bearer {{token}}

**Paramètres (optionnels) :**
- `search` : Filtre de recherche
- `sort` : Champ de tri
- `direction` : Sens du tri (asc/desc)
- `per_page` : Nombre d'éléments par page
- `page` : Numéro de page
- `include` : Relations à inclure (user,expenses,incomes)
- `include_counts` : Inclure le nombre d'éléments associés

### 🔹 Test et validation

**Ce que nous testons :**
- L'accès à la liste des catégories
- La filtration des catégories par utilisateur
- La pagination et le tri
- L'inclusion de relations et compteurs

**Comment procéder :**
1. Connectez-vous en tant qu'utilisateur standard puis en tant qu'administrateur
2. Envoyez la requête `GET /api/v1/categories` dans les deux cas
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez que l'utilisateur standard ne voit que ses propres catégories
5. Vérifiez que l'administrateur peut voir toutes les catégories

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response has pagination structure", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('data');
    pm.expect(jsonData).to.have.property('links');
    pm.expect(jsonData).to.have.property('meta');
});

pm.test("Categories have the required fields", function () {
    var jsonData = pm.response.json();
    if (jsonData.data.length > 0) {
        pm.expect(jsonData.data[0]).to.have.property('id');
        pm.expect(jsonData.data[0]).to.have.property('name');
        pm.expect(jsonData.data[0]).to.have.property('user_id');
    }
});

// Si vous avez des catégories avec des IDs connus, vous pouvez faire un test plus spécifique
// Par exemple, vérifier que l'utilisateur normal ne voit pas les catégories d'autres utilisateurs
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Visibilité des catégories**  
- **Symptôme** : Un utilisateur normal voit les catégories d'autres utilisateurs
- **Résolution** : Vérifiez le filtre par user_id dans le contrôleur

**Bug potentiel #2 : Problème de pagination**  
- **Symptôme** : Les liens de pagination sont incorrects ou non fonctionnels
- **Résolution** : Vérifiez la configuration de la pagination dans le contrôleur

## Test de récupération d'une catégorie spécifique

### 🔹 Configuration du test

**Méthode :** GET  
**URL :** `{{base_url}}/v1/categories/:id`  
**Headers :**
- Authorization: Bearer {{token}}

**Paramètres (optionnels) :**
- `include` : Relations à inclure
- `include_counts` : Inclure le nombre d'éléments associés

### 🔹 Test et validation

**Ce que nous testons :**
- L'accès aux détails d'une catégorie spécifique
- La restriction d'accès aux catégories d'autres utilisateurs
- L'inclusion des relations et compteurs

**Comment procéder :**
1. Obtenez l'ID d'une catégorie appartenant à l'utilisateur connecté
2. Envoyez la requête `GET /api/v1/categories/:id`
3. Vérifiez que le code de statut est `200 OK`
4. Essayez d'accéder à une catégorie d'un autre utilisateur (en tant qu'utilisateur standard)
5. Vérifiez que le code d'erreur est `403 Forbidden`

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Category data is correct", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('id');
    pm.expect(jsonData).to.have.property('name');
    pm.expect(jsonData).to.have.property('user_id');
});

pm.test("Counts are included when requested", function () {
    if (pm.request.url.query.has('include_counts')) {
        var jsonData = pm.response.json();
        pm.expect(jsonData).to.have.property('expenses_count');
        pm.expect(jsonData).to.have.property('incomes_count');
    }
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Accès non autorisé**  
- **Symptôme** : Erreur 403 "This action is unauthorized"
- **Résolution** : Vérifiez que vous essayez d'accéder à une catégorie qui vous appartient

**Bug potentiel #2 : Catégorie inexistante**  
- **Symptôme** : Erreur 404 "Not Found"
- **Résolution** : Vérifiez que l'ID de la catégorie existe

## Test de création d'une catégorie

### 🔹 Configuration du test

**Méthode :** POST  
**URL :** `{{base_url}}/v1/categories`  
**Headers :**
- Content-Type: application/json
- Authorization: Bearer {{token}}

**Body (JSON) :**
```json
{
    "name": "Nouvelle Catégorie"
}
```

### 🔹 Test et validation

**Ce que nous testons :**
- La création d'une nouvelle catégorie
- L'attribution automatique à l'utilisateur connecté
- La validation du nom unique par utilisateur

**Comment procéder :**
1. Connectez-vous en tant qu'utilisateur
2. Envoyez la requête avec un nom de catégorie unique
3. Vérifiez que le code de statut est `201 Created`
4. Vérifiez que la catégorie a bien été créée avec le bon utilisateur
5. Essayez de créer une catégorie avec un nom déjà utilisé
6. Vérifiez que la validation rejette le doublon

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 201", function () {
    pm.response.to.have.status(201);
});

pm.test("Category created with correct data", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('id');
    pm.expect(jsonData.name).to.eql("Nouvelle Catégorie");
    pm.expect(jsonData.user_id).to.eql(pm.environment.get("user_id"));
    
    // Stockage de l'ID pour les tests suivants
    if (jsonData.id) {
        pm.environment.set("new_category_id", jsonData.id);
    }
});
```

**Test de validation du nom unique :**
```javascript
// Créez une deuxième requête avec le même nom
// Elle devrait échouer avec le code 422
pm.test("Cannot create duplicate category", function () {
    pm.response.to.have.status(422);
    var jsonData = pm.response.json();
    pm.expect(jsonData.errors).to.have.property('name');
    pm.expect(jsonData.errors.name[0]).to.include('déjà');
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Validation du nom unique ne fonctionne pas**  
- **Symptôme** : Possibilité de créer des catégories avec le même nom pour un utilisateur
- **Résolution** : Vérifiez la règle de validation dans StoreCategoryRequest

**Bug potentiel #2 : La validation n'est pas spécifique à l'utilisateur**  
- **Symptôme** : Impossible de créer une catégorie même si le nom existe seulement pour un autre utilisateur
- **Résolution** : Ajoutez le filtre user_id dans la règle unique

## Test de mise à jour d'une catégorie

### 🔹 Configuration du test

**Méthode :** PUT  
**URL :** `{{base_url}}/v1/categories/:id`  
**Headers :**
- Content-Type: application/json
- Authorization: Bearer {{token}}

**Body (JSON) :**
```json
{
    "name": "Catégorie Modifiée"
}
```

### 🔹 Test et validation

**Ce que nous testons :**
- La mise à jour d'une catégorie existante
- La validation du nom unique (par utilisateur)
- Les restrictions d'accès (un utilisateur ne peut modifier que ses propres catégories)

**Comment procéder :**
1. Utilisez l'ID de la catégorie créée précédemment
2. Envoyez la requête avec le nouveau nom
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez que le nom a été correctement mis à jour
5. Essayez de mettre à jour une catégorie d'un autre utilisateur
6. Vérifiez que le code d'erreur est `403 Forbidden`

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Category updated with correct data", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.id).to.eql(pm.environment.get("new_category_id"));
    pm.expect(jsonData.name).to.eql("Catégorie Modifiée");
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Accès non autorisé**  
- **Symptôme** : Erreur 403 "This action is unauthorized"
- **Résolution** : Vérifiez que vous essayez de modifier une catégorie qui vous appartient

**Bug potentiel #2 : Validation du nom unique**  
- **Symptôme** : Erreur 422 sur le nom même lors de la mise à jour sans changement
- **Résolution** : Assurez-vous que la règle unique ignore l'enregistrement en cours d'édition

## Test de suppression d'une catégorie

### 🔹 Configuration du test

**Méthode :** DELETE  
**URL :** `{{base_url}}/v1/categories/:id`  
**Headers :**
- Authorization: Bearer {{token}}

### 🔹 Test et validation

**Ce que nous testons :**
- La suppression d'une catégorie
- L'impossibilité de supprimer une catégorie utilisée
- Les restrictions d'accès (un utilisateur ne peut supprimer que ses propres catégories)

**Comment procéder :**
1. Créez une nouvelle catégorie pour ce test
2. Envoyez la requête DELETE
3. Vérifiez que le code de statut est `204 No Content`
4. Créez une autre catégorie, associez-la à une dépense
5. Essayez de supprimer cette catégorie
6. Vérifiez que le code d'erreur est `422 Unprocessable Entity`

**Scripts Postman pour automatiser la validation :**
```javascript
// Pour une catégorie non utilisée
pm.test("Status code is 204", function () {
    pm.response.to.have.status(204);
});

// Pour une catégorie utilisée (dans un test séparé)
pm.test("Cannot delete a category in use", function () {
    pm.response.to.have.status(422);
    var jsonData = pm.response.json();
    pm.expect(jsonData.message).to.include("in use");
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Suppression d'une catégorie utilisée**  
- **Symptôme** : Erreur de contrainte d'intégrité dans la base de données
- **Résolution** : Vérifiez que le contrôleur vérifie si la catégorie est utilisée avant de la supprimer

**Bug potentiel #2 : Suppression autorisée pour d'autres utilisateurs**  
- **Symptôme** : Possibilité de supprimer des catégories d'autres utilisateurs
- **Résolution** : Vérifier les règles d'autorisation dans la Policy

# PARTIE 5: TESTS DES ENDPOINTS DÉPENSES

## Test de récupération de la liste des dépenses

### 🔹 Configuration du test

**Méthode :** GET  
**URL :** `{{base_url}}/v1/expenses`  
**Headers :**
- Authorization: Bearer {{token}}

**Paramètres (optionnels) :**
- `category_id` : Filtre par catégorie
- `date_start` : Filtre par date de début
- `date_end` : Filtre par date de fin
- `amount_min` : Filtre par montant minimum
- `amount_max` : Filtre par montant maximum
- `search` : Recherche dans les descriptions
- `sort` : Champ de tri
- `direction` : Sens du tri (asc/desc)
- `per_page` : Nombre d'éléments par page
- `page` : Numéro de page
- `include` : Relations à inclure (user,category)

### 🔹 Test et validation

**Ce que nous testons :**
- L'accès à la liste des dépenses
- La filtration par utilisateur (automatique)
- Les filtres optionnels
- La pagination et le tri
- L'inclusion des relations

**Comment procéder :**
1. Connectez-vous en tant qu'utilisateur standard puis en tant qu'administrateur
2. Envoyez la requête `GET /api/v1/expenses` dans les deux cas
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez que l'utilisateur standard ne voit que ses propres dépenses
5. Testez différentes combinaisons de filtres

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response has pagination structure", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('data');
    pm.expect(jsonData).to.have.property('links');
    pm.expect(jsonData).to.have.property('meta');
});

pm.test("Expenses have the required fields", function () {
    var jsonData = pm.response.json();
    if (jsonData.data.length > 0) {
        pm.expect(jsonData.data[0]).to.have.property('id');
        pm.expect(jsonData.data[0]).to.have.property('amount');
        pm.expect(jsonData.data[0]).to.have.property('description');
        pm.expect(jsonData.data[0]).to.have.property('date');
    }
});

// Test spécifique pour les utilisateurs normaux
if (!pm.environment.get("is_admin")) {
    pm.test("User only sees their own expenses", function () {
        var jsonData = pm.response.json();
        var userId = pm.environment.get("user_id");
        
        if (jsonData.data.length > 0) {
            // Vérifiez que chaque dépense appartient à l'utilisateur connecté
            var allBelongToUser = jsonData.data.every(function(expense) {
                return expense.user_id === userId;
            });
            
            pm.expect(allBelongToUser).to.be.true;
        }
    });
}
```

**Tests avec différents paramètres :**
1. Liste simple : `GET /api/v1/expenses`
2. Avec filtres de date : `GET /api/v1/expenses?date_start=2023-01-01&date_end=2023-12-31`
3. Avec filtres de montant : `GET /api/v1/expenses?amount_min=100&amount_max=500`
4. Avec filtre de catégorie : `GET /api/v1/expenses?category_id=1`
5. Avec recherche : `GET /api/v1/expenses?search=restaurant`
6. Avec tri : `GET /api/v1/expenses?sort=amount&direction=desc`
7. Avec pagination : `GET /api/v1/expenses?per_page=5&page=1`
8. Avec inclusion : `GET /api/v1/expenses?include=category`

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Filtre de date incorrect**  
- **Symptôme** : Les filtres de date ne fonctionnent pas comme prévu
- **Résolution** : Vérifiez le format de date et le traitement dans le contrôleur

**Bug potentiel #2 : Problème de visibilité entre utilisateurs**  
- **Symptôme** : Un utilisateur standard voit les dépenses d'autres utilisateurs
- **Résolution** : Vérifiez le filtre par user_id dans le contrôleur

## Test de récupération d'une dépense spécifique

### 🔹 Configuration du test

**Méthode :** GET  
**URL :** `{{base_url}}/v1/expenses/:id`  
**Headers :**
- Authorization: Bearer {{token}}

**Paramètres (optionnels) :**
- `include` : Relations à inclure (user,category)

### 🔹 Test et validation

**Ce que nous testons :**
- L'accès aux détails d'une dépense spécifique
- La restriction d'accès aux dépenses d'autres utilisateurs
- L'inclusion des relations

**Comment procéder :**
1. Obtenez l'ID d'une dépense appartenant à l'utilisateur connecté
2. Envoyez la requête `GET /api/v1/expenses/:id`
3. Vérifiez que le code de statut est `200 OK`
4. Essayez d'accéder à une dépense d'un autre utilisateur (en tant qu'utilisateur standard)
5. Vérifiez que le code d'erreur est `403 Forbidden`

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Expense data is correct", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('id');
    pm.expect(jsonData).to.have.property('amount');
    pm.expect(jsonData).to.have.property('description');
    pm.expect(jsonData).to.have.property('date');
});

pm.test("Included relationships are present when requested", function () {
    if (pm.request.url.query.has('include')) {
        var jsonData = pm.response.json();
        var includes = pm.request.url.query.get('include').split(',');
        
        if (includes.includes('category')) {
            pm.expect(jsonData).to.have.property('category');
            pm.expect(jsonData.category).to.have.property('id');
            pm.expect(jsonData.category).to.have.property('name');
        }
        
        if (includes.includes('user')) {
            pm.expect(jsonData).to.have.property('user');
            pm.expect(jsonData.user).to.have.property('id');
            pm.expect(jsonData.user).to.have.property('name');
        }
    }
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Accès non autorisé**  
- **Symptôme** : Erreur 403 "This action is unauthorized"
- **Résolution** : Vérifiez que vous essayez d'accéder à une dépense qui vous appartient

**Bug potentiel #2 : Dépense inexistante**  
- **Symptôme** : Erreur 404 "Not Found"
- **Résolution** : Vérifiez que l'ID de la dépense existe

## Test de création d'une dépense

### 🔹 Configuration du test

**Méthode :** POST  
**URL :** `{{base_url}}/v1/expenses`  
**Headers :**
- Content-Type: application/json
- Authorization: Bearer {{token}}

**Body (JSON) :**
```json
{
    "amount": 150.75,
    "description": "Restaurant avec clients",
    "date": "2023-07-25",
    "category_id": 1
}
```

### 🔹 Test et validation

**Ce que nous testons :**
- La création d'une nouvelle dépense
- L'attribution automatique à l'utilisateur connecté
- La validation des données (montant positif, date valide, catégorie existante)
- L'appartenance de la catégorie à l'utilisateur connecté

**Comment procéder :**
1. Obtenez l'ID d'une catégorie appartenant à l'utilisateur connecté
2. Envoyez la requête avec les données d'une nouvelle dépense
3. Vérifiez que le code de statut est `201 Created`
4. Vérifiez que la dépense a bien été créée avec le bon utilisateur
5. Essayez de créer une dépense avec une catégorie d'un autre utilisateur
6. Vérifiez que le code d'erreur est `403 Forbidden`

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 201", function () {
    pm.response.to.have.status(201);
});

pm.test("Expense created with correct data", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('id');
    pm.expect(jsonData.amount).to.eql(150.75);
    pm.expect(jsonData.description).to.eql("Restaurant avec clients");
    pm.expect(jsonData.date).to.eql("2023-07-25");
    pm.expect(jsonData.category_id).to.eql(1);
    
    // Stockage de l'ID pour les tests suivants
    if (jsonData.id) {
        pm.environment.set("new_expense_id", jsonData.id);
    }
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Validation du montant**  
- **Symptôme** : Possibilité de créer des dépenses avec un montant négatif
- **Résolution** : Vérifiez la règle de validation dans StoreExpenseRequest

**Bug potentiel #2 : Catégorie appartenant à un autre utilisateur**  
- **Symptôme** : Possibilité d'utiliser une catégorie d'un autre utilisateur
- **Résolution** : Vérifiez que le contrôleur vérifie l'appartenance de la catégorie

## Test de mise à jour d'une dépense

### 🔹 Configuration du test

**Méthode :** PUT  
**URL :** `{{base_url}}/v1/expenses/:id`  
**Headers :**
- Content-Type: application/json
- Authorization: Bearer {{token}}

**Body (JSON) :**
```json
{
    "amount": 175.50,
    "description": "Restaurant d'affaires - mise à jour",
    "date": "2023-07-26",
    "category_id": 2
}
```

### 🔹 Test et validation

**Ce que nous testons :**
- La mise à jour d'une dépense existante
- La validation des données
- Les restrictions d'accès (un utilisateur ne peut modifier que ses propres dépenses)

**Comment procéder :**
1. Utilisez l'ID de la dépense créée précédemment
2. Envoyez la requête avec les données mises à jour
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez que les données ont été correctement mises à jour
5. Essayez de mettre à jour une dépense d'un autre utilisateur
6. Vérifiez que le code d'erreur est `403 Forbidden`

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Expense updated with correct data", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.id).to.eql(pm.environment.get("new_expense_id"));
    pm.expect(jsonData.amount).to.eql(175.50);
    pm.expect(jsonData.description).to.eql("Restaurant d'affaires - mise à jour");
    pm.expect(jsonData.date).to.eql("2023-07-26");
    pm.expect(jsonData.category_id).to.eql(2);
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Accès non autorisé**  
- **Symptôme** : Erreur 403 "This action is unauthorized"
- **Résolution** : Vérifiez que vous essayez de modifier une dépense qui vous appartient

**Bug potentiel #2 : Catégorie appartenant à un autre utilisateur**  
- **Symptôme** : Erreur 403 lors de l'utilisation d'une catégorie valide d'un autre utilisateur
- **Résolution** : Vérifiez que le contrôleur vérifie l'appartenance de la catégorie

## Test de suppression d'une dépense

### 🔹 Configuration du test

**Méthode :** DELETE  
**URL :** `{{base_url}}/v1/expenses/:id`  
**Headers :**
- Authorization: Bearer {{token}}

### 🔹 Test et validation

**Ce que nous testons :**
- La suppression d'une dépense
- Les restrictions d'accès (un utilisateur ne peut supprimer que ses propres dépenses)

**Comment procéder :**
1. Créez une nouvelle dépense pour ce test
2. Envoyez la requête DELETE
3. Vérifiez que le code de statut est `204 No Content`
4. Essayez de supprimer une dépense d'un autre utilisateur
5. Vérifiez que le code d'erreur est `403 Forbidden`

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 204", function () {
    pm.response.to.have.status(204);
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Accès non autorisé**  
- **Symptôme** : Erreur 403 "This action is unauthorized"
- **Résolution** : Vérifiez que vous essayez de supprimer une dépense qui vous appartient

**Bug potentiel #2 : Suppressions en cascade non configurées**  
- **Symptôme** : Erreur de contrainte d'intégrité dans la base de données
- **Résolution** : Vérifiez que les migrations incluent le `onDelete('cascade')` pour les relations

# PARTIE 6: TESTS DES ENDPOINTS REVENUS

## Test de récupération de la liste des revenus

### 🔹 Configuration du test

**Méthode :** GET  
**URL :** `{{base_url}}/v1/incomes`  
**Headers :**
- Authorization: Bearer {{token}}

**Paramètres (optionnels) :**
- `category_id` : Filtre par catégorie
- `date_start` : Filtre par date de début
- `date_end` : Filtre par date de fin
- `amount_min` : Filtre par montant minimum
- `amount_max` : Filtre par montant maximum
- `search` : Recherche dans les descriptions
- `sort` : Champ de tri
- `direction` : Sens du tri (asc/desc)
- `per_page` : Nombre d'éléments par page
- `page` : Numéro de page
- `include` : Relations à inclure (user,category)

### 🔹 Test et validation

**Ce que nous testons :**
- L'accès à la liste des revenus
- La filtration par utilisateur (automatique)
- Les filtres optionnels
- La pagination et le tri
- L'inclusion des relations

**Comment procéder :**
1. Connectez-vous en tant qu'utilisateur standard puis en tant qu'administrateur
2. Envoyez la requête `GET /api/v1/incomes` dans les deux cas
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez que l'utilisateur standard ne voit que ses propres revenus
5. Testez différentes combinaisons de filtres

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response has pagination structure", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('data');
    pm.expect(jsonData).to.have.property('links');
    pm.expect(jsonData).to.have.property('meta');
});

pm.test("Incomes have the required fields", function () {
    var jsonData = pm.response.json();
    if (jsonData.data.length > 0) {
        pm.expect(jsonData.data[0]).to.have.property('id');
        pm.expect(jsonData.data[0]).to.have.property('amount');
        pm.expect(jsonData.data[0]).to.have.property('description');
        pm.expect(jsonData.data[0]).to.have.property('date');
    }
});
```

### 🔹 Bugs potentiels et résolutions

Les bugs potentiels sont similaires à ceux des dépenses, puisque les deux modules suivent la même structure.

## Test de récupération d'un revenu spécifique

### 🔹 Configuration du test

**Méthode :** GET  
**URL :** `{{base_url}}/v1/incomes/:id`  
**Headers :**
- Authorization: Bearer {{token}}

**Paramètres (optionnels) :**
- `include` : Relations à inclure (user,category)

### 🔹 Test et validation

**Ce que nous testons :**
- L'accès aux détails d'un revenu spécifique
- La restriction d'accès aux revenus d'autres utilisateurs
- L'inclusion des relations

**Comment procéder :**
1. Obtenez l'ID d'un revenu appartenant à l'utilisateur connecté
2. Envoyez la requête `GET /api/v1/incomes/:id`
3. Vérifiez que le code de statut est `200 OK`
4. Essayez d'accéder à un revenu d'un autre utilisateur (en tant qu'utilisateur standard)
5. Vérifiez que le code d'erreur est `403 Forbidden`

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Income data is correct", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('id');
    pm.expect(jsonData).to.have.property('amount');
    pm.expect(jsonData).to.have.property('description');
    pm.expect(jsonData).to.have.property('date');
});
```

### 🔹 Bugs potentiels et résolutions

Les bugs potentiels sont similaires à ceux rencontrés avec les dépenses.

## Test de création d'un revenu

### 🔹 Configuration du test

**Méthode :** POST  
**URL :** `{{base_url}}/v1/incomes`  
**Headers :**
- Content-Type: application/json
- Authorization: Bearer {{token}}

**Body (JSON) :**
```json
{
    "amount": 2500.00,
    "description": "Salaire mensuel",
    "date": "2023-07-31",
    "category_id": 11
}
```

### 🔹 Test et validation

**Ce que nous testons :**
- La création d'un nouveau revenu
- L'attribution automatique à l'utilisateur connecté
- La validation des données
- L'appartenance de la catégorie à l'utilisateur connecté

**Comment procéder :**
1. Obtenez l'ID d'une catégorie de type revenu appartenant à l'utilisateur connecté
2. Envoyez la requête avec les données d'un nouveau revenu
3. Vérifiez que le code de statut est `201 Created`
4. Vérifiez que le revenu a bien été créé avec le bon utilisateur

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 201", function () {
    pm.response.to.have.status(201);
});

pm.test("Income created with correct data", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('id');
    pm.expect(jsonData.amount).to.eql(2500.00);
    pm.expect(jsonData.description).to.eql("Salaire mensuel");
    pm.expect(jsonData.date).to.eql("2023-07-31");
    pm.expect(jsonData.category_id).to.eql(11);
    
    // Stockage de l'ID pour les tests suivants
    if (jsonData.id) {
        pm.environment.set("new_income_id", jsonData.id);
    }
});
```

### 🔹 Bugs potentiels et résolutions

Les bugs potentiels sont similaires à ceux des dépenses.

## Test de mise à jour d'un revenu

### 🔹 Configuration du test

**Méthode :** PUT  
**URL :** `{{base_url}}/v1/incomes/:id`  
**Headers :**
- Content-Type: application/json
- Authorization: Bearer {{token}}

**Body (JSON) :**
```json
{
    "amount": 2750.00,
    "description": "Salaire mensuel avec prime",
    "date": "2023-07-31",
    "category_id": 11
}
```

### 🔹 Test et validation

**Ce que nous testons :**
- La mise à jour d'un revenu existant
- La validation des données
- Les restrictions d'accès (un utilisateur ne peut modifier que ses propres revenus)

**Comment procéder :**
1. Utilisez l'ID du revenu créé précédemment
2. Envoyez la requête avec les données mises à jour
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez que les données ont été correctement mises à jour

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Income updated with correct data", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.id).to.eql(pm.environment.get("new_income_id"));
    pm.expect(jsonData.amount).to.eql(2750.00);
    pm.expect(jsonData.description).to.eql("Salaire mensuel avec prime");
});
```

### 🔹 Bugs potentiels et résolutions

Les bugs potentiels sont similaires à ceux des dépenses.

## Test de suppression d'un revenu

### 🔹 Configuration du test

**Méthode :** DELETE  
**URL :** `{{base_url}}/v1/incomes/:id`  
**Headers :**
- Authorization: Bearer {{token}}

### 🔹 Test et validation

**Ce que nous testons :**
- La suppression d'un revenu
- Les restrictions d'accès (un utilisateur ne peut supprimer que ses propres revenus)

**Comment procéder :**
1. Créez un nouveau revenu pour ce test
2. Envoyez la requête DELETE
3. Vérifiez que le code de statut est `204 No Content`
4. Essayez de supprimer un revenu d'un autre utilisateur
5. Vérifiez que le code d'erreur est `403 Forbidden`

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 204", function () {
    pm.response.to.have.status(204);
});
```

### 🔹 Bugs potentiels et résolutions

Les bugs potentiels sont similaires à ceux des dépenses.

# PARTIE 7: TESTS DES ENDPOINTS PROFIL

## Test de récupération du profil

### 🔹 Configuration du test

**Méthode :** GET  
**URL :** `{{base_url}}/v1/profile`  
**Headers :**
- Authorization: Bearer {{token}}

### 🔹 Test et validation

**Ce que nous testons :**
- L'accès au profil de l'utilisateur authentifié
- La correspondance des données avec l'utilisateur connecté

**Comment procéder :**
1. Connectez-vous pour obtenir un token valide
2. Envoyez la requête `GET /api/v1/profile`
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez que les données correspondent à l'utilisateur authentifié

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Profile data matches authenticated user", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('id');
    pm.expect(jsonData).to.have.property('name');
    pm.expect(jsonData).to.have.property('email');
    
    // Si vous avez stocké les infos de l'utilisateur connecté
    if (pm.environment.has("user_id")) {
        pm.expect(jsonData.id).to.equal(Number(pm.environment.get("user_id")));
    }
    if (pm.environment.has("user_email")) {
        pm.expect(jsonData.email).to.equal(pm.environment.get("user_email"));
    }
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Authentification expirée**  
- **Symptôme** : Erreur 401 "Unauthenticated"
- **Résolution** : Reconnectez-vous pour obtenir un nouveau token

## Test de mise à jour du profil

### 🔹 Configuration du test

**Méthode :** PUT  
**URL :** `{{base_url}}/v1/profile`  
**Headers :**
- Content-Type: application/json
- Authorization: Bearer {{token}}

**Body (JSON) :**
```json
{
    "name": "Nom Mis À Jour",
    "email": "email_mis_a_jour@example.com",
    "password": "nouveau_mot_de_passe",
    "password_confirmation": "nouveau_mot_de_passe",
    "current_password": "ancien_mot_de_passe"
}
```

### 🔹 Test et validation

**Ce que nous testons :**
- La mise à jour des informations de profil
- La validation du mot de passe actuel
- La confirmation du nouveau mot de passe

**Comment procéder :**
1. Connectez-vous avec un compte de test
2. Envoyez la requête avec les données mises à jour
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez que les informations ont été correctement mises à jour
5. Testez différents scénarios de mise à jour (nom seulement, email seulement, mot de passe)

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Profile updated with correct data", function () {
    var jsonData = pm.response.json();
    
    // Vérifiez les champs mis à jour
    if (pm.request.body && pm.request.body.mode === "raw") {
        const requestBody = JSON.parse(pm.request.body.raw);
        
        if (requestBody.name) {
            pm.expect(jsonData.name).to.equal(requestBody.name);
        }
        
        if (requestBody.email) {
            pm.expect(jsonData.email).to.equal(requestBody.email);
        }
    }
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Validation du mot de passe actuel incorrecte**  
- **Symptôme** : Erreur 422 avec message sur le mot de passe actuel
- **Résolution** : Assurez-vous de fournir le bon mot de passe actuel

**Bug potentiel #2 : Confirmation de mot de passe ne correspond pas**  
- **Symptôme** : Erreur 422 "The password confirmation does not match"
- **Résolution** : Vérifiez que les champs password et password_confirmation sont identiques

## Test de mise à jour de l'image de profil

### 🔹 Configuration du test

**Méthode :** POST  
**URL :** `{{base_url}}/v1/profile/image`  
**Headers :**
- Authorization: Bearer {{token}}

**Body (form-data) :**
- `profile_image` : [Fichier image]

### 🔹 Test et validation

**Ce que nous testons :**
- L'upload et la mise à jour de l'image de profil
- La validation du type de fichier
- La gestion des anciennes images

**Comment procéder :**
1. Préparez une image de test
2. Envoyez la requête avec l'image dans le corps
3. Vérifiez que le code de statut est `200 OK`
4. Vérifiez que l'URL de l'image de profil a été mise à jour

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Profile image was updated", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('profile_image_url');
    pm.expect(jsonData.profile_image_url).to.not.be.null;
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Type de fichier non accepté**  
- **Symptôme** : Erreur 422 concernant le type de fichier
- **Résolution** : Utilisez une image au format accepté (jpeg, png, jpg, gif)

**Bug potentiel #2 : Taille de fichier trop grande**  
- **Symptôme** : Erreur 422 concernant la taille du fichier
- **Résolution** : Utilisez une image de moins de 2MB

## Test de suppression du compte

### 🔹 Configuration du test

**Méthode :** DELETE  
**URL :** `{{base_url}}/v1/profile`  
**Headers :**
- Content-Type: application/json
- Authorization: Bearer {{token}}

**Body (JSON) :**
```json
{
    "password": "mot_de_passe_actuel"
}
```

### 🔹 Test et validation

**Ce que nous testons :**
- La suppression du compte utilisateur
- La confirmation par mot de passe
- La révocation des tokens

**Comment procéder :**
1. Créez un compte utilisateur spécifique pour ce test
2. Connectez-vous avec ce compte
3. Envoyez la requête DELETE avec le mot de passe correct
4. Vérifiez que le code de statut est `200 OK`
5. Essayez d'utiliser le même token pour une autre requête
6. Vérifiez que le token a été révoqué (erreur 401)

**Scripts Postman pour automatiser la validation :**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Account deletion confirmation message", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('message');
    pm.expect(jsonData.message).to.include('deleted');
});
```

### 🔹 Bugs potentiels et résolutions

**Bug potentiel #1 : Mot de passe incorrect**  
- **Symptôme** : Erreur 422 concernant la validation du mot de passe
- **Résolution** : Fournir le mot de passe correct dans la requête

**Bug potentiel #2 : Tokens non révoqués**  
- **Symptôme** : Le token reste utilisable après la suppression du compte
- **Résolution** : Vérifiez que le contrôleur révoque tous les tokens de l'utilisateur

# PARTIE 8: TESTS AVANCÉS

## Test des filtres et de la pagination

Pour tester systématiquement les filtres et la pagination, nous pouvons créer une série de requêtes qui testent différentes combinaisons.

### 🔹 Filtres à tester pour les dépenses et revenus

1. **Filtres de date**
   - `date_start` seulement
   - `date_end` seulement
   - Les deux ensemble
   - Format de date invalide

2. **Filtres de montant**
   - `amount_min` seulement
   - `amount_max` seulement
   - Les deux ensemble
   - Valeurs négatives ou non numériques

3. **Filtres de catégorie**
   - Catégorie existante
   - Catégorie inexistante
   - Catégorie d'un autre utilisateur

4. **Recherche textuelle**
   - Terme spécifique
   - Terme partiel
   - Terme inexistant

### 🔹 Tests de pagination

1. **Paramètres de pagination**
   - Différentes valeurs de `per_page`
   - Différentes valeurs de `page`
   - Valeurs limites (très grand `per_page`)
   - Valeurs invalides (`page` négatif)

2. **Navigation entre pages**
   - Utilisation des liens `next`, `prev`
   - Accès à `first` et `last`
   - Dépassement des limites

### 🔹 Scripts d'automatisation

```javascript
// Exemple pour tester la pagination
function testPagination(baseUrl, token, entity) {
    const testCases = [
        { per_page: 5, page: 1, expectedStatus: 200 },
        { per_page: 10, page: 1, expectedStatus: 200 },
        { per_page: 100, page: 1, expectedStatus: 200 },
        { per_page: -1, page: 1, expectedStatus: 422 }, // Devrait être rejeté
        { per_page: 5, page: -1, expectedStatus: 422 }, // Devrait être rejeté
    ];
    
    testCases.forEach(testCase => {
        pm.sendRequest({
            url: baseUrl + `/${entity}?per_page=${testCase.per_page}&page=${testCase.page}`,
            method: 'GET',
            header: {
                'Authorization': 'Bearer ' + token
            }
        }, function (err, res) {
            console.log(`Testing ${entity} pagination: per_page=${testCase.per_page}, page=${testCase.page}`);
            console.log(`Expected status: ${testCase.expectedStatus}, Actual status: ${res.status}`);
            
            // Vérifier le statut
            pm.expect(res.status).to.equal(testCase.expectedStatus);
            
            // Si succès, vérifier la structure de pagination
            if (testCase.expectedStatus === 200) {
                const jsonData = res.json();
                pm.expect(jsonData).to.have.property('data');
                pm.expect(jsonData).to.have.property('links');
                pm.expect(jsonData).to.have.property('meta');
                
                // Vérifier que le meta.per_page correspond à la valeur demandée (si valide)
                if (testCase.per_page > 0) {
                    pm.expect(jsonData.meta.per_page).to.equal(testCase.per_page);
                }
                
                // Vérifier que la page actuelle correspond
                if (testCase.page > 0) {
                    pm.expect(jsonData.meta.current_page).to.equal(testCase.page);
                }
            }
        });
    });
}
```

## Test des permissions et rôles

Pour tester systématiquement les permissions et rôles, nous devons vérifier que chaque endpoint respecte les règles d'autorisation.

### 🔹 Matrice de tests à établir

| Endpoint | Utilisateur standard | Administrateur |
|----------|----------------------|----------------|
| **Users - GET /users** | Interdit (403) | Autorisé (200) |
| **Users - POST /users** | Interdit (403) | Autorisé (201) |
| **Users - PUT /users/:id** | Interdit sauf soi-même | Autorisé pour tous |
| **Categories - GET /categories** | Limité à ses catégories | Toutes les catégories |
| **Expenses - GET /expenses** | Limité à ses dépenses | Toutes les dépenses |

### 🔹 Implémentation des tests

Pour chaque combinaison d'endpoint et de rôle, créez une requête Postman et vérifiez le comportement attendu.

```javascript
// Exemple pour tester les permissions
function testPermissions(baseUrl, userToken, adminToken) {
    const testCases = [
        { endpoint: '/users', method: 'GET', userStatus: 403, adminStatus: 200 },
        { endpoint: '/categories', method: 'GET', userStatus: 200, adminStatus: 200 },
        // ...
    ];
    
    // Tester avec l'utilisateur standard
    testCases.forEach(testCase => {
        pm.sendRequest({
            url: baseUrl + testCase.endpoint,
            method: testCase.method,
            header: {
                'Authorization': 'Bearer ' + userToken
            }
        }, function (err, res) {
            console.log(`Testing ${testCase.method} ${testCase.endpoint} as user`);
            console.log(`Expected status: ${testCase.userStatus}, Actual status: ${res.status}`);
            pm.expect(res.status).to.equal(testCase.userStatus);
        });
    });
    
    // Tester avec l'administrateur
    testCases.forEach(testCase => {
        pm.sendRequest({
            url: baseUrl + testCase.endpoint,
            method: testCase.method,
            header: {
                'Authorization': 'Bearer ' + adminToken
            }
        }, function (err, res) {
            console.log(`Testing ${testCase.method} ${testCase.endpoint} as admin`);
            console.log(`Expected status: ${testCase.adminStatus}, Actual status: ${res.status}`);
            pm.expect(res.status).to.equal(testCase.adminStatus);
        });
    });
}
```

## Test des cas d'erreur

Il est important de tester comment l'API gère les erreurs et les cas limites.

### 🔹 Types d'erreurs à tester

1. **Validation des données**
   - Champs manquants
   - Formats invalides
   - Valeurs hors limites
   - Doublons

2. **Authentification et autorisation**
   - Token invalide
   - Token expiré
   - Permissions insuffisantes

3. **Ressources**
   - ID inexistant
   - Conflits de données
   - Contraintes d'intégrité

### 🔹 Implémentation des tests

Pour chaque endpoint, créez des requêtes qui testent les différents scénarios d'erreur.

```javascript
// Exemple pour tester les erreurs de validation
function testValidationErrors(baseUrl, token) {
    // Tester la création d'une dépense avec des données invalides
    pm.sendRequest({
        url: baseUrl + '/expenses',
        method: 'POST',
        header: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json'
        },
        body: {
            mode: 'raw',
            raw: JSON.stringify({
                // Montant négatif (devrait être rejeté)
                amount: -100,
                // Description manquante
                date: '2023-07-25',
                category_id: 1
            })
        }
    }, function (err, res) {
        console.log('Testing validation errors');
        
        // Vérifier le statut d'erreur de validation
        pm.expect(res.status).to.equal(422);
        
        // Vérifier la structure de la réponse d'erreur
        const jsonData = res.json();
        pm.expect(jsonData).to.have.property('errors');
        pm.expect(jsonData.errors).to.have.property('amount');
        pm.expect(jsonData.errors).to.have.property('description');
    });
}
```

## Tests de charge basiques

Les tests de charge complets nécessiteraient des outils spécialisés comme JMeter ou Gatling, mais nous pouvons faire quelques tests basiques avec Postman.

### 🔹 Collection Runner

Postman propose un outil appelé Collection Runner qui permet d'exécuter une collection entière ou une partie de celle-ci plusieurs fois.

1. Ouvrez Collection Runner depuis le menu principal
2. Sélectionnez votre collection ou dossier
3. Définissez le nombre d'itérations (par exemple, 20)
4. Activez "Keep variable values"
5. Lancez les tests

### 🔹 Analyse des performances

Lors de l'exécution, observez :
- Le temps de réponse moyen
- Le taux de réussite
- Les différences de performance entre les endpoints

## 📊 Rédaction d'un rapport de test

Après avoir exécuté tous les tests, il est important de rédiger un rapport synthétique.

### 🔹 Structure du rapport

1. **Résumé exécutif**
   - Pourcentage de tests réussis
   - Principales réussites et échecs
   - Recommandations prioritaires

2. **Détails des tests**
   - Liste des endpoints testés
   - Résultats pour chaque endpoint
   - Erreurs rencontrées

3. **Performance**
   - Temps de réponse moyen
   - Endpoints les plus lents
   - Recommandations d'optimisation

4. **Sécurité**
   - Validation des contrôles d'accès
   - Vulnérabilités potentielles
   - Recommandations de sécurité

### 🔹 Exemple de résumé de rapport

```
Rapport de test de l'API Gestion Dépenses - 2023-07-28

Résumé exécutif:
- Tests réussis: 95% (57/60 tests)
- Principales réussites: Authentification robuste, filtres fonctionnels, contrôles d'accès bien implémentés
- Principaux problèmes: Validation incomplète pour certains endpoints, problèmes de performance avec des grands volumes

Recommandations prioritaires:
1. Renforcer la validation pour les endpoints Expense et Income
2. Optimiser les requêtes pour améliorer les performances de liste avec filtres
3. Ajouter des tests unitaires pour couvrir les cas spécifiques identifiés

Détails des résultats par module:
...
```

# PARTIE 9: AUTOMATISATION ET INTÉGRATION CONTINUE

## Exportation et partage des collections

Pour faciliter la collaboration, vous pouvez exporter et partager vos collections Postman.

### 🔹 Exportation de la collection

1. Cliquez sur les trois points (...) à côté du nom de la collection
2. Sélectionnez "Export"
3. Choisissez la version (v2.1 est recommandée)
4. Enregistrez le fichier JSON

### 🔹 Exportation de l'environnement

1. Cliquez sur l'icône d'engrenage (⚙️)
2. Cliquez sur les trois points (...) à côté de l'environnement
3. Sélectionnez "Export"
4. Enregistrez le fichier JSON

### 🔹 Partage via contrôle de version

Il est recommandé de stocker ces fichiers dans votre dépôt Git :

```
projet/
├── app/
├── ...
└── tests/
    └── postman/
        ├── Gestion_Depenses_API.postman_collection.json
        └── Gestion_Depenses_Local.postman_environment.json
```

## Automatisation des tests avec Newman

Newman est un outil en ligne de commande pour exécuter des collections Postman. Il permet d'intégrer facilement les tests Postman dans vos pipelines CI/CD.

### 🔹 Installation de Newman

```bash
# Installation globale avec npm
npm install -g newman

# Si vous souhaitez générer des rapports HTML
npm install -g newman-reporter-htmlextra
```

### 🔹 Exécution des tests avec Newman

```bash
# Exécution basique
newman run Gestion_Depenses_API.postman_collection.json -e Gestion_Depenses_Local.postman_environment.json

# Avec génération de rapport HTML
newman run Gestion_Depenses_API.postman_collection.json -e Gestion_Depenses_Local.postman_environment.json -r htmlextra
```

### 🔹 Options utiles de Newman

- `-n <number>` : Nombre d'itérations
- `--folder <name>` : Exécuter seulement un dossier spécifique
- `--timeout <ms>` : Timeout pour les requêtes
- `--delay-request <ms>` : Délai entre les requêtes

## Intégration dans un pipeline CI/CD

Vous pouvez intégrer les tests Newman dans votre pipeline CI/CD pour automatiser les tests d'API.

### 🔹 Exemple avec GitHub Actions

```yaml
# .github/workflows/api-tests.yml
name: API Tests

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main, develop ]

jobs:
  api-tests:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Set up Node.js
      uses: actions/setup-node@v2
      with:
        node-version: '14'
    
    - name: Install Newman
      run: npm install -g newman newman-reporter-htmlextra
    
    - name: Set up Laravel environment
      run: |
        cp .env.example .env
        composer install
        php artisan key:generate
        php artisan migrate --seed
        php artisan serve --port=8000 &
        sleep 5  # Attendre que le serveur démarre
    
    - name: Run API tests
      run: newman run ./tests/postman/Gestion_Depenses_API.postman_collection.json -e ./tests/postman/Gestion_Depenses_CI.postman_environment.json -r cli,htmlextra
    
    - name: Archive test results
      uses: actions/upload-artifact@v2
      if: always()
      with:
        name: newman-report
        path: newman/
```

### 🔹 Exemple avec GitLab CI

```yaml
# .gitlab-ci.yml
stages:
  - test

api-tests:
  stage: test
  image: php:8.2
  services:
    - mysql:8.0
  variables:
    MYSQL_ROOT_PASSWORD: root
    MYSQL_DATABASE: gestion_depenses_test
  before_script:
    - apt-get update && apt-get install -y nodejs npm
    - npm install -g newman newman-reporter-htmlextra
    - curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    - composer install
    - cp .env.example .env
    - sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql/g' .env
    - sed -i 's/DB_PASSWORD=/DB_PASSWORD=root/g' .env
    - sed -i 's/DB_DATABASE=laravel/DB_DATABASE=gestion_depenses_test/g' .env
    - php artisan key:generate
    - php artisan migrate --seed
    - php artisan serve --port=8000 &
    - sleep 5  # Attendre que le serveur démarre
  script:
    - newman run ./tests/postman/Gestion_Depenses_API.postman_collection.json -e ./tests/postman/Gestion_Depenses_CI.postman_environment.json -r cli,htmlextra
  artifacts:
    paths:
      - newman/
    expire_in: 1 week
```

# RESSOURCES ET ANNEXES

## Commandes et outils utiles

### 🔹 Commandes Laravel pour le débogage

```bash
# Effacer le cache
php artisan cache:clear

# Effacer le cache de configuration
php artisan config:clear

# Effacer le cache de routes
php artisan route:clear

# Lister toutes les routes API
php artisan route:list --path=api

# Lister les middlewares
php artisan middleware:list

# Vérifier les politiques d'autorisation
php artisan policy:list
```

### 🔹 Requêtes cURL équivalentes

Vous pouvez également utiliser cURL pour tester vos API. Voici quelques exemples :

```bash
# Authentification
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# Récupérer la liste des utilisateurs
curl -X GET http://localhost:8000/api/v1/users \
  -H "Authorization: Bearer {votre_token}"

# Créer une dépense
curl -X POST http://localhost:8000/api/v1/expenses \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {votre_token}" \
  -d '{"amount":150.75,"description":"Restaurant","date":"2023-07-25","category_id":1}'
```

## Ressources complémentaires

### 🔹 Documentation officielle et tutoriels

- [Documentation officielle de Postman](https://learning.postman.com/docs/getting-started/introduction/)
- [Cours Postman sur Postman Academy](https://academy.postman.com/)
- [Documentation de Newman](https://github.com/postmanlabs/newman)
- [Documentation Laravel sur les API Resources](https://laravel.com/docs/11.x/eloquent-resources)
- [Documentation Laravel sur Sanctum](https://laravel.com/docs/11.x/sanctum)

### 🔹 Outils complémentaires pour les tests d'API

- [Insomnia](https://insomnia.rest/) - Alternative à Postman
- [Swagger/OpenAPI](https://swagger.io/) - Pour la documentation d'API
- [JMeter](https://jmeter.apache.org/) - Pour les tests de charge avancés
- [Gatling](https://gatling.io/) - Tests de charge basés sur Scala

## Troubleshooting courant

### 🔹 Problèmes fréquents et solutions

**Problème : 401 Unauthorized malgré un token valide**
- Vérifiez la syntaxe de l'en-tête Authorization (`Bearer` avec un espace avant le token)
- Vérifiez que le token n'a pas expiré
- Vérifiez que l'utilisateur est actif et existe toujours

**Problème : 403 Forbidden sur un endpoint où l'accès devrait être autorisé**
- Vérifiez les politiques d'autorisation
- Vérifiez les rôles et permissions de l'utilisateur
- Vérifiez les règles de propriété des ressources

**Problème : 500 Internal Server Error**
- Consultez les logs Laravel (`storage/logs/laravel.log`)
- Activez le mode debug dans `.env` (APP_DEBUG=true)
- Vérifiez les exceptions dans le Handler

**Problème : Variables d'environnement non persistantes dans Postman**
- Vérifiez que vous avez bien sélectionné l'environnement
- Assurez-vous d'utiliser `pm.environment.set()` et non `pm.variables.set()`
- Vérifiez l'option "Keep variable values" dans Collection Runner

## Conclusion

Les tests d'API sont une étape cruciale pour garantir la qualité et la fiabilité de votre application. En utilisant Postman et ses fonctionnalités avancées, vous pouvez :

1. **Tester systématiquement** tous les endpoints de votre API
2. **Automatiser les validations** pour détecter rapidement les problèmes
3. **Documenter le comportement** attendu de votre API
4. **Partager les connaissances** avec votre équipe
5. **Intégrer les tests** dans votre pipeline CI/CD

Cette méthodologie de test vous permettra de développer une API robuste, bien documentée et facile à maintenir.

---

## 📌 Code source de cette étape

Le code source correspondant à cette étape est disponible sur la branche `step-8`.

## 📌 Prochaine étape

Maintenant que nous avons testé notre API, nous allons la sécuriser davantage en implémenttant une authentification JWT (JSON Web Token) plus robuste. **[➡️ Étape suivante : Sécurisation de l'API avec JWT](10-auth-jwt.md)**.