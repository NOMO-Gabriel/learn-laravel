# 📌 Gestionnaire Personnel de Dépenses - Tutoriel Laravel 11

## 🎯 Présentation de la formation
Ce tutoriel a pour but de vous apprendre à utiliser la technologie `Laravel 11` de façon pratique en vous guidant dans la création d'un **gestionnaire personnel de dépenses** en utilisant **Laravel 11**. Vous apprendrez à développer une application complète avec **Blade** et une **API sécurisée** avec **JWT**.

## 📜 Concepts Laravel abordés
Ce projet couvrira les notions suivantes :
- Installation et configuration de **Laravel 11**
- Gestion des **routes (web et API)**
- Création et manipulation des **modèles et migrations**
- Utilisation de **Eloquent ORM** pour gérer les relations entre entités
- Authentification avec **Laravel Breeze** et **JWT**
- Gestion des **rôles et permissions** (admin vs utilisateur)
- Création d’une **API RESTful** et tests avec **Postman**
- Sécurisation de l’API avec **JWT**
- Création d’interfaces avec **Blade**
- Middleware pour la gestion des comptes bloqués
- Export des dépenses en **Excel/PDF**
- Gestion des fichiers avec **Storage**

## 💰 Présentation du projet

### 🎯 Objectif
Développer une application permettant à un utilisateur de **gérer ses dépenses**, tout en offrant à un **administrateur** la possibilité de **bloquer des comptes**.

### ⚙️ Fonctionnement
1. **Un utilisateur** peut :
   - Ajouter, modifier et supprimer ses **dépenses**.
   - Voir un **historique** de ses dépenses avec **catégories**.
   - Gérer son **profil utilisateur**.
   - Consulter des **rapports statistiques** sur ses dépenses.
   
2. **Un administrateur** peut :
   - Gérer la liste des utilisateurs.
   - Bloquer ou débloquer un utilisateur.
   - Accéder à un **dashboard** avec des statistiques.

### 🛠️ Modélisation
Le projet utilise quatre principales entités :
- **User** (utilisateur, avec rôle admin ou user, état actif/bloqué)
- **Entrée d'argent** (montant, description, date, catégorie, utilisateur associé)
- **Expense** (montant, description, date, catégorie, utilisateur associé)
- **Category** (nom de la catégorie, relation avec Expense)

## 📌 Plan du tutoriel
1. [Requirements](01-requirements.md)
2. [Création et configuration du projet](02-creation-configuration.md)
3. [Création des modèles et migrations](03-modeles-migrations.md)
4. [Remplissage de la base de données avec des fixtures](04-fixtures.md)

### **Partie 1 : Application Laravel + Blade**
5. [Création des contrôleurs et routage (web.php)](05-controllers-web.md)
6. [Création des interfaces (Dashboard, profil utilisateur, gestion des dépenses, bilans)](06-interfaces.md)
7. [Tests des interfaces](07-tests-interfaces.md)

### **Partie 2 : API**
8. [Création des contrôleurs d’API](08-controllers-api.md)
9. [Tests avec Postman](09-tests-api.md)
10. [Sécurisation de l’API avec JWT](10-auth-jwt.md)
11. [Tests finaux avec Postman](11-tests-final.md)

---

### 🚀 **Comment utiliser ce tutoriel ?**
Chaque section est dans un fichier **Markdown séparé** pour une meilleure lisibilité. 
Cliquez sur une étape pour commencer ! Bonne formation ! 🎓🔥

## 🎉 Félicitations et Recommandations
Si vous êtes arrivé jusqu’ici, bravo ! 🎉 Vous avez maintenant une solide compréhension de **Laravel 11** et des principales fonctionnalités d’une application web moderne.

### 🚀 Pour aller plus loin :
- Apprenez les tests de l'application avec **PHPUnit**.
- Expérimentez avec **Laravel Livewire** pour du développement réactif.
- Explorez **Laravel Queues & Jobs** pour gérer les tâches en arrière-plan.
- Implémentez **une gestion avancée des rôles** avec **Laravel Permission**.
- Ajoutez un **système de notifications** (email, Slack, etc.).
- Réalisez un projet de grande envergure pour maîtriser un maximum de concepts et vous amuser.

Continuez à pratiquer et à construire vos propres projets ! 🚀🔥

---

## ⭐ Soutenez ce projet !
Si vous aimez cette formation, **n'oubliez pas de follow** le compte et de **mettre une étoile ⭐ sur le repository GitHub** ! Cela m'encouragera à produire encore plus de contenus de qualité. Merci pour votre soutien ! 🙌
