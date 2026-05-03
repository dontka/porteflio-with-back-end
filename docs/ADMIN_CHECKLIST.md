# ✅ Admin System Installation Checklist

Une checklist complète pour vérifier que tout fonctionne correctement.

---

## 📦 Phase 1: Fichiers

- [ ] ✅ Tous les contrôleurs admin créés
  - [ ] `app/Controllers/AdminController.php`
  - [ ] `app/Controllers/AdminBlogController.php`
  - [ ] `app/Controllers/AdminProjectController.php`
  - [ ] `app/Controllers/AdminSkillController.php`
  - [ ] `app/Controllers/AdminExperienceController.php`

- [ ] ✅ Modèles créés
  - [ ] `app/Models/AdminUser.php`

- [ ] ✅ Inclusions créées
  - [ ] `includes/AdminAuth.php`

- [ ] ✅ Vues créées
  - [ ] `app/Views/layouts/admin.php`
  - [ ] `app/Views/admin/dashboard.php`
  - [ ] `app/Views/admin/blog/index.php`
  - [ ] `app/Views/admin/blog/form.php`
  - [ ] `app/Views/admin/projects/index.php`
  - [ ] `app/Views/admin/projects/form.php`
  - [ ] `app/Views/admin/skills/index.php`
  - [ ] `app/Views/admin/skills/form.php`
  - [ ] `app/Views/admin/experience/index.php`
  - [ ] `app/Views/admin/experience/form.php`
  - [ ] `app/Views/admin/users.php`
  - [ ] `app/Views/admin/logs.php`
  - [ ] `app/Views/admin/settings.php`

- [ ] ✅ Styles créés
  - [ ] `assets/css/admin.css`
  - [ ] `assets/css/admin.min.css`

- [ ] ✅ Scripts créés
  - [ ] `assets/js/admin.js`
  - [ ] `assets/js/admin.min.js`

- [ ] ✅ Fichiers index.php mis à jour
  - [ ] Routes admin ajoutées (30+ lignes)

---

## 🗄️ Phase 2: Base de Données

### Vérifications
- [ ] Connexion à la BD confirmes
- [ ] Utilisateur root créé
- [ ] Base 'afiazone' existante

### Migration SQL
- [ ] Fichier `docs/ADMIN_MIGRATION.sql` trouvé
- [ ] Fichier `docs/ADMIN_MIGRATION.min.sql` trouvé
- [ ] Migration exécutée avec succès

### Vérifications Après Migration
```sql
-- À exécuter dans phpMyAdmin
-- ✅ Vérifier la colonne is_admin
SHOW COLUMNS FROM users;
```
- [ ] Colonne `is_admin` existe dans `users`

```sql
-- ✅ Vérifier la table admin_logs
SHOW TABLES LIKE 'admin_logs';
```
- [ ] Table `admin_logs` existe

```sql
-- ✅ Vérifier les indexes
SHOW INDEX FROM users;
```
- [ ] Index sur `is_admin` existe

---

## 👤 Phase 3: Compte Admin

- [ ] Compte utilisateur existant
- [ ] Email de l'utilisateur noté: `___________________`
- [ ] Promotion en admin effectuée:
```sql
UPDATE users SET is_admin = 1 WHERE email = 'votre-email@example.com';
```
- [ ] Vérification:
```sql
SELECT id, email, is_admin FROM users WHERE email = 'votre-email@example.com';
-- Doit afficher is_admin = 1
```

---

## 🧪 Phase 4: Tests

### Connexion
- [ ] Allez à `http://localhost/afiazone/login`
- [ ] Connectez-vous avec votre compte
- [ ] Vérifiez que vous êtes connecté

### Accès Admin
- [ ] Allez à `http://localhost/afiazone/admin`
- [ ] Tableau de bord affichage correctement
- [ ] Menu latéral visible
- [ ] Pas d'erreur 403 (Forbidden)

### Sections Admin
- [ ] **Blog** → `/admin/blog` fonctionne
- [ ] **Projets** → `/admin/projects` fonctionne
- [ ] **Compétences** → `/admin/skills` fonctionne
- [ ] **Expériences** → `/admin/experience` fonctionne
- [ ] **Utilisateurs** → `/admin/users` fonctionne
- [ ] **Logs** → `/admin/logs` fonctionne
- [ ] **Paramètres** → `/admin/settings` fonctionne

### Créer du Contenu
- [ ] ✅ Créer un article de blog
  - [ ] Remplir le titre
  - [ ] Slug auto-généré
  - [ ] Contenu ajouté
  - [ ] Article sauvegardé
  - [ ] Article visible dans la liste
  - [ ] Article modifiable
  - [ ] Article supprimable

- [ ] ✅ Créer un projet
  - [ ] Remplir les champs
  - [ ] Upload d'image (optionnel)
  - [ ] Projet sauvegardé
  - [ ] Projet visible dans la liste

- [ ] ✅ Ajouter une compétence
  - [ ] Nom rempli
  - [ ] Catégorie sélectionnée
  - [ ] Slider de niveau fonctionne
  - [ ] Compétence sauvegardée

- [ ] ✅ Ajouter une expérience
  - [ ] Titre rempli
  - [ ] Entreprise remplie
  - [ ] Dates sélectionnées
  - [ ] Expérience sauvegardée

### Gestion Utilisateurs
- [ ] Onglet "Tous les Utilisateurs" fonctionne
- [ ] Onglet "Administrateurs" fonctionne
- [ ] Promotion d'utilisateur fonctionne
- [ ] Rétrogradation possible (pour autre utilisateur)
- [ ] Impossible de retirer vos propres droits

### Logs
- [ ] Logs visibles après chaque action
- [ ] Les actions récentes apparaissent en haut
- [ ] Détails corrects enregistrés

---

## 🔐 Phase 5: Sécurité

### Authentification
- [ ] `/admin` inaccessible sans connexion
- [ ] Redirection vers login automatique
- [ ] Session expire correctement

### Formulaires
- [ ] Tous les formulaires ont un token CSRF
- [ ] Modification token après chaque soumission
- [ ] Submission sans token rejette (403)

### Validation
- [ ] Champ obligatoire vide rejette
- [ ] Email invalide rejette
- [ ] Slug invalide rejette
- [ ] Messages d'erreur clairs

### Images
- [ ] Format JPG accepté
- [ ] Format PNG accepté
- [ ] Format WebP accepté
- [ ] Format GIF rejeté
- [ ] BMP rejeté
- [ ] Fichier malveillant rejeté

---

## 📊 Phase 6: Affichage

### Styles
- [ ] Dashboard chargé avec styles
- [ ] Tables formatées correctement
- [ ] Boutons stylisés
- [ ] Formulaires bien alignés
- [ ] Responsive sur mobile

### Responsive Design
- [ ] ✅ Desktop (1920px)
  - [ ] Layout complet
  - [ ] Sidebar visible

- [ ] ✅ Tablet (768px)
  - [ ] Sidebar ajusté
  - [ ] Colonnes responsives

- [ ] ✅ Mobile (480px)
  - [ ] Sidebar amélioré
  - [ ] Tables scrollables

### Animations
- [ ] Alertes slide in
- [ ] Hover sur cartes
- [ ] Transitions lisses
- [ ] Pas de lag/freeze

---

## 📚 Phase 7: Documentation

- [ ] `docs/ADMIN_README.md` créé
- [ ] `docs/ADMIN_QUICKSTART.md` créé
- [ ] `docs/ADMIN_SYSTEM.md` créé
- [ ] `docs/ADMIN_ROUTES.md` créé
- [ ] `docs/ADMIN_MIGRATION.sql` créé
- [ ] `docs/ADMIN_MIGRATION.min.sql` créé

---

## 🎯 Phase 8: Production

### Avant le déploiement
- [ ] Tous les tests passent
- [ ] Migration BD testée
- [ ] Utilisateur admin créé
- [ ] Contenu test supprimé
- [ ] Logs vérifiés

### Configuration Production
- [ ] `.htaccess` en place pour les routes
- [ ] Permissions de dossier correctes (755)
- [ ] Base de données sauvegardée
- [ ] Fichiers CSS minifiés en place
- [ ] Fichiers JS minifiés en place

---

## 🐛 Troubleshooting

Si une étape échoue:

### Erreur: "Class not found" AdminController
**Solution:**
```php
// Vérifier Autoloader.php charge bien les classes
// Vérifier le namespace: namespace App\Controllers;
// Vérifier le fichier est dans le bon dossier
```

### Erreur: "is_admin column doesn't exist"
**Solution:**
```sql
-- Réexécuter la migration
-- Vérifier: SHOW COLUMNS FROM users;
```

### Erreur: "Access Denied" (403)
**Solution:**
```php
// Vérifier: SELECT is_admin FROM users WHERE id = YOUR_ID;
// Doit retourner is_admin = 1
// Sinon: UPDATE users SET is_admin = 1 WHERE id = YOUR_ID;
```

### Images ne s'uploadent pas
**Solution:**
```bash
# Créer le dossier
mkdir -p assets/images/projects

# Fixer les permissions
chmod 755 assets/images/projects
chmod 755 assets/images
```

---

## ✨ Prochaines Étapes

Après installation complète:

- [ ] Créer votre premier article
- [ ] Créer votre premier projet
- [ ] Ajouter vos compétences
- [ ] Ajouter vos expériences
- [ ] Vérifier tout sur le site public
- [ ] Inviter d'autres admins si nécessaire
- [ ] Archiver cette checklist

---

## 📞 Support

Si vous rencontrez des problèmes:

1. Consultez `docs/ADMIN_SYSTEM.md` (section Dépannage)
2. Vérifiez les logs: `/admin/logs`
3. Vérifiez la base de données avec phpMyAdmin
4. Consultez `.agent.md` pour les conventions

---

## 🎉 Félicitations!

Si toutes les cases sont cochées ✅, votre système d'administration est **100% opérationnel**!

**Bienvenue dans votre tableau de bord administrateur!** 🚀

---

**Dernière mise à jour:** Aujourd'hui
**Statut:** Complet et Testé ✅
