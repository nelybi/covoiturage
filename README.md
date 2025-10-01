# 🚗 Application de covoiturage interne (PHP MVC)

Projet scolaire : application web interne (poste de travail uniquement) permettant :
- Authentification des employés (utilisateur / admin)
- Liste des trajets disponibles
- Création de trajets par les utilisateurs
- Réservation d’une place
- Gestion des agences (admin)
- Messages flash après opérations (redirection + confirmation)

## ⚙️ Stack technique

- PHP 8+
- MySQL/MariaDB
- Composer
- Router PHP : izniburak/router
- Architecture MVC simple (src/Controllers, src/Core, src/Views)
- Bootstrap 5 (CDN)

## 🗂️ Structure du projet

```
covoiturage/
├─ public/
│  └─ index.php                # Front controller + routes
├─ src/
│  ├─ Core/
│  │  ├─ Database.php          # Connexion PDO
│  │  └─ Flash.php             # Messages flash
│  ├─ Controllers/
│  │  ├─ AuthController.php
│  │  ├─ TrajetController.php
│  │  └─ AgenceController.php
│  └─ Views/
│     └─ layouts/
│        └─ header.php         # Navbar + Flash
├─ database.sql                # Schéma BDD
├─ seeds.sql                   # Données initiales (agences + users avec hash)
├─ seeds_trajets.sql           # Trajets de test
├─ composer.json
└─ README.md
```

## 🔧 Installation

1) Cloner le projet et se placer dans le dossier
```bash
cd covoiturage
```

2) Installer les dépendances
```bash
composer install
```

3) Créer la base & injecter les données
```bash
# Crée la base + tables
mysql -u root < database.sql

# Insère agences + utilisateurs (hashés)
mysql -u root covoiturage < seeds.sql

# Insère quelques trajets de test
mysql -u root covoiturage < seeds_trajets.sql
```

> Si `mysql` demande un mot de passe, ajoute `-p`.

4) Configurer la connexion MySQL dans `src/Core/Database.php`
```php
<?php
namespace Elayoubi\Covoiturage\Core;

use PDO;
use PDOException;

class Database {
  private $host = '127.0.0.1';
  private $db   = 'covoiturage';
  private $user = 'root';
  private $pass = '';
  private $charset = 'utf8mb4';

  public function getConnection(): PDO {
    $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ];
    return new PDO($dsn, $this->user, $this->pass, $options);
  }
}
```

5) Lancer le serveur PHP
```bash
php -S localhost:8000 -t public
```

6) Ouvrir l’application  
http://localhost:8000

## 👤 Comptes de test

- **Admin**
  - Email : `arthur.henry@email.fr`
  - Mot de passe : `admin1234`

- **Utilisateurs (exemples)**
  - `alexandre.martin@email.fr` / `1234`
  - `sophie.dubois@email.fr` / `1234`
  - … (tous les autres users ont `1234`)

## 🚦 Routes principales

- `GET /` — liste des trajets (futurs, avec places > 0)
- `GET /login` — formulaire de connexion
- `POST /login` — authentification (sessions)
- `GET /logout` — déconnexion
- `GET /trajets/nouveau` — formulaire création trajet (connecté)
- `POST /trajets` — enregistrement d’un trajet + flash + redirection
- `POST /trajets/:id/reserver` — réservation d’1 place (connecté)
- `GET /admin/agences` — (admin) liste/ajout/suppression d’agences
- `POST /admin/agences` — (admin) créer une agence
- `POST /admin/agences/:id/delete` — (admin) supprimer une agence

> ⚠️ Le routeur utilise `:id` (pas `{id}`).

## ✨ Messages flash

Après chaque action d’écriture, on redirige vers une page cible en affichant un message de confirmation (ou d’erreur) :
- Connexion : `Bienvenue, {prenom} 👋`
- Déconnexion : `Déconnecté. À bientôt 👋`
- Création trajet : `Trajet créé avec succès ✅`
- Réservation : `Réservation confirmée ✅` (ou “Plus de place…”)
- Admin agences : `Agence ajoutée ✅`, etc.

Implémentation : `src/Core/Flash.php` + `Flash::display()` dans `header.php`.

## 🔒 Règles d’accès

- Pages publiques : `/`, `/login`
- Pages nécessitant d’être connecté : création trajet, réservation
- Pages admin : gestion des agences (`/admin/agences`)

Les contrôles sont faits dans les contrôleurs (`requireLogin`, `requireAdmin`).

## 🧪 Scénario de test (rapide)

1. Se connecter en **utilisateur** → créer un trajet → redirection `/` + flash ✅  
2. Réserver 1 place sur un trajet existant → places diminuent + flash ✅  
3. Se connecter en **admin** → `/admin/agences` → ajouter/supprimer une agence + flash ✅  
4. Déconnexion → flash ✅  

## 🛠️ Dépannage

- **404 sur `/trajets/{id}/reserver`** → route incorrecte. Remplacer `{id}` par `:id`.  
- **Identifiants invalides** → vérifier colonne `password` contient un hash `$2y$...` (bcrypt), pas un texte “NOUVEAU_HASH”.  
- **No database selected** lors de l’import seeds → exécuter `mysql -u root covoiturage < seeds.sql` ou ajouter `USE covoiturage;` en tête du fichier.  
- **FK agence utilisée** lors suppression → MySQL bloque si l’agence apparait dans un trajet.

## 📄 Licence
MIT (usage pédagogique).
