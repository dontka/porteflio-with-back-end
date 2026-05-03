# ⚡ Quick Start - Système d'Administration

## 3 Étapes pour Démarrer

### 1️⃣ Migration SQL (1 minute)

**Via phpMyAdmin:**
1. Ouvrir phpMyAdmin → Base "afiazone"
2. Onglet "SQL"
3. Copier/Coller de `docs/ADMIN_MIGRATION.sql`
4. Cliquer "Exécuter"

**Ou via terminal:**
```bash
mysql -u root -p afiazone < docs/ADMIN_MIGRATION.sql
```

### 2️⃣ Promouvoir un Admin (30 secondes)

**phpMyAdmin SQL:**
```sql
UPDATE users SET is_admin = 1 WHERE id = 1;
```

### 3️⃣ Se Connecter à l'Admin (immédiat)

1. Connectez-vous: `http://localhost/afiazone/login`
2. Allez à: `http://localhost/afiazone/admin`
3. ✅ Vous êtes dans l'administration!

---

## Routes Principales

| URL | Fonction |
|-----|----------|
| `/admin` | 📊 Tableau de bord |
| `/admin/blog` | 📰 Gérer les articles |
| `/admin/projects` | 🚀 Gérer les projets |
| `/admin/skills` | ⭐ Gérer les compétences |
| `/admin/experience` | 💼 Gérer les expériences |
| `/admin/users` | 👥 Gérer les utilisateurs |
| `/admin/logs` | 📋 Voir les logs |
| `/admin/settings` | ⚙️ Paramètres |

---

## Fichiers Clés

| Fichier | Rôle |
|---------|------|
| `index.php` | ✅ Routes mises à jour |
| `docs/ADMIN_MIGRATION.sql` | ✅ Migration BD |
| `app/Controllers/AdminController.php` | ✅ Controller principal |
| `app/Views/layouts/admin.php` | ✅ Layout admin |
| `assets/css/admin.min.css` | ✅ Styles (minifiés) |
| `assets/js/admin.min.js` | ✅ Scripts (minifiés) |

---

## 🔧 Dépannage Rapide

| Problème | Solution |
|----------|----------|
| Pas d'accès à `/admin` | Vérifiez: `SELECT is_admin FROM users WHERE id = 1;` |
| Erreur migration SQL | Vérifiez les permissions DB et les erreurs de syntaxe |
| Pas d'images | Créez: `mkdir assets/images/projects` |
| CSRF token invalide | Videz le cache (Ctrl+Shift+Suppr) |

---

## ✨ Fonctionnalités Incluses

✅ Tableau de bord avec statistiques  
✅ CRUD Blog (Créer, Lire, Éditer, Supprimer)  
✅ CRUD Projets avec upload images  
✅ CRUD Compétences avec niveau (slider)  
✅ CRUD Expériences  
✅ Gestion utilisateurs (Promotion/Rétrogradation)  
✅ Logs d'audit complets  
✅ Sécurité CSRF  
✅ Protection authentification  
✅ Responsive design  

---

## 📚 Documentation Complète

Voir `docs/ADMIN_SYSTEM.md` pour la documentation complète.

**Vous êtes prêt! Commencez par créer votre premier article! 🚀**
