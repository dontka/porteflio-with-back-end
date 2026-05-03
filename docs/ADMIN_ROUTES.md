# 🎛️ Routes Administrateur

## Vue d'Ensemble

Toutes les routes administrateur sont protégées par authentification. Un utilisateur doit être connecté ET avoir le statut `is_admin = 1`.

---

## 📊 Dashboard

```
GET /admin
Response: Tableau de bord avec statistiques et contenu récent
```

---

## 📰 Gestion du Blog

### Lister les Articles
```
GET /admin/blog
Response: Liste de tous les articles avec actions
```

### Créer un Article
```
GET /admin/blog/create
Response: Formulaire de création

POST /admin/blog/store
Body: { title, slug, content, excerpt, csrf_token }
Redirect: /admin/blog + message success/error
```

### Éditer un Article
```
GET /admin/blog/edit/{id}
Response: Formulaire pré-rempli

POST /admin/blog/update/{id}
Body: { title, slug, content, excerpt, csrf_token }
Redirect: /admin/blog + message success/error
```

### Supprimer un Article
```
POST /admin/blog/delete/{id}
Redirect: /admin/blog + message success
```

---

## 🚀 Gestion des Projets

### Lister les Projets
```
GET /admin/projects
Response: Liste de tous les projets
```

### Créer un Projet
```
GET /admin/projects/create
Response: Formulaire de création

POST /admin/projects/store
Body (multipart/form-data):
  - title (string, required)
  - slug (string, required)
  - description (string, required)
  - content (text, optional)
  - technologies (string, optional)
  - link (url, optional)
  - github (url, optional)
  - thumbnail (file, optional - image/jpeg|png|webp)
  - is_featured (checkbox, boolean)
  - csrf_token (string, required)
Redirect: /admin/projects + success message
```

### Éditer un Projet
```
GET /admin/projects/edit/{id}
Response: Formulaire pré-rempli

POST /admin/projects/update/{id}
Body: Identique à POST /admin/projects/store
```

### Supprimer un Projet
```
POST /admin/projects/delete/{id}
Redirect: /admin/projects + success message
```

---

## ⭐ Gestion des Compétences

### Lister les Compétences
```
GET /admin/skills
Response: Tableau de toutes les compétences
```

### Ajouter une Compétence
```
GET /admin/skills/create
Response: Formulaire création

POST /admin/skills/store
Body:
  - name (string, required)
  - category (string, required)
  - proficiency (int 0-100, required)
  - csrf_token (string, required)
Redirect: /admin/skills
```

### Éditer une Compétence
```
GET /admin/skills/edit/{id}
Response: Formulaire pré-rempli

POST /admin/skills/update/{id}
Body: Identique à POST /admin/skills/store
```

### Supprimer une Compétence
```
POST /admin/skills/delete/{id}
Redirect: /admin/skills
```

---

## 💼 Gestion des Expériences

### Lister les Expériences
```
GET /admin/experience
Response: Tableau de toutes les expériences
```

### Ajouter une Expérience
```
GET /admin/experience/create
Response: Formulaire création

POST /admin/experience/store
Body:
  - title (string, required)
  - company (string, required)
  - start_date (date, required)
  - end_date (date, optional)
  - description (text, optional)
  - is_current (checkbox, boolean)
  - csrf_token (string, required)
Redirect: /admin/experience
```

### Éditer une Expérience
```
GET /admin/experience/edit/{id}
Response: Formulaire pré-rempli

POST /admin/experience/update/{id}
Body: Identique à POST /admin/experience/store
```

### Supprimer une Expérience
```
POST /admin/experience/delete/{id}
Redirect: /admin/experience
```

---

## 👥 Gestion des Utilisateurs

### Lister les Utilisateurs
```
GET /admin/users
Response: Tableau des utilisateurs et administrateurs
```

### Promouvoir un Utilisateur
```
POST /admin/promote-user
Body:
  - user_id (int, required)
Redirect: /admin/users
Logs: admin_logs (action: 'promote_user')
```

### Retirer les Droits Admin
```
POST /admin/revoke-user
Body:
  - user_id (int, required)
Conditions:
  - Cannot revoke yourself (same as current user_id)
Redirect: /admin/users
Logs: admin_logs (action: 'revoke_admin')
```

---

## 📋 Logs d'Administration

### Afficher les Logs
```
GET /admin/logs
Response: Liste des 100 derniers logs avec:
  - username (administrateur)
  - action (type d'action)
  - details (informations complémentaires)
  - created_at (horodatage)
```

**Actions enregistrées:**
- `create_blog` - Création d'article
- `update_blog` - Modification d'article
- `delete_blog` - Suppression d'article
- `create_project` - Création de projet
- `update_project` - Modification de projet
- `delete_project` - Suppression de projet
- `create_skill` - Ajout de compétence
- `update_skill` - Modification de compétence
- `delete_skill` - Suppression de compétence
- `create_experience` - Ajout d'expérience
- `update_experience` - Modification d'expérience
- `delete_experience` - Suppression d'expérience
- `promote_user` - Promotion d'utilisateur
- `revoke_admin` - Rétrogradation d'admin

---

## ⚙️ Paramètres

### Afficher les Paramètres
```
GET /admin/settings
Response: Informations système et de sécurité
```

---

## 🔒 Sécurité - Points Clés

### Authentification
- Route: `GET /login` pour se connecter
- Vérification: `$_SESSION['user_id']` et `$_SESSION['is_admin'] == 1`
- Fonction: `isUserAdmin()` et `requireAdmin()`

### Protection CSRF
- Token généré: `$_SESSION['csrf_token'] = bin2hex(random_bytes(32))`
- Vérifié sur tous les formulaires POST
- Token unique par session

### Requêtes BD
- Toutes les requêtes utilisent des prepared statements
- Paramètres liés pour prévenir l'injection SQL

### Upload d'Images
- Validation MIME type (JPEG, PNG, WebP)
- Nettoyage du nom de fichier
- Stockage sécurisé: `assets/images/{folder}/{filename}`

---

## 📊 Codes de Réponse HTTP

| Code | Sens |
|------|------|
| 200 | Succès (GET) / Redirection après succès |
| 302 | Redirection POST-REDIRECT-GET |
| 403 | Accès refusé (pas admin) |
| 404 | Ressource non trouvée |
| 405 | Méthode non autorisée (GET au lieu de POST, etc.) |
| 500 | Erreur serveur |

---

## 📝 Format des Réponses

### Succès (Redirect)
```
Status: 302
Location: /admin/{section}
Session: ['success' => 'Message de succès']
```

### Erreur (Redirect)
```
Status: 302
Location: /admin/{section}
Session: ['error' => 'Message d\'erreur']
```

### Form (GET)
```
Status: 200
View: admin/{section}/form.php
Data: [
  'pageTitle' => '...',
  'post|project|skill|experience' => $data,
  'isEdit' => true|false,
  'csrfToken' => $_SESSION['csrf_token'],
  'currentUser' => getCurrentAdminUser()
]
```

---

## 🎯 Exemple de Flux Complet

### Créer un Article

1. **GET `/admin/blog/create`**
   - Générer token CSRF
   - Afficher formulaire vide

2. **POST `/admin/blog/store`**
   - Vérifier token CSRF
   - Valider les données
   - Insérer en BD
   - Enregistrer dans logs
   - Rediriger à `/admin/blog`

3. **Redirection `/admin/blog`**
   - Afficher message success
   - Afficher article dans la liste

---

## 🚀 Intégration Future

Routes reservées pour fonctionnalités futures:
- Édition du profil
- Gestion des catégories
- Gestion des tags
- Gestion des thèmes
- Configuration SEO
- Backup/Restore

---

**Pour plus de détails, consultez `docs/ADMIN_SYSTEM.md`**
