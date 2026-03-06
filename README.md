# Portfolio — Donatien TUMAINI KANANE

Portfolio professionnel de **Donatien TUMAINI KANANE**, développeur web full-stack et analyste de données basé à Goma, RDC. Développé avec PHP et MySQL, ce site offre une interface moderne et responsive pour présenter mes projets, compétences et parcours professionnel.

**🔗 LinkedIn :** [linkedin.com/in/afialab](https://cd.linkedin.com/in/afialab)  
**🔗 GitHub :** [github.com/dontka](https://github.com/dontka)  
**🔗 Site Web :** [schor.alwaysdata.net](http://schor.alwaysdata.net)  
**📧 Contact :** donatienkanane@gmail.com

---

## Aperçu

### Page d'Accueil (index.php)

#### En-tête
- Photo de profil
- Nom et titre professionnel
- Liens vers LinkedIn et GitHub
- Bouton de contact
- Switch mode sombre/clair

#### Section Principale
- Présentation personnelle (À Propos de Moi)
- Projets récents (8 projets issus de GitHub)
- Expérience professionnelle (6 postes)

#### Barre Latérale
- Informations de contact (localisation, email, site web)
- Compétences techniques avec niveaux (10 compétences)
- Formation (Université de Goma, Institut Isidore Bakanja)
- Langues (Français, Anglais, Kiswahili)
- Calendrier de contributions GitHub
- Flux d'activité GitHub

### Page de Connexion (login.php)
- Formulaire de connexion avec validation
- Gestion des sessions utilisateur
- Protection contre les injections SQL et XSS

### Page de Projet (project.php)
- Affichage détaillé de chaque projet
- Système de commentaires pour utilisateurs connectés
- Informations : date de création, statut, lien vers le dépôt

---

## Projets Présentés

| Projet | Description | Technologies |
|--------|-------------|-------------|
| **AfiaZone** | Marketplace médicale avec E-Wallet Santé | PHP, MySQL |
| **ASBL/ONG Manager** | Plateforme de gestion d'organisation tout-en-un | PHP |
| **PHP Payment Gateway** | Package de gestion multi-paiements (Mobile Money, Stripe, PayPal…) | PHP |
| **Saint-Tharcisse** | Application de gestion de servants de messe | PHP |
| **CRUD ASBL/ONG** | Système CRUD pour associations et ONG | PHP |
| **SCHOR Center** | Site du Centre de Consultance Scientifique | HTML, CSS |
| **E-Commerce Responsive** | Site e-commerce responsive | HTML, CSS, JS |
| **Blog avec Back-end** | Plateforme de blog avec authentification | PHP, MySQL |

---

## Installation et Configuration

### Prérequis
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx) — recommandé : [Laragon](https://laragon.org/)

### Installation
1. Cloner le repository :
   ```bash
   git clone https://github.com/dontka/porteflio-with-back-end.git
   ```
2. Configurer la base de données dans `config.php`
3. Importer le fichier `database.sql` dans MySQL
4. Ajouter votre photo de profil dans `assets/images/profile.png`
5. Ajouter des captures d'écran de projets dans `assets/images/projects/`

### Configuration
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'portfolio');
define('DB_USER', 'root');
define('DB_PASS', '');
define('SYS_URL', 'http://localhost/porteflio-with-back-end/');
define('DEBUGGING', false);
define('DEFAULT_LOCALE', 'fr_FR');
```

---

## Technologies Utilisées

### Back-end
- PHP 7.4+
- MySQL 5.7+
- PDO pour la gestion de la base de données
- Sessions PHP pour l'authentification

### Front-end
- Bootstrap 5 pour le design responsive
- FontAwesome 6 pour les icônes
- JavaScript vanilla
- GitHub Calendar & Activity API

### Outils
- Git & GitHub
- Laragon (environnement local)

---

## Structure du Projet

```
porteflio-with-back-end/
├── config.php                 # Configuration (BDD, URL, locale)
├── index.php                  # Page d'accueil du portfolio
├── project.php                # Page détaillée d'un projet
├── login.php                  # Page de connexion
├── logout.php                 # Déconnexion
├── database.sql               # Script SQL (structure + données)
├── includes/
│   ├── Database.php           # Classe de connexion PDO
│   ├── functions.php          # Fonctions utilitaires
│   ├── handle_comment.php     # Traitement des commentaires
│   └── delete_comment.php     # Suppression de commentaires
├── assets/
│   ├── css/styles.css         # Styles du thème
│   ├── js/main.js             # Scripts (GitHub Calendar, RSS, Dark Mode)
│   ├── images/                # Photos de profil et projets
│   ├── fontawesome/           # Icônes FontAwesome 6
│   ├── plugins/               # Bootstrap, Popper, Dark Mode Switch
│   └── scss/                  # Sources SCSS
└── README.md
```

---

## Auteur

**Donatien TUMAINI KANANE**  
Développeur Web Full-Stack & Analyste de Données  
Goma, République Démocratique du Congo  

- 📧 donatienkanane@gmail.com  
- 🔗 [LinkedIn](https://cd.linkedin.com/in/afialab)  
- 💻 [GitHub](https://github.com/dontka)  
- 🌐 [schor.alwaysdata.net](http://schor.alwaysdata.net)

## Licence

Ce projet est sous licence Apache 2.0. Voir le fichier `LICENSE` pour plus de détails.