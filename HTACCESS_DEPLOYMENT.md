# 📋 Guide de déploiement - .htaccess pour production

## Fichiers .htaccess inclus

Deux versions sont disponibles:

### 1. `.htaccess` (Version Locale)
- **Utilisé pour:** Développement local (Laragon)
- **RewriteBase:** `/porteflio-with-back-end/`
- **URL:** `http://localhost/porteflio-with-back-end/`
- **État:** Actuellement actif ✅

### 2. `.htaccess.prod` (Version Production)
- **Utilisé pour:** Serveur InfinityFree
- **RewriteBase:** `/`
- **URL:** `https://votre-domaine.com/`
- **Inclut:**
  - Force HTTPS automatique
  - Headers de sécurité supplémentaires
  - Compression GZIP
  - Cache côté client
  - HSTS (HTTP Strict Transport Security)

---

## 🚀 Instructions pour la production

### Étape 1: Avant de déployer
- Utilise le fichier `.htaccess` localement (déjà configuré ✅)
- Teste tout localement: `http://localhost/porteflio-with-back-end/`

### Étape 2: Sur InfinityFree (via FTP)

#### Option A: Renommer le fichier (Recommandé)
```bash
1. Garder .htaccess (local) dans le dossier dev/
2. Downloader .htaccess.prod du serveur
3. Renommer sur le serveur:
   .htaccess → .htaccess.old (backup)
   .htaccess.prod → .htaccess
```

#### Option B: Remplacer directement
```bash
1. Uploader .htaccess.prod
2. Via FTP, renommer .htaccess.prod en .htaccess
3. Supprimer l'ancien .htaccess
```

### Étape 3: Vérification

Après renommage, teste:
```
https://votre-domaine.com/         (Homepage)
https://votre-domaine.com/login    (Login page)
https://votre-domaine.com/project/mon-projet  (Project page)
```

---

## ⚙️ Contenu de .htaccess.prod

### Configuration RewriteBase
```apache
RewriteBase /
```
Cette ligne indique à Apache que la racine est `/` et non `/porteflio-with-back-end/`

### Force HTTPS
```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```
Redirige automatiquement HTTP vers HTTPS

### Headers de sécurité supplémentaires
- `Strict-Transport-Security` → Force HTTPS pendant 1 an
- `X-Content-Type-Options` → Prévient MIME sniffing
- `X-Frame-Options` → Prévient clickjacking
- `X-XSS-Protection` → Protection XSS renforcée

### Compression GZIP
```apache
<IfModule mod_gzip.c>
    ...
</IfModule>
```
Compresse les réponses (JS, CSS, HTML, XML) pour plus de rapidité

### Cache côté client
```apache
<IfModule mod_expires.c>
    ...
</IfModule>
```
Images cachées 1 an, CSS/JS 30 jours, HTML 1 jour

---

## 🔄 Retour en local

Si tu dois revenir en locale après avoir modifié:
```bash
1. Sur le serveur, renommer:
   .htaccess → .htaccess.prod
2. Sur ton ordinateur:
   // Colle le bon .htaccess local
   RewriteBase /porteflio-with-back-end/
```

---

## ✅ Checklist final

- [ ] Tester localement avec `.htaccess` local
- [ ] Supprimer les fichiers de test
- [ ] Créer sauvegarde de la BD
- [ ] Uploader tous les fichiers
- [ ] **Renommer `.htaccess` en `.htaccess.old`**
- [ ] **Renommer `.htaccess.prod` en `.htaccess`**
- [ ] Tester le domaine production
- [ ] Vérifier les logs: `/logs/errors.log`
- [ ] Vérifier que HTTPS fonctionne
- [ ] Tester les fonctionnalités (login, commentaires, etc.)

---

## 🐛 Si ça ne fonctionne pas

### "404 Not Found"
```
→ Vérifier que .htaccess est bien présent
→ Vérifier les permissions: 644
→ Vérifier que mod_rewrite est actif
```

### "Internal Server Error"
```
→ Consulter /logs/errors.log
→ Vérifier la syntaxe du .htaccess
→ Contact support InfinityFree si mod_rewrite n'est pas actif
```

### CSS/JS ne chargent pas
```
→ Vérifier que $systemUrl est correct
→ Vérifier que /assets/ a les bonnes permissions
→ Tester l'accès direct: https://votre-domaine.com/assets/css/styles.min.css
```

---

**Bonne chance! 🚀**
