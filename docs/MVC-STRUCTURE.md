# Restructuration MVC - Documentation

## ✅ Structure Mise en Place

### Répertoires Créés
```
app/
├── Core/
│   ├── Autoloader.php        # Autoloader PSR-4
│   ├── BaseController.php    # Classe de base pour les controllers
│   ├── BaseModel.php         # (dans Models/) Classe de base pour les models
│   ├── Router.php            # Routeur principaux
│   └── View.php              # Gestionnaire des vues
├── Models/
│   ├── BaseModel.php         # Classe abstraite pour les models
│   ├── Profile.php
│   ├── Project.php
│   ├──Blog.php
│   ├── Skill.php
│   └── Experience.php
├── Controllers/
│   ├── HomeController.php    # Gère la page d'accueil
│   ├── ProjectController.php # Gère les pages de projets
│   ├── BlogController.php    # Gère les articles de blog
│   └── APIController.php     # Gère les routes API
└── Views/
    └── home.php              # Template de la page d'accueil
```

## 🔄 Flux de Requête

### Point d'Entrée Unique
```
1. index.php (Front Controller)
   ├─ Charge la configuration (config.php)
   ├─ Initialise l'Autoloader
   ├─ Démarre la session
   ├─ Crée le Router
   ├─ Définit les routes
   └─ Dispatche la requête
```

### Exemple de Routing
```php
// Routes définies dans index.php
$router->add('GET', '/', 'HomeController', 'index');
$router->add('GET', '/projet/{slug}', 'ProjectController', 'show');
$router->add('GET', '/blog/{slug}', 'BlogController', 'show');
$router->add('POST', '/api', 'APIController', 'route');
```

## 📝 Routes Disponibles

| Méthode | Route | Controller | Action |Exemple |
|---------|-------|-----------|--------|--------|
| GET | / | HomeController | index | http://localhost/porteflio-with-back-end/ |
| GET | /projet/{slug} | ProjectController | show | http://localhost/porteflio-with-back-end/projet/blog-avec-back-end |
| GET | /project/{slug} | ProjectController | show | Backward compatibility |
| GET | /blog/{slug} | BlogController | show | http://localhost/porteflio-with-back-end/blog/mon-article |
| POST | /api | APIController | route | Gère commentaires, likes, etc. |

## 🏗️ Architecture MVC Légère

### Models
- **BaseModel**: Classe abstraite avec CRUD basiques
- **Profile**: Données du profil utilisateur
- **Project**: Gestion des projets
- **Blog**: Articles de blog  
- **Skill**: Compétences techniques
- **Experience**: Expérience professionnelle

### Controllers
- Héritent de **BaseController**
- Accès automatiqueà la BD via `$this->db`
- Accès aux vues via `$this->render()`
- Méthodes: `render()`, `json()`, `redirect()`, `checkDatabase()`

### Views  
- Templates PHP dans `app/Views/`
- Variables disponibles via l'appel: `$view->render('home', ['data' => $data])`
- Extraction automatique des variables via `extract()`

### Router
- Convertit les chemins en regex avec paramètres
- Supporte les paramètres nommés: `/projet/{slug}` → `$params['slug']`
- Gère les redirections 404

### Autoloader
- PSR-4 compatible
- Charge automatiquement les classes du namespace `App\`
- Enregistrement simple: `Autoloader::register()`

## ✅ Tests de Fonctionnalité

```bash
# Page d'accueil
curl http://localhost/porteflio-with-back-end/
# HTTP 200 ✓

# Projet
curl http://localhost/porteflio-with-back-end/projet/blog-avec-back-end  
# HTTP 200 ✓

# Blog (404 si slug inexistant)
curl http://localhost/porteflio-with-back-end/blog/crud-asbl-ong
# HTTP 404 (slug n'existe pas)
```

## 📦 Avantages de cette Structure

1. **Séparation des responsabilités**: Models, Controllers, Views séparés
2. **Autoloader**: Pas besoin de require manuels
3. **Front Controller Unique**: Toutes les requêtes via index.php
4. **Flexibility**: Routes flexibles et maintenables
5. **Backward Compatibility**: Ancien projet.php encore accessible
6. **Évolutif**: Facile d'ajouter nouveaux controllers/models

## 🔔 Points d'Attention

- Les vues utilisent toujours les fonctions du `functions.php` hérité
- Les include files `handle_comment.php` etc. sont intégrées via APIController
- Configuration centralisée dans `config.php`
- Pas de migration DB nécessaire - compatible avec schéma existant

## 🚀 Prochaines Étapes (Optionnel)

- [ ] Scinder les vues en partials (se trouvent dans app/Views/sections/)
- [ ] Ajouter class di pour l'injection de dépendances
- [ ] Ajouter middleware (authentification, logs, etc.)
- [ ] Configurer le cache des templates
- [ ] Ajouter des tests unitaires
