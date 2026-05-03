# 📊 Système d'Administration Complet

## Table des matières
1. [Installation](#installation)
2. [Accès à l'Administration](#accès-à-ladministration)
3. [Fonctionnalités](#fonctionnalités)
4. [Gestion du Contenu](#gestion-du-contenu)
5. [Gestion des Utilisateurs](#gestion-des-utilisateurs)
6. [Sécurité](#sécurité)
7. [Dépannage](#dépannage)

---

## 🚀 Installation

### Étape 1: Exécuter la migration SQL

Importez le fichier de migration dans votre base de données:

```bash
# Option 1: Via phpMyAdmin
# 1. Ouvrir phpMyAdmin
# 2. Sélectionner la base de données 'afiazone'
# 3. Aller à l'onglet "SQL"
# 4. Copier le contenu de docs/ADMIN_MIGRATION.sql
# 5. Cliquer sur "Exécuter"

# Option 2: Via MySQL CLI
mysql -u root -p afiazone < docs/ADMIN_MIGRATION.sql
```

**Fichiers de migration:**
- `docs/ADMIN_MIGRATION.sql` - Version avec commentaires
- `docs/ADMIN_MIGRATION.min.sql` - Version minifiée

### Étape 2: Promouvoir un Utilisateur en Admin

Une fois la migration exécutée, vous devez promouvoir un utilisateur existant en administrateur:

```sql
-- Dans phpMyAdmin, exécuter:
UPDATE users SET is_admin = 1 WHERE email = 'votre-email@example.com';
```

Ou lors de la création du premier compte utilisateur, exécuter directement:

```sql
UPDATE users SET is_admin = 1 WHERE id = 1 LIMIT 1;
```

### Étape 3: Vérifier l'Installation

1. Connectez-vous avec votre compte utilisateur
2. Allez sur `http://localhost/afiazone/admin`
3. Vous devriez voir le tableau de bord administrateur

---

## 🔐 Accès à l'Administration

### Conditions d'accès

- L'utilisateur doit être **connecté**
- L'utilisateur doit avoir le statut **admin** (`is_admin = 1` en base de données)

### URL d'administration

```
http://localhost/afiazone/admin
```

### Navigation

Une fois connecté, utilisez le menu latéral gauche pour naviguer entre les sections:

- **Tableau de Bord** - Vue d'ensemble des statistiques
- **Articles de Blog** - Gérer vos articles
- **Projets** - Gérer vos projets
- **Compétences** - Gérer vos skills
- **Expériences** - Gérer vos expériences
- **Utilisateurs** - Gérer les utilisateurs et admins
- **Logs** - Historique des actions
- **Paramètres** - Configuration du site

---

## ✨ Fonctionnalités

### 1. Tableau de Bord (Dashboard)

Affiche:
- 📊 Statistiques globales (articles, projets, admins, utilisateurs)
- 📰 Articles récents
- ⭐ Projets en vedette

### 2. Gestion du Blog

**CRUD Complet:**
- ✅ Créer un nouvel article
- ✅ Lister tous les articles
- ✅ Éditer un article existant
- ✅ Supprimer un article

**Champs d'un article:**
- Titre (obligatoire)
- Slug (obligatoire, auto-généré)
- Contenu (obligatoire)
- Résumé/Excerpt
- Date de création (automatique)

### 3. Gestion des Projets

**CRUD Complet:**
- ✅ Créer un nouveau projet
- ✅ Lister tous les projets
- ✅ Éditer un projet
- ✅ Supprimer un projet

**Champs d'un projet:**
- Titre (obligatoire)
- Slug (obligatoire, auto-généré)
- Description courte (obligatoire)
- Contenu détaillé
- Technologies utilisées
- Lien vers le projet live
- Lien GitHub
- Image principale (upload avec validation)
- Vedette (checkbox pour afficher en avant)

### 4. Gestion des Compétences

**CRUD Complet:**
- ✅ Ajouter une compétence
- ✅ Lister les compétences
- ✅ Éditer une compétence
- ✅ Supprimer une compétence

**Champs d'une compétence:**
- Nom (obligatoire)
- Catégorie (Backend, Frontend, Mobile, DevOps, Database, Tools, Autre)
- Niveau de maîtrise (0-100%, avec slider interactif)

### 5. Gestion des Expériences

**CRUD Complet:**
- ✅ Ajouter une expérience
- ✅ Lister les expériences
- ✅ Éditer une expérience
- ✅ Supprimer une expérience

**Champs d'une expérience:**
- Titre du poste (obligatoire)
- Entreprise (obligatoire)
- Date de début (obligatoire)
- Date de fin (optionnelle)
- Description
- Expérience actuelle (checkbox)

### 6. Gestion des Utilisateurs

**Fonctionnalités:**
- 👥 Lister tous les utilisateurs
- 👑 Voir tous les administrateurs
- ⬆️ Promouvoir un utilisateur en admin
- ⬇️ Retirer les droits admin

**Protection:**
- Vous ne pouvez pas retirer vos propres droits admin
- Les logs enregistrent tous les changements

### 7. Logs d'Audit

**Enregistrement de:**
- ✅ Création/édition/suppression d'articles
- ✅ Création/édition/suppression de projets
- ✅ Création/édition/suppression de compétences
- ✅ Création/édition/suppression d'expériences
- ✅ Promotion/rétrogradation d'utilisateurs

**Informations enregistrées:**
- Administrateur qui a effectué l'action
- Type d'action
- Détails (ID, titre, etc.)
- Timestamp exact

### 8. Paramètres

- 📋 Information système
- 🔒 Détails de sécurité
- 🚀 Fonctionnalités de maintenance (à venir)

---

## 📝 Gestion du Contenu

### Créer un Article de Blog

1. Allez dans **Articles de Blog** → **Créer un article**
2. Remplissez les champs:
   - **Titre**: "Mon premier article"
   - **Slug**: Auto-généré ou personnalisé (mon-premier-article)
   - **Résumé**: Court résumé (optionnel)
   - **Contenu**: Contenu complet en HTML
3. Cliquez **Créer**
4. L'article apparaît dans la liste et sur le site

### Créer un Projet

1. Allez dans **Projets** → **Créer un projet**
2. Remplissez les champs obligatoires:
   - **Titre**: "Mon App E-commerce"
   - **Slug**: mon-app-ecommerce
   - **Description**: Brève description
3. Remplissez les champs optionnels:
   - **Contenu**: Description détaillée
   - **Technologies**: "PHP, MySQL, Vue.js"
   - **Lien**: URL du projet live
   - **GitHub**: URL du repo GitHub
   - **Image**: Uploadez l'image principale
   - **Vedette**: Cochez pour afficher en avant
4. Cliquez **Créer**

### Ajouter une Compétence

1. Allez dans **Compétences** → **Ajouter une compétence**
2. Remplissez:
   - **Nom**: "PHP"
   - **Catégorie**: "Backend"
   - **Niveau**: Ajustez le slider (ex: 95%)
3. Cliquez **Ajouter**

### Ajouter une Expérience

1. Allez dans **Expériences** → **Ajouter une expérience**
2. Remplissez:
   - **Titre**: "Développeur Web"
   - **Entreprise**: "Tech Company"
   - **Date de début**: 2021-01-01
   - **Date de fin**: 2023-12-31 (ou laissez vide si actuel)
   - **Description**: Vos responsabilités
   - **Actuel**: Cochez si vous y travaillez toujours
3. Cliquez **Ajouter**

---

## 👥 Gestion des Utilisateurs

### Promouvoir un Utilisateur

1. Allez dans **Utilisateurs**
2. Onglet **Tous les Utilisateurs**
3. Cliquez le bouton **⬆️** pour promouvoir en admin
4. L'utilisateur reçoit les droits admin

### Retirer les Droits Admin

1. Allez dans **Utilisateurs**
2. Onglet **Administrateurs**
3. Cliquez le bouton **⬇️ Retirer les droits**
4. Confirmez l'action

**Note:** Vous ne pouvez pas retirer vos propres droits.

---

## 🔒 Sécurité

### Mesures de Sécurité Implémentées

1. **Authentification Requise**
   - Toutes les pages admin nécessitent d'être connecté
   - Vérification du statut admin

2. **Protection CSRF**
   - Tous les formulaires ont un token CSRF unique
   - Les tokens expirent après chaque requête

3. **Hachage des Mots de Passe**
   - Algorithme bcrypt
   - Migration automatique depuis SHA-1 vers bcrypt

4. **Requêtes Préparées**
   - Protection contre l'injection SQL
   - Tous les inputs sont échappés

5. **Validation des Entrées**
   - Vérification des types
   - Sanitization du contenu HTML

6. **Audit Trail (Logs)**
   - Enregistrement de toutes les actions
   - Traçabilité complète

7. **Upload Sécurisé**
   - Vérification MIME type
   - Noms de fichiers nettoyés
   - Stockage en dehors du web root

### Bonnes Pratiques

- ✅ Utilisez des mots de passe forts (min. 12 caractères)
- ✅ Déconnectez-vous après chaque session
- ✅ Ne partagez jamais vos identifiants
- ✅ Mettez à jour régulièrement votre navigateur
- ✅ Activez HTTPS en production

---

## 🐛 Dépannage

### Je ne vois pas le bouton d'administration

**Solution:**
1. Assurez-vous que vous êtes connecté
2. Vérifiez votre statut admin en base de données:
   ```sql
   SELECT is_admin FROM users WHERE email = 'votre-email';
   ```
3. Si `is_admin = 0`, exécutez:
   ```sql
   UPDATE users SET is_admin = 1 WHERE email = 'votre-email';
   ```

### J'accès `/admin` mais j'obtiens une erreur 403

**Solution:**
1. Vérifiez que vous êtes connecté (allez sur `/login`)
2. Vérifiez votre statut admin en base de données
3. Videz le cache du navigateur (Ctrl+Shift+Suppr)

### Erreur lors de l'upload d'image

**Vérifications:**
1. Le dossier `assets/images/projects/` existe-t-il?
2. Les permissions du dossier sont-elles 755?
3. La taille du fichier est-elle conforme (< 5MB)?
4. Le format est-il JPEG, PNG ou WebP?

**Solution:**
```bash
# Créer le dossier s'il n'existe pas
mkdir -p assets/images/projects

# Fixer les permissions
chmod 755 assets/images/projects
chmod 755 assets/images
```

### Les logs n'apparaissent pas

**Solution:**
1. Vérifiez que la table `admin_logs` existe:
   ```sql
   SHOW TABLES LIKE 'admin_logs';
   ```
2. Si elle n'existe pas, réexécutez la migration
3. Rafraîchissez la page (/admin/logs)

### Problème de CSRF token

**Solution:**
1. Videz le cache du navigateur
2. Déconnectez-vous puis reconnectez-vous
3. Le token devrait être généré automatiquement

---

## 📚 Architecture

### Structure des Fichiers

```
app/Controllers/
  ├── AdminController.php           # Controller principal
  ├── AdminBlogController.php       # Gestion blog
  ├── AdminProjectController.php    # Gestion projets
  ├── AdminSkillController.php      # Gestion compétences
  └── AdminExperienceController.php # Gestion expériences

app/Models/
  └── AdminUser.php                 # Modèle utilisateur admin

app/Views/
  └── admin/
      ├── dashboard.php             # Tableau de bord
      ├── users.php                 # Gestion utilisateurs
      ├── logs.php                  # Logs d'audit
      ├── settings.php              # Paramètres
      ├── blog/                      # Vues blog
      ├── projects/                 # Vues projets
      ├── skills/                   # Vues compétences
      └── experience/               # Vues expériences

app/Views/layouts/
  └── admin.php                     # Layout admin

includes/
  └── AdminAuth.php                 # Fonctions d'authentification admin

assets/
  ├── css/
  │   ├── admin.css                 # Styles admin
  │   └── admin.min.css             # Styles minifiés
  └── js/
      ├── admin.js                  # Scripts admin
      └── admin.min.js              # Scripts minifiés
```

### Classes Principales

#### AdminController
- `dashboard()` - Affiche le tableau de bord
- `users()` - Gère les utilisateurs
- `promoteUser()` - Promeut un utilisateur
- `revokeUser()` - Retire les droits admin
- `logs()` - Affiche les logs
- `settings()` - Paramètres

#### AdminBlogController
- `index()` - Liste les articles
- `create()` - Formulaire création
- `store()` - Sauvegarde l'article
- `edit()` - Formulaire édition
- `update()` - Met à jour l'article
- `delete()` - Supprime l'article

---

## 🎨 Personnalisation

### Modifier les Couleurs

Éditez les variables CSS dans `assets/css/admin.css`:

```css
:root {
    --admin-primary: #667eea;      /* Couleur primaire */
    --admin-secondary: #764ba2;    /* Couleur secondaire */
    --admin-success: #43e97b;      /* Couleur succès */
    --admin-danger: #f5576c;       /* Couleur danger */
    --admin-warning: #f5a623;      /* Couleur warning */
    /* ... */
}
```

### Ajouter des Catégories de Compétences

Éditez le formulaire des compétences dans `app/Views/admin/skills/form.php`:

```html
<select id="category" name="category" required>
    <option value="Ma Nouvelle Catégorie">Ma Nouvelle Catégorie</option>
    <!-- ... -->
</select>
```

---

## 📞 Support

Pour toute question ou problème:
1. Consultez cette documentation
2. Vérifiez les logs: `/admin/logs`
3. Vérifiez la base de données avec phpMyAdmin
4. Consultez le fichier `.agent.md` pour les conventions du projet

---

## 📄 Licence

Ce système d'administration fait partie du projet Portfolio et est soumis à la même licence.

**Version:** 1.0.0  
**Dernière mise à jour:** 2024-01-01
