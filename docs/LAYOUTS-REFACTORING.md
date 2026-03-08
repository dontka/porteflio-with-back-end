# 📐 Architecture Refactorisée - Layout & Partials System

## ✅ Refactorisation Complétée

### Nouvelles Structures Créées

```
app/Views/
├── layouts/
│   └── app.php              ← Layout principal (head, nav, footer)
│
├── partials/
│   ├── navbar.php           ← Navigation réutilisable
│   └── footer.php           ← Footer réutilisable
│
├── sections/                ← Sections modulaires pour home.php
│   ├── hero.php             ← Section héro
│   ├── stats.php            ← Barre de statistiques
│   ├── stack.php            ← Stack technologique
│   ├── services.php         ← Services carrousel
│   ├── skills.php           ← Compétences techniques
│   ├── projects.php         ← Projets récents
│   ├── experience.php       ← Expérience pro
│   ├── education.php        ← Formation académique
│   ├── blog.php             ← Blog & actualités
│   ├── github.php           ← Activité GitHub
│   └── contact.php          ← Section contact
│
├── home.php                 ← Page d'accueil (inclut sections)
├── project.php              ← Détails d'un projet
├── blog.php                 ← Détails d'un article
├── login.php
└── register.php
```

### 🔄 Flux de Rendu (Nouveau)

```
HomeController::index()
    ↓
$this->render('home', $data)      ← Utilise layout par défaut
    ↓
BaseController::render()          ← Rend avec layout
    ↓
View::renderWithLayout('layouts/app', 'home', $data)
    ↓
app/Views/layouts/app.php (wrapper HTML)
    │
    ├─ <head> (meta, styles, favicon)
    ├─ $view->include('partials/navbar')
    ├─ <main>
    │   <?php echo $content; ?>      ← home.php injectée ici
    │ </main>
    ├─ $view->include('partials/footer')
    └─ <scripts>
```

### 📁 Structure du Layout Principal (app.php)

```HTML
<!DOCTYPE html>
<html>
  <head>
    <!-- Meta tags dynamiques -->
    <!-- Stylesheet management (conditional) -->
  </head>
  <body>
    <!-- Skip link -->
    <!-- Navbar partial -->
    <main id="main-content">
      ← Content buffer ($content) injecté ici
    </main>
    <!-- Footer partial -->
    <!-- Back to top button -->
    <!-- Scripts (conditionally loaded) -->
  </body>
</html>
```

### 🔧 Système de Variables du Layout

Les pages passent des variables au layout pour customiser:

```php
// Variables système
$pageTitle          // <title>
$pageDescription    // <meta description>
$pageUrl           // <link canonical>
$pageImage         // <meta og:image>
$ogType            // Wikipedia 'article', 'website', etc
$isScrolled        // Navbar state

// Styles/Scripts additionnels
$pageStyles        // Array CSS URLs
$pageScripts       // Array JS URLs
$inlineScripts     // JS code inline
```

### 🏠 Refactorisation de home.php

**Avant:** 880 lignes monolithiques avec head, nav, 11 sections, footer

**Après:** ~50 lignes modulaires

```php
<?php
$view->include('sections/hero');
$view->include('sections/stats');
$view->include('sections/stack');
// ... etc
?>
```

### 📄 Refactorisation de project.php & blog.php

**Avant:** 750+ lignes avec head, nav, full templates

**Après:** ~180 lignes sans structure HTML (héritée du layout)
- Juste le contenu spécifique
- Pas de duplication de nav/footer
- Pas de balises HTML<head>/body

### 🎯 Points Clés de l'Architecture

1. **DRY (Don't Repeat Yourself)**
   - Head/Nav/Footer définis une seule fois dans le layout
   - Partials réutilisables (navbar, footer)
   - Sections découplées pour maintenance facile

2. **Conditionnalité Intelligente**
   - GitHub scripts/CSS chargés seulement pour homepage
   - Page-specific assets optionnels
   - Inline scripts générés dynamiquement

3. **Séparation des Responsabilités**
   - Layouts: Structure HTML globale
   - Partials: Composants réutilisables
   - Sections: Contenus modulaires
   - Templates: Vues spécifiques

4. **Meta Dynamique**
   - Open Graph tags générés par controller
   - Descriptions uniques par page
   - Images sociales customisées
   - Canonical URLs cohérentes

### 📝 Mise à Jour View.php

Ajout de deux nouvelles méthodes:

```php
// Rendre un template avec layout
renderWithLayout($layout, $template, $data)

// Rendre sans layout
render($template, $data)
```

### 🎨 Customs Bootstrap

```php
// BaseController::render() utilise layout par défaut
$this->render('home', $data);
// → BaseController détecte automatiquement layout 'app'

// Render custom layout
$this->renderWithLayout('custom', 'template', $data);

// Render partial (pas de layout)
$this->renderPartial('snippets/widget', $data);
```

### ✅ Validation

- [x] home.php découpée en 11 sections
- [x] Navbar + Footer en partials
- [x] Layout principal créé (app.php)
- [x] Controllers mis à jour avec variables layout
- [x] View.php étendue (renderWithLayout)
- [x] project.php refactorisée (sans duplication)
- [x] blog.php refactorisée (sans duplication)
- [x] Styles conditionnels (Github uniquement pour home)
- [x] Scripts conditionnels (GitHub pour home)
- [x] Meta tags dynamiques par page

### 🚀 Bénéfices

1. **Maintenabilité:** Modifier nav/footer = 1 fichier (partials/navbar.php)
2. **Performance:** CSS/JS GitHub déchargés pour project/blog
3. **DRY:** 0 duplication de structure HTML
4. **Scalabilité:** Ajouter une page = créer template + section (si besoin)
5. **Flexibilité:** Sections indépendantes, faciles à réordonner/masquer
6. **SEO:** Meta tags uniques par page

---

**Status**: ✅ **COMPLET** | **Architecture**: Layouts + Partials + Sections Pattern
