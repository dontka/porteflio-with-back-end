# Portfolio avec Back-end

Ce projet est un portfolio professionnel développé avec PHP et MySQL, offrant une interface moderne et responsive pour présenter vos projets et compétences.

## Pages Principales

### 1. Page d'Accueil (index.php)
La page d'accueil présente l'ensemble du portfolio avec les sections suivantes :

#### En-tête
- Photo de profil
- Nom et titre professionnel
- Liens vers les réseaux sociaux (Twitter, LinkedIn, GitHub)
- Bouton de contact
- Switch pour le mode sombre/clair

#### Section Principale
- Présentation personnelle
- Liste des projets récents
- Section "À propos"
  - Expérience professionnelle

#### Barre Latérale
- Informations de contact
- Liste des compétences avec niveaux
- Intégration GitHub (calendrier et activité)

### 2. Page de Connexion (login.php)
Interface d'authentification sécurisée :

#### Fonctionnalités
- Formulaire de connexion avec validation
- Gestion des sessions utilisateur
- Redirection intelligente après connexion
- Messages d'erreur personnalisés
- Protection contre les injections SQL
- Interface responsive

#### Sécurité
- Hachage des mots de passe (SHA1)
- Protection contre les attaques XSS
- Validation des entrées utilisateur
- Gestion sécurisée des sessions

### 3. Page de Projet (project.php)
Page détaillée pour chaque projet :

#### Affichage du Projet
- Titre et description
- Image principale
- Date de création
- Statut (mis en avant ou non)
- Lien vers le projet

#### Système de Commentaires
- Liste des commentaires existants
- Formulaire de commentaire pour utilisateurs connectés
- Gestion des droits (modération, suppression)
- Interface utilisateur intuitive

#### Fonctionnalités Sociales
- Partage sur réseaux sociaux
- Intégration avec GitHub
- Système de notation (à venir)

## Installation et Configuration

### Prérequis
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx)

### Installation
1. Cloner le repository
2. Configurer la base de données dans `config.php`
3. Importer le fichier `database.sql`
4. Configurer les paramètres de l'application

### Configuration
```php
// Base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'portfolio');
define('DB_USER', 'votre_utilisateur');
define('DB_PASS', 'votre_mot_de_passe');

// Application
define('DEBUG_MODE', false);
define('DEFAULT_LOCALE', 'fr_FR');
define('gSYSTEM_URL', 'http://votre-domaine.com/');
```

## Technologies Utilisées

### Back-end
- PHP 7.4+
- MySQL 5.7+
- PDO pour la gestion de la base de données
- Sessions PHP pour l'authentification

### Front-end
- Bootstrap 5 pour le design responsive
- FontAwesome pour les icônes
- jQuery pour les interactions
- GitHub API pour l'intégration

## Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :
1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## Structure du Projet

```
porteflio-with-back-end/
├── app/
│   ├── config/
│   │   └── config.php
│   ├── controllers/
│   │   └── HomeController.php
│   ├── core/
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   └── Model.php
│   ├── models/
│   │   └── ProjectModel.php
│   └── views/
│       ├── layouts/
│       │   ├── header.php
│       │   └── footer.php
│       └── home/
│           └── index.php
├── public/
│   ├── css/
│   ├── js/
│   └── img/
└── index.php
```