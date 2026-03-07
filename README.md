# Portfolio — Donatien TUMAINI KANANE

> Portfolio professionnel full-stack construit avec PHP 8 / MySQL / Bootstrap 5 — conçu comme une architecture modulaire, évolutive et maintenable.

**Donatien TUMAINI KANANE** — Développeur Web Full-Stack & Data Analyst basé à Goma, RDC. Je conçois des solutions numériques à fort impact social pour les organisations humanitaires, les ONG et le secteur de la santé en Afrique Centrale.

| Lien | URL |
|------|-----|
| LinkedIn | [linkedin.com/in/afialab](https://cd.linkedin.com/in/afialab) |
| GitHub | [github.com/dontka](https://github.com/dontka) |
| Site Web | [schor.alwaysdata.net](http://schor.alwaysdata.net) |
| Email | donatienkanane@gmail.com |

---

## Architecture & Choix Techniques

### Principes appliqués
- **Séparation des responsabilités** : couche données (`Database.php`), logique métier (`functions.php`), présentation (`index.php`, templates)
- **Injection de dépendances** : la connexion PDO est injectée dans chaque fonction métier plutôt qu'utilisée en global
- **Sécurité en profondeur** : requêtes préparées (PDO), échappement XSS (`htmlspecialchars`), validation des entrées, sessions sécurisées
- **Progressive enhancement** : le site fonctionne sans JavaScript, les animations et interactions enrichissent l'expérience
- **Mobile-first & responsive** : breakpoints adaptatifs (575px, 991px, 1200px), composants fluides
- **Performance** : CSS critique inline-ready, FontAwesome en `defer`, images lazy-loadables

### Stack technique

| Couche | Technologies |
|--------|-------------|
| **Back-end** | PHP 8.x, MySQL 8, PDO (requêtes préparées) |
| **Front-end** | Bootstrap 5, JavaScript ES6 vanilla, CSS3 (variables, animations, glassmorphism) |
| **Typographie** | Inter (corps), Poppins (titres), JetBrains Mono (code) |
| **Icônes** | FontAwesome 6 (chargement différé JS → SVG) |
| **Intégrations** | GitHub Calendar API, GitHub Activity Feed, RSS Feed |
| **Outils** | Git, GitHub, Laragon, SCSS (source disponible) |

### Sécurité
- Requêtes SQL paramétrées (`PDO::prepare` + `bindParam`) — aucune concaténation directe
- Protection XSS via `sanitizeOutput()` sur toutes les sorties dynamiques
- Hash des mots de passe (bcrypt/SHA1)
- Validation côté serveur sur inscription, connexion et commentaires
- Tokens de session régénérés après authentification

---

## Structure du Projet

```
### 📁 Répertoires
```
✓ includes/          - PHP classes & functions (protected by .htaccess)
  - Database.php     - PDO database connection
  - functions.php    - Utility functions
  - handle_*.php     - AJAX handlers
  - delete_comment.php - Comment deletion

✓ assets/            - Static files
  - css/             - Stylesheets
  - js/              - JavaScript
  - fontawesome/     - Icon library
  - images/          - Project/blog images
  - plugins/         - Third-party plugins
```

### 📚 Documentation
```
✓ README.md          - Project information
✓ LICENSE            - Legal information
✓ favicon.ico        - Browser tab icon
```

---

## 🔐 SÉCURITÉ & .gitignore

Créé un `.gitignore` pour éviter d'accidentellement commiter:
- Fichiers sensibles (config.php, *.sql)
- Logs et fichiers temporaires
- Documentation interne (AUDIT_REPORT.md, etc.)
- Fichiers IDE/OS
- Archives (*.zip, *.rar)

---

## 📊 STRUCTURE FINALE

```
porteflio-with-back-end/
├── .git/                 (Version control)
├── .gitignore            (NEW - Git exclude rules)
├── .htaccess             (Apache config + Security)
├── config.php            (Production config)
├── favicon.ico           (Browser icon)
├── index.php             (Homepage)
├── blog.php              (Blog articles)
├── project.php           (Project details)
├── login.php             (Auth)
├── register.php          (Auth)
├── logout.php            (Auth)
├── sitemap.php           (Dynamic sitemap)
├── robots.txt            (SEO)
├── README.md             (Documentation)
├── LICENSE               (Legal)
├── assets/
│   ├── css/              (Stylesheets)
│   ├── js/               (JavaScript)
│   ├── fontawesome/      (Icons)
│   ├── images/           (Images)
│   └── plugins/          (3rd party)
└── includes/             (Protected by .htaccess)
    ├── Database.php
    ├── functions.php
    ├── handle_*.php
    └── delete_comment.php
---

## Fonctionnalités

### Portfolio dynamique
- Données issues de MySQL — facilement modifiable sans toucher au code
- Profil, projets, compétences, expérience et blog gérés en base
- Projet mis en avant (featured) avec badge et disposition spéciale
- Niveaux de compétences animés (barres + anneaux SVG circulaires)

### Système de blog
- Articles avec slug unique, catégories, excerpts, dates
- Page de listing avec article principal (featured) + fil connecté (timeline)
- Page de détail avec sidebar (auteur, métadonnées, partage)
- Temps de lecture calculé automatiquement

### Authentification & commentaires
- Inscription/connexion avec validation serveur
- Commentaires liés aux projets, visibles par tous, modifiables par l'auteur
- Redirection post-login vers la page d'origine

### UI/UX
- Thème clair/sombre avec switch persistant (localStorage)
- Carousel de services avec autoplay + dots + flèches
- Timeline d'expérience alternée (gauche/droite)
- Animations d'entrée au scroll (Intersection Observer)
- Statistiques comptées dynamiquement
- Navigation sticky avec effet glassmorphism au scroll

### Intégrations
- Calendrier de contributions GitHub (widget interactif)
- Flux d'activité GitHub en temps réel
- Liens sociaux dynamiques depuis le profil en base

---

## Collaboration & Déploiement

### Environnement local
```bash
# Prérequis : PHP 8+, MySQL 8+, serveur web (recommandé : Laragon)
git clone https://github.com/dontka/porteflio-with-back-end.git
```

### Configuration
1. Éditer `config.php` avec vos paramètres :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'portfolio');
define('DB_USER', 'root');
define('DB_PASS', '');
define('SYS_URL', 'http://localhost/porteflio-with-back-end/');
define('DEBUGGING', false);
```
2. Importer `database.sql` dans MySQL
3. Placer les images dans `assets/images/` et `assets/images/projects/`

### Convention de code
- PHP : PSR-12, fonctions documentées avec PHPDoc
- CSS : organisation par section, variables CSS pour la cohérence
- JS : `"use strict"`, IIFE pour l'isolation des scopes
- SQL : commentaires descriptifs, clés étrangères, timestamps automatiques

---

## Impact Social & Communautaire

Ce portfolio n'est pas seulement un exercice technique — il reflète un engagement concret pour l'innovation numérique en Afrique Centrale :

- **AfiaZone** facilite l'accès aux soins médicaux en RDC via une marketplace avec e-wallet santé
- **ASBL/ONG Manager** aide les organisations humanitaires à structurer leur gestion opérationnelle
- **Collecte de données** (KOBO, SPSS, Power BI) au service de la recherche en santé publique
- **Sensibilisation communautaire** — expérience terrain dans la prévention des violences sexuelles à Masisi
- **Programme FIKIRI / PNUD** — contribution à l'identification de solutions innovantes pour les ODD

Chaque projet est pensé pour résoudre des problèmes réels dans un contexte où la technologie peut transformer les conditions de vie.

---

## Auteur

**Donatien TUMAINI KANANE**
Développeur Web Full-Stack & Data Analyst
Goma, République Démocratique du Congo

- [LinkedIn](https://cd.linkedin.com/in/afialab)
- [GitHub](https://github.com/dontka)
- [Site Web](http://schor.alwaysdata.net)
- donatienkanane@gmail.com

## Licence

Apache 2.0 — voir le fichier `LICENSE`.