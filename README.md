# 🎨 Système de Réservation d'Ateliers

Application Laravel 12 avec MongoDB pour la gestion complète de réservations d'ateliers créatifs dans plusieurs boutiques.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![MongoDB](https://img.shields.io/badge/MongoDB-7.x-green.svg)](https://mongodb.com)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-pink.svg)](https://livewire.laravel.com)

## 📋 Table des matières

- [Vue d'ensemble](#-vue-densemble)
- [Fonctionnalités principales](#-fonctionnalités-principales)
- [Architecture technique](#-architecture-technique)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [API REST](#-api-rest)
- [Seeders et données de test](#-seeders-et-données-de-test)
- [Licence](#-licence)

## 🎯 Vue d'ensemble

Application complète de gestion de réservations pour des ateliers créatifs organisés dans différentes boutiques. Le système permet aux clients de :
- Parcourir et réserver des ateliers
- Gérer leur panier et effectuer des paiements
- Accumuler des crédits de fidélité
- Accéder à des ateliers VIP exclusifs
- Laisser des commentaires et notes

Les employés peuvent :
- Créer et gérer des ateliers avec intervenants
- Suivre les réservations en temps réel
- Gérer plusieurs boutiques et salles
- Visualiser des statistiques détaillées

## ✨ Fonctionnalités principales

### 🎨 Gestion des Ateliers
- Création d'ateliers avec date, durée, prix, description
- Support des ateliers VIP (payables uniquement par crédit fidélité)
- Gestion des intervenants avec informations de contact et réseaux sociaux
- Vérification automatique de disponibilité des salles
- Calcul en temps réel de la capacité restante
- Système de soft delete pour l'historique

### 🏪 Gestion des Boutiques
- Multi-boutiques avec adresses complètes
- Gestion des salles par boutique (capacités, catégories)
- Affectation des employés aux boutiques
- Informations de contact et réseaux sociaux

### 👥 Gestion des Clients
- Profils clients avec historique complet
- Système de panier avec expiration (20 min)
- Crédits de fidélité (1 crédit = 1 personne inscrite)
- Ateliers VIP nécessitant 10 crédits/personne
- Gestion des réservations et annulations

### 💳 Système de Paiement
Méthodes de paiement supportées :
- **Carte bancaire** (avec masquage du numéro)
- **Chèque**
- **Espèces**
- **PayPal** (génération ID transaction)
- **Crédit fidélité** (pour ateliers VIP)

Statuts de paiement : `validé`, `en_attente`, `remboursé`, `échoué`

### 💬 Commentaires et Évaluations
- Système de notation sur 5 étoiles
- Commentaires liés aux ateliers et clients
- Modération possible

### 📊 Statistiques et Reporting
- Tableau de bord avec KPIs
- Statistiques par atelier (réservations actives/annulées)
- Répartition des paiements par méthode
- Visualisation de la capacité d'occupation

## 🏗️ Architecture technique

### Stack technique
- **Backend** : Laravel 11.x avec Fortify (authentification)
- **Frontend** : Livewire 3.x + TailwindCSS + Alpine.js
- **Base de données** : MongoDB 7.x avec laravel-mongodb
- **Icônes** : Heroicons + Blade Simple Icons

### Structure de données (MongoDB)

#### Collections principales
- `ateliers` - Avec réservations et paiements **embeded**
- `clients` - Avec panier **embeded**
- `boutiques` - Avec salles **embeded** (optionnel)
- `salles` - Avec référence boutique
- `users` - Employés et administrateurs
- `commentaires` - Avec références atelier/client

#### Particularités MongoDB
- Utilisation d'`ObjectId` pour les relations
- Données embeded pour optimiser les performances
- Soft delete avec champ `deleted_at`
- Timestamps automatiques

## 🔧 Prérequis

### Logiciels requis
- **PHP 8.2+** (8.3 recommandé) - [Télécharger](https://www.php.net/downloads.php)
- **Composer 2.x** - [Télécharger](https://getcomposer.org/download/)
- **Node.js 21+** et npm - [Télécharger](https://nodejs.org/en/download/)
- **MongoDB 7.x** - [Télécharger](https://www.mongodb.com/try/download/community)
- **Serveur web** (XAMPP, WAMP, Laragon, etc.) [OPTIONNEL MAIS RECOMMENDÉ POUR GESTION PHP]

### Installation de l'extension MongoDB pour PHP

#### Windows
1. Télécharger l'extension depuis [PECL](https://pecl.php.net/package/mongodb/2.1.4/windows)
   - Choisir la version **Thread Safe (TS)** correspondant à votre PHP
   
2. Placer le fichier `php_mongodb.dll` dans le dossier des extensions PHP
   ```
   Exemple : C:\xampp\php\ext\
   ```

3. Activer l'extension dans `php.ini`
   ```ini
   extension=php_mongodb.dll
   ```
   
4. Redémarrer le serveur web (Apache/Nginx)

5. Vérifier l'installation
   ```bash
   php -m | findstr mongodb
   # ou
   composer check-platform-reqs
   ```

#### Linux/Mac
```bash
pecl install mongodb
echo "extension=mongodb.so" >> /etc/php/8.2/cli/php.ini
```

## 📦 Installation

### 1. Cloner le projet
```bash
git clone https://github.com/votre-repo/reservation-atelier.git
cd reservation-atelier
```

### 2. Installer les dépendances
```bash
# Dépendances PHP
composer install

# Dépendances JavaScript
npm install
```

### 3. Configuration de l'environnement
```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 4. Configurer MongoDB dans `.env`
```env
DB_CONNECTION=mongodb
DB_HOST=127.0.0.1
DB_PORT=27017
DB_DATABASE=reservation_atelier
DB_USERNAME=
DB_PASSWORD=

# Pour MongoDB Atlas (cloud)
# DB_DSN=mongodb+srv://username:password@cluster.mongodb.net/database
```

### 5. Lancer les migrations (optionnel avec MongoDB)
```bash
php artisan migrate:fresh
```

### 6. Peupler la base avec des données de test
```bash
php artisan db:seed
```

**Seeders disponibles :**
- `ClientSeeder` - Crée 20 clients
- `BoutiqueSeeder` - Crée 5 boutiques avec adresses
- `SalleSeeder` - Crée des salles dans chaque boutique
- `UserSeeder` - Crée 10 employés
- `AtelierSeeder` - Crée 30 ateliers
- `ReservationSeeder` - Crée des réservations avec paiements
- `CommentaireSeeder` - Crée 20-50 commentaires
- `PanierSeeder` - Remplit 30% des paniers clients

### 7. Compiler les assets
```bash
# Mode développement
npm run dev

# Mode production
npm run build

# Mode watch (hot reload)
npm run dev -- --watch
```

### 8. Démarrer le serveur
```bash
php artisan serve
```

L'application sera accessible sur : **http://127.0.0.1:8000**

### 9. Connexion au dashboard
Utilisateur par défaut:

Connexion : `admin@gmail.com` / `123456789`

## 🔌 API REST

L'application expose une API REST complète pour toutes les opérations. Base URL : `http://localhost:8000/api`

### 👥 Clients

#### Lister tous les clients
```http
GET /api/clients
```

#### Voir un client
```http
GET /api/clients/{clientId}
```

**Réponse :**
```json
{
  "id": "507f1f77bcf86cd799439011",
  "nom": "Dupont",
  "prenom": "Jean",
  "email": "jean.dupont@example.com",
  "telephone": "+33612345678",
  "credit_fidelite": 25,
  "panier": {
    "ateliers": [],
    "expires_at": "2025-12-01T15:30:00Z"
  }
}
```

### 🛒 Gestion du Panier

#### Ajouter au panier
```http
POST /api/clients/{clientId}/panier
Content-Type: application/json

{
  "atelier_id": "507f1f77bcf86cd799439012",
  "quantity": 2
}
```

#### Retirer du panier
```http
DELETE /api/clients/{clientId}/panier
Content-Type: application/json

{
  "atelier_id": "507f1f77bcf86cd799439012"
}
```

#### Vider le panier
```http
POST /api/clients/{clientId}/panier/empty
```

#### Traiter le panier (paiement)
```http
POST /api/clients/{clientId}/panier/process
Content-Type: application/json

{
  "methode_paiement": "carte|cheque|espece|credit_fidelite|paypal",
  "numCarte": "1234567890123456" // Optionnel selon méthode
}
```

**Règles de paiement :**
- Ateliers VIP : uniquement `credit_fidelite` (10 crédits/personne)
- Ateliers standards : toutes les méthodes sauf `credit_fidelite`
- Le panier est automatiquement vidé après paiement
- Les crédits sont ajoutés/déduits automatiquement

### 🎨 Ateliers

#### Lister tous les ateliers
```http
GET /api/ateliers
```

#### Voir un atelier
```http
GET /api/ateliers/{atelierId}
```

#### Créer un atelier
```http
POST /api/ateliers
Content-Type: application/json

{
  "nom": "Atelier de peinture",
  "description": "Découvrez les techniques de peinture acrylique",
  "date": "2025-12-15T14:00:00Z",
  "duree": 2,
  "prix": 45.00,
  "vip": false,
  "salle_id": "507f1f77bcf86cd799439013",
  "employe_id": "507f1f77bcf86cd799439014",
  "intervenant": {
    "nom": "Martin",
    "prenom": "Sophie",
    "infoContact": {
      "email": "sophie@example.com",
      "telephone": "+33612345678",
      "website": "https://sophie-art.fr",
      "instagram": "https://instagram.com/sophie_art"
    }
  }
}
```

#### Mettre à jour un atelier
```http
PUT /api/ateliers/{atelierId}
PATCH /api/ateliers/{atelierId}
Content-Type: application/json

{
  "nom": "Atelier de peinture avancé",
  "prix": 55.00
}
```

#### Supprimer un atelier
```http
DELETE /api/ateliers/{atelierId}
```

#### Statistiques d'un atelier
```http
GET /api/ateliers/{atelierId}/statistics
```

**Réponse :**
```json
{
  "active_count": 12,
  "deleted_count": 2,
  "total_count": 14,
  "total_participants": 35,
  "revenue_by_method": {
    "carte": 450.00,
    "paypal": 200.00,
    "credit_fidelite": 0
  },
  "capacity_info": {
    "max": 50,
    "booked": 35,
    "remaining": 15
  }
}
```

### 💬 Commentaires

#### Ajouter un commentaire
```http
POST /api/atelier/{atelierId}/commentaires
Content-Type: application/json

{
  "commentaire": "Super atelier, très instructif !",
  "note": 5,
  "client_id": "507f1f77bcf86cd799439011"
}
```

#### Lister les commentaires
```http
GET /api/atelier/{atelierId}/commentaires
```

#### Modifier un commentaire
```http
PUT /api/commentaires/{commentaireId}
Content-Type: application/json

{
  "commentaire": "Excellent atelier !",
  "note": 5
}
```

#### Supprimer un commentaire
```http
DELETE /api/commentaires/{commentaireId}
```

### 📋 Réservations

#### Créer une réservation
```http
POST /api/atelier/{atelierId}/reservations
Content-Type: application/json

{
  "client_id": "507f1f77bcf86cd799439011",
  "nb_personne": 2,
  "paiement": {
    "numCarte": "1234567890123456",
    "methode_paiement": "carte"
  }
}
```

#### Lister les réservations d'un atelier
```http
GET /api/atelier/{atelierId}/reservations
```

### 🏪 Boutiques

#### Lister toutes les boutiques
```http
GET /api/boutiques
```

**Réponse :**
```json
{
  "data": [
    {
      "id": "507f1f77bcf86cd799439015",
      "nom": "Boutique Centre",
      "adresse": {
        "rue": "Rue de la Créativité",
        "numero": "12",
        "ville": "Paris",
        "code_postal": "75001"
      },
      "infoContact": {
        "email": "contact@boutique.fr",
        "telephone": "+33123456789",
        "website": "https://boutique.fr"
      }
    }
  ]
}
```

### 🏢 Salles

#### Vérifier disponibilité
```http
POST /api/salles/disponibilite
Content-Type: application/json

{
  "salle_id": "507f1f77bcf86cd799439016",
  "date": "2025-12-15T14:00:00Z",
  "duree": 2,
  "atelier_id": "507f1f77bcf86cd799439017" // Optionnel pour exclure
}
```

**Réponse :**
```json
{
  "disponible": true,
  "conflits": []
}
```

### 👨‍💼 Employés

#### Lister tous les employés
```http
GET /api/employes
```

### 📊 Codes de réponse

- `200 OK` - Requête réussie
- `201 Created` - Ressource créée
- `400 Bad Request` - Données invalides
- `404 Not Found` - Ressource introuvable
- `422 Unprocessable Entity` - Erreur de validation
- `500 Internal Server Error` - Erreur serveur

### 🔐 Authentification

Les routes API sont actuellement publiques. Pour une utilisation en production, ajouter :
```php
Route::middleware('auth:sanctum')->group(function () {
    // Routes protégées
});
```

## 📊 Seeders et données de test

### Exécution des seeders

```bash
# Tout réinitialiser avec seeders
php artisan migrate:fresh --seed

# Exécuter un seeder spécifique
php artisan db:seed --class=CommentaireSeeder
```

### Données générées

| Seeder | Quantité | Description |
|--------|----------|-------------|
| `ClientSeeder` | 20       | Clients avec emails et téléphones |
| `BoutiqueSeeder` | 5        | Boutiques avec adresses complètes |
| `SalleSeeder` | 15-25    | Salles réparties dans les boutiques |
| `UserSeeder` | 10       | Employés avec accès au système |
| `AtelierSeeder` | 280        | Ateliers variés avec intervenants |
| `ReservationSeeder` | ~100     | Réservations avec paiements |
| `CommentaireSeeder` | 20-50    | Commentaires et notes sur ateliers |
| `PanierSeeder` | ~6       | 30% des clients avec panier rempli |

### Méthodes helper du modèle Client

```php
$client = Client::find($id);

// Vérifier si le panier est vide
if ($client->panierIsEmpty()) { ... }

// Nombre d'items dans le panier
$count = $client->panierCount();

// Total du panier
$total = $client->panierTotal();

// Vérifier si le panier a expiré
if ($client->panierHasExpired()) { ... }
```

## 🎯 Cas d'usage typiques

### Parcours client complet

1. **Client parcourt les ateliers**
   ```http
   GET /api/ateliers
   ```

2. **Ajoute 2 ateliers au panier**
   ```http
   POST /api/clients/{id}/panier
   {"atelier_id": "...", "quantity": 2}
   ```

3. **Traite le paiement**
   ```http
   POST /api/clients/{id}/panier/process
   {"methode_paiement": "carte", "numCarte": "1234..."}
   ```

4. **Laisse un commentaire**
   ```http
   POST /api/atelier/{id}/commentaires
   {"commentaire": "Super !", "note": 5, "client_id": "..."}
   ```

### Gestion employé

1. **Créer un atelier**
   ```http
   POST /api/ateliers
   ```

2. **Vérifier la disponibilité de la salle**
   ```http
   POST /api/salles/disponibilite
   ```

3. **Consulter les statistiques**
   ```http
   GET /api/ateliers/{id}/statistics
   ```

## 📝 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

