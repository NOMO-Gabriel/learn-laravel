# 🌐 API Rest avec Laravel 11

[⬅️ Étape précédente : Tests et Débogage des Interfaces](07-tests-interfaces.md)  
[➡️ Étape suivante : Tests de l'API avec Postman](09-tests-api.md)

---

## 📌 Table des matières

### PARTIE 1: FONDAMENTAUX DES API REST
- [Introduction aux API REST](#introduction-aux-api-rest)
- [API RESTful vs API traditionnelle](#api-restful-vs-api-traditionnelle)
- [Principes REST et bonnes pratiques](#principes-rest-et-bonnes-pratiques)
- [Structure des requêtes et réponses HTTP](#structure-des-requêtes-et-réponses-http)
- [Les codes de statut HTTP](#les-codes-de-statut-http)
- [La sécurité des API](#la-sécurité-des-api)

### PARTIE 2: CONCEPTS AVANCÉS
- [DTO (Data Transfer Objects)](#dto-data-transfer-objects)
- [API Resources dans Laravel](#api-resources-dans-laravel)
- [Controllers API vs Controllers Web](#controllers-api-vs-controllers-web)
- [Gestion d'erreurs et exceptions](#gestion-derreurs-et-exceptions)
- [Pagination](#pagination)
- [Filtrage et tri](#filtrage-et-tri)

### PARTIE 3: IMPLÉMENTATION
- [Configuration initiale de l'API](#configuration-initiale-de-lapi)
- [Création des API Resources](#création-des-api-resources)
- [Création des DTOs](#création-des-dtos)
- [Implémentation des contrôleurs d'API](#implémentation-des-contrôleurs-dapi)
- [Routes API](#routes-api)
- [Exemples d'utilisation](#exemples-dutilisation)

### RESSOURCES
- [Commandes utiles pour les API Laravel](#commandes-utiles-pour-les-api-laravel)
- [Ressources complémentaires](#ressources-complémentaires)

---

# PARTIE 1: FONDAMENTAUX DES API REST

## Introduction aux API REST

Une **API** (Application Programming Interface) est un ensemble de règles qui permettent à différentes applications de communiquer entre elles. Une **API REST** (REpresentational State Transfer) est un style d'architecture pour la création d'API web qui utilise des méthodes HTTP standards pour effectuer des opérations sur des ressources.

### 🔹 Pourquoi utiliser une API REST?

1. **Séparation du frontend et du backend** : Permet de développer indépendamment l'interface utilisateur et la logique métier
2. **Multi-plateformes** : Une seule API peut servir plusieurs clients (web, mobile, desktop)
3. **Scalabilité** : Facilite la mise à l'échelle de votre application
4. **Interopérabilité** : Permet l'intégration avec des systèmes tiers
5. **Mise en cache** : Optimise les performances grâce à la mise en cache des réponses

### 🔹 Composants clés d'une API REST

- **Ressources** : Objets ou données sur lesquels l'API effectue des opérations (ex: utilisateurs, articles, produits)
- **URI (Uniform Resource Identifier)** : Points de terminaison (endpoints) qui identifient les ressources (ex: `/api/users`)
- **Méthodes HTTP** : Actions qui peuvent être effectuées sur les ressources (GET, POST, PUT, DELETE, etc.)
- **Représentation des données** : Format des données échangées (généralement JSON ou XML)
- **Stateless** : Chaque requête contient toutes les informations nécessaires pour être comprise et traitée

## API RESTful vs API traditionnelle

Pour comprendre les avantages des API REST, comparons-les avec les approches traditionnelles.

### 🔹 API traditionnelle (RPC ou SOAP)

- Utilise souvent un seul point d'entrée (endpoint) pour plusieurs opérations
- Méthodes personnalisées pour chaque action (ex: `getUserById`, `createNewUser`)
- Généralement basée sur des actions plutôt que sur des ressources
- Souvent plus verbeuse et moins intuitive

**Exemple d'API traditionnelle :**
```
/api/getUserById?id=123
/api/createNewUser
/api/deleteUser?id=123
```

### 🔹 API RESTful

- Utilise des URI distincts pour identifier les ressources
- S'appuie sur les méthodes HTTP standard pour définir les actions
- Basée sur les ressources plutôt que sur les actions
- Plus simple et plus intuitive à utiliser

**Exemple d'API RESTful :**
```
GET /api/users/123         # Obtenir l'utilisateur avec l'ID 123
POST /api/users            # Créer un nouvel utilisateur
DELETE /api/users/123      # Supprimer l'utilisateur avec l'ID 123
```

## Principes REST et bonnes pratiques

REST est guidé par plusieurs principes fondamentaux qui assurent une API efficace et maintenable.

### 🔹 Principes fondamentaux

1. **Interface uniforme** : Utilisation cohérente des URI et des méthodes HTTP
2. **Sans état (Stateless)** : Chaque requête contient toutes les informations nécessaires
3. **Mise en cache** : Les réponses indiquent si elles peuvent être mises en cache
4. **Système en couches** : Le client ne sait pas s'il communique directement avec le serveur
5. **Architecture client-serveur** : Séparation des préoccupations
6. **Code à la demande (facultatif)** : Le serveur peut étendre les fonctionnalités du client

### 🔹 Bonnes pratiques pour une API RESTful

1. **Utiliser les noms pluriels pour les ressources** : `/api/users` plutôt que `/api/user`
2. **Utiliser les méthodes HTTP de manière appropriée** :
   - `GET` pour récupérer des données
   - `POST` pour créer une ressource
   - `PUT` pour mettre à jour une ressource complète
   - `PATCH` pour mettre à jour partiellement une ressource
   - `DELETE` pour supprimer une ressource

3. **Utiliser les codes de statut HTTP appropriés** :
   - `200 OK` pour une requête réussie
   - `201 Created` pour une création réussie
   - `400 Bad Request` pour une requête invalide
   - `404 Not Found` pour une ressource non trouvée
   - `500 Internal Server Error` pour une erreur serveur

4. **Structure hiérarchique des ressources** : 
   ```
   /api/categories/123/products
   /api/products/456/reviews
   ```

5. **Versionnement de l'API** : 
   ```
   /api/v1/users
   /api/v2/users
   ```

6. **Pagination pour les collections** : 
   ```
   /api/users?page=2&per_page=20
   ```

7. **Filtrage, tri et recherche** : 
   ```
   /api/users?status=active&sort=name&search=john
   ```

8. **Utiliser les en-têtes HTTP pour les métadonnées** : Informations sur le contenu, la mise en cache, etc.

9. **Format de réponse cohérent** : Structure JSON cohérente pour les réponses

10. **Documentation complète** : Documenter chaque endpoint, paramètre et code de statut

## Structure des requêtes et réponses HTTP

Pour comprendre comment fonctionnent les API REST, il est essentiel de connaître la structure des requêtes et des réponses HTTP.

### 🔹 Structure d'une requête HTTP

Une requête HTTP comporte plusieurs parties :

1. **Méthode HTTP** (ou verbe) : Indique l'action à effectuer
   - `GET` : Récupérer des données
   - `POST` : Créer une ressource
   - `PUT` : Mettre à jour une ressource complète
   - `PATCH` : Mettre à jour partiellement une ressource
   - `DELETE` : Supprimer une ressource
   - `HEAD` : Similaire à GET mais ne renvoie que les en-têtes
   - `OPTIONS` : Obtenir les méthodes disponibles pour une ressource

2. **URL** : Adresse de la ressource
   - Exemple : `https://api.example.com/users/123`

3. **En-têtes (Headers)** : Métadonnées de la requête
   - `Content-Type` : Format des données envoyées (ex: `application/json`)
   - `Authorization` : Informations d'authentification
   - `Accept` : Format des données attendues
   - `User-Agent` : Identification du client

4. **Corps (Body)** : Données envoyées au serveur (pour POST, PUT, PATCH)
   - Généralement au format JSON pour les API REST

**Exemple de requête HTTP :**
```http
POST /api/users HTTP/1.1
Host: api.example.com
Content-Type: application/json
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "secure_password"
}
```

### 🔹 Structure d'une réponse HTTP

Une réponse HTTP se compose de :

1. **Code de statut** : Résultat de la requête (200 OK, 404 Not Found, etc.)

2. **En-têtes (Headers)** : Métadonnées de la réponse
   - `Content-Type` : Format des données renvoyées
   - `Cache-Control` : Directives de mise en cache
   - `Content-Length` : Taille du corps de la réponse

3. **Corps (Body)** : Données renvoyées par le serveur
   - Généralement au format JSON pour les API REST

**Exemple de réponse HTTP :**
```http
HTTP/1.1 201 Created
Content-Type: application/json
Cache-Control: no-cache
Content-Length: 157

{
  "id": 456,
  "name": "John Doe",
  "email": "john@example.com",
  "created_at": "2023-07-21T14:30:15Z",
  "updated_at": "2023-07-21T14:30:15Z"
}
```

## Les codes de statut HTTP

Les codes de statut HTTP indiquent le résultat d'une requête. Ils sont regroupés en cinq classes :

### 🔹 Codes 1xx : Information

Rarement utilisés dans les API REST, ils indiquent une réponse provisoire.
- `100 Continue` : Le serveur a reçu les en-têtes et le client peut continuer à envoyer le corps

### 🔹 Codes 2xx : Succès

Indiquent que la requête a été traitée avec succès.

- `200 OK` : Requête traitée avec succès
- `201 Created` : Ressource créée avec succès
- `202 Accepted` : Requête acceptée pour traitement (asynchrone)
- `204 No Content` : Requête traitée avec succès, mais pas de contenu à renvoyer

### 🔹 Codes 3xx : Redirection

Indiquent que le client doit effectuer une action supplémentaire pour compléter la requête.

- `301 Moved Permanently` : La ressource a été déplacée définitivement
- `302 Found` : La ressource est temporairement disponible à une autre URL
- `304 Not Modified` : La ressource n'a pas été modifiée depuis la dernière requête

### 🔹 Codes 4xx : Erreur côté client

Indiquent que la requête contient une erreur ou ne peut pas être traitée.

- `400 Bad Request` : Requête mal formée ou invalide
- `401 Unauthorized` : Authentification requise ou échouée
- `403 Forbidden` : Le client n'a pas les droits pour accéder à la ressource
- `404 Not Found` : Ressource non trouvée
- `405 Method Not Allowed` : Méthode HTTP non autorisée pour cette ressource
- `409 Conflict` : Conflit avec l'état actuel de la ressource
- `422 Unprocessable Entity` : La requête est bien formée mais contient des erreurs sémantiques

### 🔹 Codes 5xx : Erreur côté serveur

Indiquent qu'il y a eu une erreur lors du traitement de la requête.

- `500 Internal Server Error` : Erreur générique côté serveur
- `502 Bad Gateway` : Le serveur a reçu une réponse invalide d'un serveur en amont
- `503 Service Unavailable` : Le serveur est temporairement indisponible
- `504 Gateway Timeout` : Le serveur n'a pas obtenu de réponse à temps d'un serveur en amont

## La sécurité des API

La sécurité est un aspect critique des API. Voici les principales considérations de sécurité pour une API REST :

### 🔹 Authentification et autorisation

1. **Authentification** : Vérifier l'identité de l'utilisateur
   - **API Keys** : Clés uniques assignées aux clients
   - **OAuth 2.0** : Protocole d'autorisation standard
   - **JWT (JSON Web Tokens)** : Tokens contenant des informations d'authentification
   - **Basic Auth** : Nom d'utilisateur et mot de passe encodés en Base64

2. **Autorisation** : Vérifier les permissions de l'utilisateur
   - **RBAC (Role-Based Access Control)** : Contrôle d'accès basé sur les rôles
   - **ABAC (Attribute-Based Access Control)** : Contrôle d'accès basé sur les attributs
   - **Scope-based** : Permissions basées sur des scopes

### 🔹 Meilleures pratiques de sécurité

1. **Utiliser HTTPS** : Chiffrer toutes les communications
2. **Valider toutes les entrées** : Prévenir les injections et autres attaques
3. **Limiter le taux de requêtes (Rate Limiting)** : Prévenir les attaques par force brute et DoS
4. **Masquer les informations sensibles** : Ne pas exposer plus d'informations que nécessaire
5. **Utiliser des jetons à courte durée de vie** : Limiter la durée de validité des tokens
6. **Journaliser les activités** : Surveiller et enregistrer les accès à l'API
7. **Utiliser des en-têtes de sécurité HTTP** :
   - `Content-Security-Policy`
   - `X-XSS-Protection`
   - `X-Content-Type-Options`
8. **Politique CORS (Cross-Origin Resource Sharing)** : Contrôler quels domaines peuvent accéder à l'API

# PARTIE 2: CONCEPTS AVANCÉS

## DTO (Data Transfer Objects)

Les DTO sont des objets simples utilisés pour transférer des données entre différentes couches d'une application.

### 🔹 Qu'est-ce qu'un DTO?

Un **Data Transfer Object (DTO)** est un modèle de conception qui permet de transférer des données entre différentes couches d'une application. C'est essentiellement une classe plate qui contient uniquement des propriétés, des accesseurs (getters) et des mutateurs (setters), sans logique métier.

### 🔹 Pourquoi utiliser des DTOs?

1. **Séparation des préoccupations** : Distinguer les objets métier des objets de transfert
2. **Sécurité** : Contrôler précisément quelles données sont exposées dans l'API
3. **Validation** : Centraliser la validation des données entrantes
4. **Évolution de l'API** : Modifier la structure interne sans affecter l'interface publique
5. **Réduction du sur-fetching** : Ne transmettre que les données nécessaires
6. **Documentation** : Définir clairement la structure des données échangées

### 🔹 Structure d'un DTO dans Laravel

En PHP/Laravel, un DTO peut être implémenté de différentes façons :

**Méthode simple - Classe avec propriétés publiques :**
```php
class UserDTO
{
    public string $name;
    public string $email;
    public ?string $profile_image = null;
    
    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->profile_image = $data['profile_image'] ?? null;
    }
    
    public static function fromRequest(Request $request): self
    {
        return new self($request->validated());
    }
    
    public static function fromModel(User $user): self
    {
        return new self([
            'name' => $user->name,
            'email' => $user->email,
            'profile_image' => $user->profile_image,
        ]);
    }
    
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'profile_image' => $this->profile_image,
        ];
    }
}
```

**Méthode avec attributs en lecture seule :**
```php
class UserDTO
{
    private string $name;
    private string $email;
    private ?string $profile_image;
    
    public function __construct(string $name, string $email, ?string $profile_image = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->profile_image = $profile_image;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getEmail(): string
    {
        return $this->email;
    }
    
    public function getProfileImage(): ?string
    {
        return $this->profile_image;
    }
    
    public static function fromRequest(Request $request): self
    {
        $validated = $request->validated();
        return new self(
            $validated['name'],
            $validated['email'],
            $validated['profile_image'] ?? null
        );
    }
    
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'profile_image' => $this->profile_image,
        ];
    }
}
```

### 🔹 Utilisation des DTOs dans une API

```php
class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        // Créer un DTO à partir de la requête validée
        $userDTO = UserDTO::fromRequest($request);
        
        // Utiliser le DTO pour créer un utilisateur
        $user = User::create($userDTO->toArray());
        
        // Retourner une réponse
        return response()->json([
            'data' => $userDTO->toArray(),
            'message' => 'User created successfully'
        ], 201);
    }
    
    public function update(UpdateUserRequest $request, User $user)
    {
        // Créer un DTO à partir de la requête validée
        $userDTO = UserDTO::fromRequest($request);
        
        // Mettre à jour l'utilisateur avec les données du DTO
        $user->update($userDTO->toArray());
        
        // Retourner une réponse
        return response()->json([
            'data' => $userDTO->toArray(),
            'message' => 'User updated successfully'
        ]);
    }
}
```

## API Resources dans Laravel

Laravel fournit un excellent moyen de transformer les modèles Eloquent en réponses JSON via les API Resources.

### 🔹 Qu'est-ce qu'une API Resource?

Une **API Resource** dans Laravel est une classe qui transforme un modèle ou une collection de modèles en structure JSON. Les ressources permettent de formatter et structurer les données de manière cohérente et contrôlée avant de les renvoyer au client.

### 🔹 Avantages des API Resources

1. **Transformation standardisée** : Format cohérent pour toutes les réponses
2. **Contrôle précis** : Décider quels attributs exposer
3. **Conditionnalité** : Inclure ou exclure des données en fonction de conditions
4. **Relations imbriquées** : Formater facilement les relations imbriquées
5. **Évolution de l'API** : Modifier le format de réponse sans changer les modèles
6. **Pagination intégrée** : Support natif de la pagination

### 🔹 Types d'API Resources dans Laravel

Laravel propose deux types de ressources :

1. **Resource** : Pour un seul modèle
2. **ResourceCollection** : Pour une collection de modèles

### 🔹 Création d'une Resource

```bash
php artisan make:resource UserResource
```

Cela génère une classe dans `app/Http/Resources` :

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile_image_url' => $this->profile_image_url,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            // Relations conditionnelles
            'roles' => $this->when($request->user()?->hasRole('admin'), function () {
                return $this->roles->pluck('name');
            }),
            // Relations chargées (eager loaded)
            'expenses' => ExpenseResource::collection($this->whenLoaded('expenses')),
            'incomes' => IncomeResource::collection($this->whenLoaded('incomes')),
        ];
    }
    
    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'version' => '1.0.0',
            ],
        ];
    }
}
```

### 🔹 Création d'une ResourceCollection

```bash
php artisan make:resource UserCollection
```

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
    
    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'total_users' => $this->collection->count(),
                'version' => '1.0.0',
            ],
        ];
    }
}
```

### 🔹 Utilisation des API Resources dans un contrôleur

```php
class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'expenses', 'incomes'])->paginate(10);
        return UserResource::collection($users);
    }
    
    public function show(User $user)
    {
        $user->load(['roles', 'expenses', 'incomes']);
        return new UserResource($user);
    }
}
```

## Controllers API vs Controllers Web

Les contrôleurs API diffèrent des contrôleurs web en termes de responsabilités et de comportement.

### 🔹 Différences clés

| Contrôleurs Web | Contrôleurs API |
|-----------------|-----------------|
| Renvoient généralement des vues HTML | Renvoient des données au format JSON/XML |
| Sessions et cookies pour l'authentification | Stateless, utilisent généralement des tokens |
| Utilisent des redirections | Utilisent des codes de statut HTTP |
| Renvoient des messages flash | Incluent des messages dans la réponse JSON |
| Optimisés pour les interfaces utilisateur | Optimisés pour la consommation par d'autres applications |

### 🔹 Structure d'un contrôleur API dans Laravel

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->validated());
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
```

### 🔹 Conventions pour les contrôleurs API

1. **Namespace spécifique** : Généralement `App\Http\Controllers\Api`
2. **Suffixe "ApiController"** : Distingue les contrôleurs API des contrôleurs web
3. **Réponses JSON** : Toujours renvoyer des réponses JSON avec les codes de statut appropriés
4. **Format de réponse cohérent** : Structure uniforme pour toutes les réponses
5. **Validation stricte** : Utiliser des Form Requests pour la validation
6. **Gestion d'erreurs explicite** : Renvoyer des messages d'erreur clairs
7. **Contrôle des ressources incluses** : Permettre au client de spécifier quelles relations charger

## Gestion d'erreurs et exceptions

Une bonne gestion des erreurs est essentielle pour créer une API robuste et conviviale.

### 🔹 Types d'erreurs dans une API

1. **Erreurs de validation** : Données d'entrée invalides
2. **Erreurs d'authentification** : Problèmes liés à l'identité de l'utilisateur
3. **Erreurs d'autorisation** : Problèmes liés aux permissions
4. **Erreurs de ressource** : Ressource non trouvée ou conflit
5. **Erreurs serveur** : Problèmes internes du serveur

### 🔹 Gestion des exceptions dans Laravel

Laravel fournit un mécanisme robuste pour gérer les exceptions. Vous pouvez personnaliser ce comportement dans `app/Exceptions/Handler.php` :

```php
<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                // Erreurs de validation
                if ($e instanceof ValidationException) {
                    return response()->json([
                        'message' => 'Validation error',
                        'errors' => $e->errors(),
                    ], 422);
                }
                
                // Ressource non trouvée
                if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                    return response()->json([
                        'message' => 'Resource not found'
                    ], 404);
                }
                
                // Erreur d'autorisation
                if ($e instanceof AuthorizationException) {
                    return response()->json([
                        'message' => 'Unauthorized action'
                    ], 403);
                }
                
                // Autres erreurs (en production, ne pas exposer les détails)
                $status = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
                
                return response()->json([
                    'message' => app()->environment('production') ? 'Server error' : $e->getMessage()
                ], $status);
            }
        });
    }
}
```

### 🔹 Format de réponse d'erreur

Il est important d'avoir un format cohérent pour les réponses d'erreur :

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ],
    "password": [
      "The password field is required.",
      "The password must be at least 8 characters."
    ]
  }
}
```



# 🌐 Pagination et Filtrage dans API REST avec Laravel 11

Dans cette section, nous allons approfondir les concepts avancés de pagination, filtrage et tri, puis nous passerons à l'implémentation complète des contrôleurs d'API pour notre application de gestion de dépenses.

## 📊 Pagination
La pagination est cruciale pour gérer efficacement de grandes quantités de données.

### 🔹 Pagination dans Laravel

Laravel facilite la pagination des résultats avec Eloquent :

```php
// Pagination simple
$users = User::paginate(15);

// Pagination avec conditions
$users = User::where('is_active', true)->paginate(15);

// Pagination avec chargement des relations
$users = User::with(['roles', 'expenses'])->paginate(15);
```

### 🔹 Structure d'une réponse paginée

Par défaut, Laravel génère une structure comme celle-ci :



### 🔹 Structure d'une réponse paginée

Par défaut, Laravel génère une structure comme celle-ci pour les résultats paginés :

```json
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    {
      "id": 2,
      "name": "Jane Smith",
      "email": "jane@example.com"
    }
  ],
  "links": {
    "first": "http://example.com/api/users?page=1",
    "last": "http://example.com/api/users?page=5",
    "prev": null,
    "next": "http://example.com/api/users?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "path": "http://example.com/api/users",
    "per_page": 15,
    "to": 15,
    "total": 75
  }
}
```

### 🔹 Pagination avec API Resources

Les API Resources de Laravel s'intègrent parfaitement avec la pagination :

```php
// Dans un contrôleur
public function index()
{
    $users = User::paginate(15);
    return UserResource::collection($users);
}
```

Cette méthode conserve automatiquement les métadonnées de pagination dans la réponse JSON.

### 🔹 Personnaliser le nombre d'éléments par page

Vous pouvez permettre au client de spécifier combien d'éléments il souhaite par page :

```php
public function index(Request $request)
{
    $perPage = $request->input('per_page', 15); // 15 par défaut
    $users = User::paginate($perPage);
    
    return UserResource::collection($users);
}
```

## 🔍 Filtrage et tri

Le filtrage et le tri sont des fonctionnalités essentielles pour permettre aux clients d'obtenir précisément les données dont ils ont besoin.

### 🔹 Implémentation du filtrage

Pour implémenter le filtrage, nous pouvons utiliser les paramètres de requête :

```php
public function index(Request $request)
{
    $query = User::query();
    
    // Filtrage par statut
    if ($request->has('status')) {
        $query->where('is_active', $request->status === 'active');
    }
    
    // Filtrage par rôle
    if ($request->has('role')) {
        $query->whereHas('roles', function ($q) use ($request) {
            $q->where('name', $request->role);
        });
    }
    
    // Recherche par nom ou email
    if ($request->has('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('email', 'like', "%{$request->search}%");
        });
    }
    
    $users = $query->paginate(15);
    return UserResource::collection($users);
}
```

### 🔹 Implémentation du tri

Le tri peut également être implémenté à l'aide des paramètres de requête :

```php
public function index(Request $request)
{
    $query = User::query();
    
    // Tri
    $sortField = $request->input('sort', 'created_at');
    $sortDirection = $request->input('direction', 'desc');
    
    // Liste des champs de tri autorisés
    $allowedSortFields = ['id', 'name', 'email', 'created_at', 'updated_at'];
    
    // Vérifier que le champ de tri est autorisé
    if (in_array($sortField, $allowedSortFields)) {
        $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
    }
    
    $users = $query->paginate(15);
    return UserResource::collection($users);
}
```

### 🔹 Chargement dynamique des relations (Includes)

Une autre fonctionnalité utile est de permettre au client de spécifier quelles relations il souhaite charger :

```php
public function index(Request $request)
{
    $query = User::query();
    
    // Inclure les relations
    if ($request->has('include')) {
        $includes = explode(',', $request->input('include'));
        $allowedIncludes = ['roles', 'expenses', 'incomes'];
        
        $validIncludes = array_intersect($includes, $allowedIncludes);
        if (!empty($validIncludes)) {
            $query->with($validIncludes);
        }
    }
    
    $users = $query->paginate(15);
    return UserResource::collection($users);
}
```

Avec cette approche, un client peut demander des utilisateurs avec leurs rôles et dépenses en faisant une requête comme : `/api/users?include=roles,expenses`.

# PARTIE 3: IMPLÉMENTATION

Avant de commencer l'implémentation de notre API, créons tous les fichiers nécessaires. Cette étape préparatoire garantira une structure organisée et nous permettra de travailler efficacement.

## 🚀 Création des fichiers nécessaires à l'implémentation de l'API

```markdown
# Création des répertoires
mkdir -p app/DTOs
mkdir -p app/Http/Controllers/Api/V1
mkdir -p app/Http/Resources
mkdir -p app/Http/Requests/Api

# Création des DTOs
touch app/DTOs/UserDTO.php
touch app/DTOs/ExpenseDTO.php
touch app/DTOs/IncomeDTO.php
touch app/DTOs/CategoryDTO.php

# Création des Resources
touch app/Http/Resources/UserResource.php
touch app/Http/Resources/ExpenseResource.php
touch app/Http/Resources/IncomeResource.php
touch app/Http/Resources/CategoryResource.php

# Création des contrôleurs API
touch app/Http/Controllers/Api/V1/UserApiController.php
touch app/Http/Controllers/Api/V1/ExpenseApiController.php
touch app/Http/Controllers/Api/V1/IncomeApiController.php
touch app/Http/Controllers/Api/V1/CategoryApiController.php
touch app/Http/Controllers/Api/V1/AuthApiController.php

# Création des Form Requests pour l'API
touch app/Http/Requests/Api/StoreUserRequest.php
touch app/Http/Requests/Api/UpdateUserRequest.php
touch app/Http/Requests/Api/StoreExpenseRequest.php
touch app/Http/Requests/Api/UpdateExpenseRequest.php
touch app/Http/Requests/Api/StoreIncomeRequest.php
touch app/Http/Requests/Api/UpdateIncomeRequest.php
touch app/Http/Requests/Api/StoreCategoryRequest.php
touch app/Http/Requests/Api/UpdateCategoryRequest.php
```

Cette structure de fichiers suit les bonnes pratiques Laravel pour le développement d'API :
- Les **DTOs** (Data Transfer Objects) serviront d'intermédiaires pour le transfert des données
- Les **Resources** transformeront nos modèles en réponses JSON structurées
- Les **Controllers API** gèreront la logique métier pour chaque entité
- Les **Form Requests** valideront les données entrantes

Nous pouvons maintenant procéder à l'implémentation de chaque composant.

## ⚙️ Configuration initiale de l'API

Avant de commencer à implémenter nos API, nous devons configurer correctement l'environnement.

### 🔹 Préfixe d'API

Laravel permet de préfixer facilement toutes les routes API dans le fichier `routes/api.php`. Ces routes sont automatiquement préfixées par `/api`.

### 🔹 Versionnement de l'API

Il est recommandé de versionner votre API pour assurer la compatibilité à long terme :

```php
// routes/api.php
Route::prefix('v1')->group(function () {
    // Routes API v1
    Route::apiResource('users', Api\V1\UserApiController::class);
});
```

### 🔹 Middleware pour l'API

Laravel inclut plusieurs middleware utiles pour les API :

```php
// routes/api.php
Route::middleware(['auth:sanctum', 'throttle:api'])->prefix('v1')->group(function () {
    // Routes protégées
});
```

- `auth:sanctum` : Authentification avec Laravel Sanctum
- `throttle:api` : Limitation de débit pour éviter les abus

## 🛠️ Création des API Resources

Pour notre application de gestion de dépenses, nous allons créer des API Resources pour chaque modèle. Ces ressources vont transformer nos modèles Eloquent en réponses JSON structurées.

### 🔹 UserResource

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile_image_url' => $this->profile_image_url,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->pluck('name');
            }),
            // Relations conditionnelles
            'expenses_count' => $this->when($request->has('include_counts'), function () {
                return $this->expenses->count();
            }),
            'incomes_count' => $this->when($request->has('include_counts'), function () {
                return $this->incomes->count();
            }),
            // Relations chargées (eager loaded)
            'expenses' => ExpenseResource::collection($this->whenLoaded('expenses')),
            'incomes' => IncomeResource::collection($this->whenLoaded('incomes')),
        ];
    }
}
```

### 🔹 ExpenseResource

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'description' => $this->description,
            'date' => $this->date->format('Y-m-d'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'user' => new UserResource($this->whenLoaded('user')),
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
```

### 🔹 IncomeResource

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'description' => $this->description,
            'date' => $this->date->format('Y-m-d'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'user' => new UserResource($this->whenLoaded('user')),
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
```

### 🔹 CategoryResource

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            // Relations conditionnelles
            'expenses_count' => $this->when($request->has('include_counts'), function () {
                return $this->expenses->count();
            }),
            'incomes_count' => $this->when($request->has('include_counts'), function () {
                return $this->incomes->count();
            }),
            // Relations chargées (eager loaded)
            'user' => new UserResource($this->whenLoaded('user')),
            'expenses' => ExpenseResource::collection($this->whenLoaded('expenses')),
            'incomes' => IncomeResource::collection($this->whenLoaded('incomes')),
        ];
    }
}
```

## 🧱 Création des DTOs

Maintenant, créons les DTOs pour nos modèles. Ces objets serviront d'intermédiaires pour transférer les données entre les couches de notre application.

### 🔹 Dossier pour les DTOs

```bash
mkdir -p app/DTOs
```

### 🔹 UserDTO

```php
<?php

namespace App\DTOs;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $password = null,
        public readonly ?string $profile_image = null,
        public readonly bool $is_active = true,
        public readonly ?string $role = 'user'
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validated();
        
        return new self(
            name: $validated['name'],
            email: $validated['email'],
            password: isset($validated['password']) ? Hash::make($validated['password']) : null,
            profile_image: $validated['profile_image'] ?? null,
            is_active: $validated['is_active'] ?? true,
            role: $validated['role'] ?? 'user'
        );
    }

    public static function fromModel(User $user): self
    {
        return new self(
            name: $user->name,
            email: $user->email,
            profile_image: $user->profile_image,
            is_active: $user->is_active,
            role: $user->roles->first()?->name ?? 'user'
        );
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
        ];
        
        if ($this->password) {
            $data['password'] = $this->password;
        }
        
        if ($this->profile_image) {
            $data['profile_image'] = $this->profile_image;
        }
        
        return $data;
    }
}
```

### 🔹 ExpenseDTO

```php
<?php

namespace App\DTOs;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseDTO
{
    public function __construct(
        public readonly float $amount,
        public readonly string $description,
        public readonly string $date,
        public readonly int $category_id,
        public readonly int $user_id
    ) {
    }

    public static function fromRequest(Request $request, int $userId = null): self
    {
        $validated = $request->validated();
        
        return new self(
            amount: (float) $validated['amount'],
            description: $validated['description'],
            date: $validated['date'],
            category_id: (int) $validated['category_id'],
            user_id: $userId ?? auth()->id()
        );
    }

    public static function fromModel(Expense $expense): self
    {
        return new self(
            amount: $expense->amount,
            description: $expense->description,
            date: $expense->date->format('Y-m-d'),
            category_id: $expense->category_id,
            user_id: $expense->user_id
        );
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'description' => $this->description,
            'date' => $this->date,
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
        ];
    }
}
```


### 🔹 IncomeDTO 

```php
<?php

namespace App\DTOs;

use App\Models\Income;
use Illuminate\Http\Request;

class IncomeDTO
{
    public function __construct(
        public readonly float $amount,
        public readonly string $description,
        public readonly string $date,
        public readonly int $category_id,
        public readonly int $user_id
    ) {
    }

    public static function fromRequest(Request $request, int $userId = null): self
    {
        $validated = $request->validated();
        
        return new self(
            amount: (float) $validated['amount'],
            description: $validated['description'],
            date: $validated['date'],
            category_id: (int) $validated['category_id'],
            user_id: $userId ?? auth()->id()
        );
    }

    public static function fromModel(Income $income): self
    {
        return new self(
            amount: $income->amount,
            description: $income->description,
            date: $income->date->format('Y-m-d'),
            category_id: $income->category_id,
            user_id: $income->user_id
        );
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'description' => $this->description,
            'date' => $this->date,
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
        ];
    }
}
```

### 🔹 CategoryDTO

```php
<?php

namespace App\DTOs;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $user_id
    ) {
    }

    public static function fromRequest(Request $request, int $userId = null): self
    {
        $validated = $request->validated();
        
        return new self(
            name: $validated['name'],
            user_id: $userId ?? auth()->id()
        );
    }

    public static function fromModel(Category $category): self
    {
        return new self(
            name: $category->name,
            user_id: $category->user_id
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'user_id' => $this->user_id,
        ];
    }
}
```

# 🌐 Implémentation des contrôleurs d'API

Maintenant que nous avons créé nos DTOs et nos API Resources, nous allons implémenter les contrôleurs d'API qui utiliseront ces composants. Ces contrôleurs utiliseront aussi les politiques d'autorisation que nous avons mises en place précédemment.

## 🔹 UserApiController

Le `UserApiController` gérera toutes les opérations relatives aux utilisateurs via l'API.

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;

class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        
        $query = User::query();
        
        // Filtrage par statut
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Filtrage par rôle
        if ($request->has('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        // Recherche par nom ou email
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
        
        // Tri
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        // Liste des champs de tri autorisés
        $allowedSortFields = ['id', 'name', 'email', 'created_at', 'updated_at'];
        
        // Vérifier que le champ de tri est autorisé
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }
        
        // Inclure les relations
        if ($request->has('include')) {
            $includes = explode(',', $request->input('include'));
            $allowedIncludes = ['roles', 'expenses', 'incomes'];
            
            $validIncludes = array_intersect($includes, $allowedIncludes);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }
        
        $users = $query->paginate($request->input('per_page', 15));
        
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);
        
        // Créer un DTO à partir de la requête validée
        $userDTO = UserDTO::fromRequest($request);
        
        // Créer l'utilisateur
        $user = User::create($userDTO->toArray());
        
        // Assigner le rôle
        if ($request->has('role')) {
            $role = Role::where('name', $request->role)->first();
            if ($role) {
                $user->assignRole($role);
            }
        } else {
            $user->assignRole('user'); // Rôle par défaut
        }
        
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        $this->authorize('view', $user);
        
        // Inclure les relations
        if ($request->has('include')) {
            $includes = explode(',', $request->input('include'));
            $allowedIncludes = ['roles', 'expenses', 'incomes'];
            
            $validIncludes = array_intersect($includes, $allowedIncludes);
            if (!empty($validIncludes)) {
                $user->load($validIncludes);
            }
        }
        
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        
        // Créer un DTO à partir de la requête validée
        $userDTO = UserDTO::fromRequest($request);
        
        // Mettre à jour l'utilisateur
        $user->update($userDTO->toArray());
        
        // Mettre à jour le rôle si nécessaire
        if ($request->has('role') && $request->user()->hasRole('admin')) {
            $user->syncRoles([$request->role]);
        }
        
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot delete your own account'
            ], Response::HTTP_FORBIDDEN);
        }
        
        $user->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    
    /**
     * Toggle user active status.
     */
    public function toggleActive(User $user)
    {
        $this->authorize('toggleActive', $user);
        
        // Empêcher de se bloquer soi-même
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot block your own account'
            ], Response::HTTP_FORBIDDEN);
        }
        
        $user->is_active = !$user->is_active;
        $user->save();
        
        return new UserResource($user);
    }
}
```

## 🔹 ExpenseApiController

Le `ExpenseApiController` gérera toutes les opérations relatives aux dépenses via l'API.

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\ExpenseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ExpenseApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Expense::query();
        
        // Si pas admin, ne montrer que les dépenses de l'utilisateur connecté
        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        } else if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filtrage par catégorie
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filtrage par date
        if ($request->has('date_start')) {
            $query->where('date', '>=', $request->date_start);
        }
        
        if ($request->has('date_end')) {
            $query->where('date', '<=', $request->date_end);
        }
        
        // Filtrage par montant
        if ($request->has('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        
        if ($request->has('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }
        
        // Recherche par description
        if ($request->has('search')) {
            $query->where('description', 'like', "%{$request->search}%");
        }
        
        // Tri
        $sortField = $request->input('sort', 'date');
        $sortDirection = $request->input('direction', 'desc');
        
        $allowedSortFields = ['id', 'date', 'amount', 'created_at'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }
        
        // Inclure les relations
        if ($request->has('include')) {
            $includes = explode(',', $request->input('include'));
            $allowedIncludes = ['user', 'category'];
            
            $validIncludes = array_intersect($includes, $allowedIncludes);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }
        
        $expenses = $query->paginate($request->input('per_page', 15));
        
        return ExpenseResource::collection($expenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        // Créer un DTO à partir de la requête validée
        $expenseDTO = ExpenseDTO::fromRequest($request, auth()->id());
        
        // Autoriser l'action
        $this->authorize('create', Expense::class);
        
        // Vérifier que la catégorie appartient à l'utilisateur
        $category = Category::findOrFail($expenseDTO->category_id);
        if ($category->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'The category does not belong to you'
            ], Response::HTTP_FORBIDDEN);
        }
        
        // Créer la dépense
        $expense = Expense::create($expenseDTO->toArray());
        
        return new ExpenseResource($expense);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Expense $expense)
    {
        // Autoriser l'action
        $this->authorize('view', $expense);
        
        // Inclure les relations
        if ($request->has('include')) {
            $includes = explode(',', $request->input('include'));
            $allowedIncludes = ['user', 'category'];
            
            $validIncludes = array_intersect($includes, $allowedIncludes);
            if (!empty($validIncludes)) {
                $expense->load($validIncludes);
            }
        }
        
        return new ExpenseResource($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        // Autoriser l'action
        $this->authorize('update', $expense);
        
        // Créer un DTO à partir de la requête validée
        $expenseDTO = ExpenseDTO::fromRequest($request, $expense->user_id);
        
        // Vérifier que la catégorie appartient à l'utilisateur
        $category = Category::findOrFail($expenseDTO->category_id);
        if ($category->user_id !== $expense->user_id && !auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'The category does not belong to you'
            ], Response::HTTP_FORBIDDEN);
        }
        
        // Mettre à jour la dépense
        $expense->update($expenseDTO->toArray());
        
        return new ExpenseResource($expense);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        // Autoriser l'action
        $this->authorize('delete', $expense);
        
        $expense->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
```

## 🔹 IncomeApiController

Le `IncomeApiController` gérera toutes les opérations relatives aux revenus via l'API.

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\IncomeDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use App\Http\Resources\IncomeResource;
use App\Models\Category;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class IncomeApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Income::query();
        
        // Si pas admin, ne montrer que les revenus de l'utilisateur connecté
        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        } else if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filtrage par catégorie
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filtrage par date
        if ($request->has('date_start')) {
            $query->where('date', '>=', $request->date_start);
        }
        
        if ($request->has('date_end')) {
            $query->where('date', '<=', $request->date_end);
        }
        
        // Filtrage par montant
        if ($request->has('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        
        if ($request->has('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }
        
        // Recherche par description
        if ($request->has('search')) {
            $query->where('description', 'like', "%{$request->search}%");
        }
        
        // Tri
        $sortField = $request->input('sort', 'date');
        $sortDirection = $request->input('direction', 'desc');
        
        $allowedSortFields = ['id', 'date', 'amount', 'created_at'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }
        
        // Inclure les relations
        if ($request->has('include')) {
            $includes = explode(',', $request->input('include'));
            $allowedIncludes = ['user', 'category'];
            
            $validIncludes = array_intersect($includes, $allowedIncludes);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }
        
        $incomes = $query->paginate($request->input('per_page', 15));
        
        return IncomeResource::collection($incomes);
    }

    # 🌐 Implémentation des contrôleurs d'API (Suite)

## 🔹 IncomeApiController (Suite)

```php
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIncomeRequest $request)
    {
        // Créer un DTO à partir de la requête validée
        $incomeDTO = IncomeDTO::fromRequest($request, auth()->id());
        
        // Autoriser l'action
        $this->authorize('create', Income::class);
        
        // Vérifier que la catégorie appartient à l'utilisateur
        $category = Category::findOrFail($incomeDTO->category_id);
        if ($category->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'The category does not belong to you'
            ], Response::HTTP_FORBIDDEN);
        }
        
        // Créer le revenu
        $income = Income::create($incomeDTO->toArray());
        
        return new IncomeResource($income);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Income $income)
    {
        // Autoriser l'action
        $this->authorize('view', $income);
        
        // Inclure les relations
        if ($request->has('include')) {
            $includes = explode(',', $request->input('include'));
            $allowedIncludes = ['user', 'category'];
            
            $validIncludes = array_intersect($includes, $allowedIncludes);
            if (!empty($validIncludes)) {
                $income->load($validIncludes);
            }
        }
        
        return new IncomeResource($income);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIncomeRequest $request, Income $income)
    {
        // Autoriser l'action
        $this->authorize('update', $income);
        
        // Créer un DTO à partir de la requête validée
        $incomeDTO = IncomeDTO::fromRequest($request, $income->user_id);
        
        // Vérifier que la catégorie appartient à l'utilisateur
        $category = Category::findOrFail($incomeDTO->category_id);
        if ($category->user_id !== $income->user_id && !auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'The category does not belong to you'
            ], Response::HTTP_FORBIDDEN);
        }
        
        // Mettre à jour le revenu
        $income->update($incomeDTO->toArray());
        
        return new IncomeResource($income);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        // Autoriser l'action
        $this->authorize('delete', $income);
        
        $income->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
```

## 🔹 CategoryApiController

Le `CategoryApiController` gérera toutes les opérations relatives aux catégories via l'API.

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\CategoryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CategoryApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Category::query();
        
        // Si pas admin, ne montrer que les catégories de l'utilisateur connecté
        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        } else if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Recherche par nom
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        
        // Tri
        $sortField = $request->input('sort', 'name');
        $sortDirection = $request->input('direction', 'asc');
        
        $allowedSortFields = ['id', 'name', 'created_at'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }
        
        // Inclure les relations
        if ($request->has('include')) {
            $includes = explode(',', $request->input('include'));
            $allowedIncludes = ['user', 'expenses', 'incomes'];
            
            $validIncludes = array_intersect($includes, $allowedIncludes);
            if (!empty($validIncludes)) {
                $query->with($validIncludes);
            }
        }
        
        // Inclure les comptes
        if ($request->has('include_counts')) {
            $query->withCount(['expenses', 'incomes']);
        }
        
        $categories = $query->paginate($request->input('per_page', 15));
        
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        // Créer un DTO à partir de la requête validée
        $categoryDTO = CategoryDTO::fromRequest($request, auth()->id());
        
        // Autoriser l'action
        $this->authorize('create', Category::class);
        
        // Créer la catégorie
        $category = Category::create($categoryDTO->toArray());
        
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Category $category)
    {
        // Autoriser l'action
        $this->authorize('view', $category);
        
        // Inclure les relations
        if ($request->has('include')) {
            $includes = explode(',', $request->input('include'));
            $allowedIncludes = ['user', 'expenses', 'incomes'];
            
            $validIncludes = array_intersect($includes, $allowedIncludes);
            if (!empty($validIncludes)) {
                $category->load($validIncludes);
            }
        }
        
        // Inclure les comptes
        if ($request->has('include_counts')) {
            $category->loadCount(['expenses', 'incomes']);


            # 🌐 Implémentation des contrôleurs d'API (Suite)

## 🔹 CategoryApiController (Suite)

```php
        }
        
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // Autoriser l'action
        $this->authorize('update', $category);
        
        // Créer un DTO à partir de la requête validée
        $categoryDTO = CategoryDTO::fromRequest($request, $category->user_id);
        
        // Mettre à jour la catégorie
        $category->update($categoryDTO->toArray());
        
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Autoriser l'action
        $this->authorize('delete', $category);
        
        // Vérifier si la catégorie est utilisée
        if ($category->expenses()->count() > 0 || $category->incomes()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete a category that is in use'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $category->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
```

## 📡 Routes API

Une fois que tous les contrôleurs sont créés, nous devons configurer les routes API pour y accéder. Voici comment configurer `routes/api.php` pour notre application :

```php
<?php

use App\Http\Controllers\Api\V1\CategoryApiController;
use App\Http\Controllers\Api\V1\ExpenseApiController;
use App\Http\Controllers\Api\V1\IncomeApiController;
use App\Http\Controllers\Api\V1\UserApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route pour obtenir l'utilisateur authentifié
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes API v1
Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    // Routes pour les utilisateurs
    Route::apiResource('users', UserApiController::class);
    Route::patch('users/{user}/toggle-active', [UserApiController::class, 'toggleActive'])->name('api.users.toggle-active');
    
    // Routes pour les dépenses
    Route::apiResource('expenses', ExpenseApiController::class);
    
    // Routes pour les revenus
    Route::apiResource('incomes', IncomeApiController::class);
    
    // Routes pour les catégories
    Route::apiResource('categories', CategoryApiController::class);
});
```

## 🔑 Authentification avec Sanctum

Pour sécuriser notre API, nous utilisons Laravel Sanctum. Sanctum fournit un système d'authentification léger pour les SPA (Single Page Applications), les applications mobiles et les API basées sur des jetons.

### 🔹 Installation et configuration de Sanctum

Sanctum est généralement préinstallé avec Laravel 11, mais assurons-nous qu'il est correctement configuré :

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 🔹 Configuration du garde Sanctum

Assurez-vous que le garde Sanctum est correctement configuré dans `config/auth.php` :

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'sanctum',
        'provider' => 'users',
        'hash' => false,
    ],
],
```

### 🔹 Contrôleur d'authentification pour l'API

Créons un contrôleur spécifique pour gérer l'authentification API :

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        // Vérifier si l'utilisateur est actif
        if (!$user->is_active) {
            return response()->json([
                'message' => 'Your account has been deactivated. Please contact an administrator.'
            ], Response::HTTP_FORBIDDEN);
        }
        
        $deviceName = $request->device_name ?? $request->userAgent() ?? 'Unknown Device';
        $token = $user->createToken($deviceName)->plainTextToken;
        
        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }
    
    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);
        
        // Assigner le rôle "user" par défaut
        $user->assignRole('user');
        
        $deviceName = $request->device_name ?? $request->userAgent() ?? 'Unknown Device';
        $token = $user->createToken($deviceName)->plainTextToken;
        
        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ], Response::HTTP_CREATED);
    }
    
    /**
     * Get the authenticated user.
     */
    public function user(Request $request)
    {
        return new UserResource($request->user());
    }
    
    /**
     * Logout the user (revoke token).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Successfully logged out']);
    }
    
    /**
     * Logout from all devices (revoke all tokens).
     */
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();
        
        return response()->json(['message' => 'Successfully logged out from all devices']);
    }
}
```

### 🔹 Routes d'authentification

Ajoutons les routes d'authentification à notre fichier `routes/api.php` :

```php
// Routes d'authentification (sans middleware d'authentification)
Route::prefix('v1')->group(function () {
    Route::post('login', [AuthApiController::class, 'login'])->name('api.login');
    Route::post('register', [AuthApiController::class, 'register'])->name('api.register');
    
    // Routes protégées par authentification
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user', [AuthApiController::class, 'user'])->name('api.user');
        Route::post('logout', [AuthApiController::class, 'logout'])->name('api.logout');
        Route::post('logout-all', [AuthApiController::class, 'logoutAll'])->name('api.logout.all');
    });
});
```

## 📋 Form Requests pour la validation

Pour maintenir un code propre et bien organisé, nous utilisons des Form Requests pour la validation des données. Voici quelques exemples de Form Requests pour notre application :

### 🔹 StoreExpenseRequest

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // L'autorisation est gérée par les policies
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
```

### 🔹 UpdateExpenseRequest

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // L'autorisation est gérée par les policies
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
```

Vous devrez créer des Form Requests similaires pour les autres modèles (Income, Category, User).

## 🧪 Exemples d'utilisation

Voici quelques exemples de comment utiliser notre API avec des outils comme Postman ou des requêtes HTTP classiques :

### 🔹 Authentification et obtention du token

```http
POST /api/v1/login HTTP/1.1
Host: example.com
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}
```

Réponse :

```json
{
  "token": "1|jkl87fsd98f7sdf98dsf7...",
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@example.com",
    "profile_image_url": "http://example.com/storage/profiles/default-avatar.png",
    "is_active": true,
    "created_at": "2023-07-21 14:30:15",
    "updated_at": "2023-07-21 14:30:15",
    "roles": ["admin"]
  }
}
```

### 🔹 Liste des dépenses avec filtres et tri

```http
GET /api/v1/expenses?category_id=1&amount_min=50&sort=date&direction=desc&include=category HTTP/1.1
Host: example.com
Authorization: Bearer 1|jkl87fsd98f7sdf98dsf7...
```

Réponse :

```json
{
  "data": [
    {
      "id": 5,
      "amount": 75.50,
      "description": "Courses au supermarché",
      "date": "2023-07-20",
      "created_at": "2023-07-20 10:30:15",
      "updated_at": "2023-07-20 10:30:15",
      "category": {
        "id": 1,
        "name": "Alimentation",
        "user_id": 1,
        "created_at": "2023-07-01 12:00:00",
        "updated_at": "2023-07-01 12:00:00"
      }
    },
    ...
  ],
  "links": { ... },
  "meta": { ... }
}
```

## 📚 Commandes utiles pour les API Laravel

```bash
# Créer un contrôleur API
php artisan make:controller Api/V1/UserApiController --api

# Créer une ressource API
php artisan make:resource UserResource

# Créer une collection de ressources API
php artisan make:resource UserCollection --collection

# Créer un Form Request
php artisan make:request StoreUserRequest

# Générer une key pour l'application
php artisan key:generate

# Effacer le cache de l'application
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Vérifier les routes API
php artisan route:list --path=api
```

## 📝 En résumé

Nous avons créé une API REST complète pour notre application de gestion de dépenses. Cette API :

1. Est sécurisée avec Sanctum pour l'authentification
2. Utilise des DTOs pour le transfert des données
3. Utilise des API Resources pour formater les réponses
4. Implémente des contrôleurs pour chaque entité
5. Inclut un système de filtrage, tri et pagination
6. Gère les relations entre les modèles
7. Vérifie les autorisations via les policies
8. Valide les données via des Form Requests

Cette architecture solide nous permettra d'avoir une API flexible et robuste qui pourra être consommée par différents clients : applications web, mobiles ou même d'autres services.

Dans la prochaine étape, nous allons tester notre API avec Postman pour nous assurer que tout fonctionne correctement.


## 📚 Ressources complémentaires

- [Documentation officielle des API Laravel](https://laravel.com/docs/11.x/eloquent-resources) - Pour approfondir l'utilisation des API Resources
- [Laravel Sanctum](https://laravel.com/docs/11.x/sanctum) - Pour une documentation complète sur l'authentification API
- [JSON:API](https://jsonapi.org/) - Une spécification pour construire des API en JSON
- [Postman](https://www.postman.com/) - Un outil essentiel pour tester les API
- [Laravel API Best Practices](https://www.laravelbestpractices.com/) - Catalogue de bonnes pratiques pour le développement d'API

## 📌 Code source de cette étape

Le code source correspondant à cette étape est disponible sur la branche `step-7`.

## 📌 Prochaine étape

À présent que notre API est développée, nous devons la tester pour nous assurer qu'elle fonctionne correctement et qu'elle répond à nos attentes de performance et de sécurité.

**[➡️ Étape suivante : Tests de l'API avec Postman](09-tests-api.md)**