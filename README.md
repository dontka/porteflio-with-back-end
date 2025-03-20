# TutoLabPro - Portfolio Dynamique

Un portfolio professionnel dynamique développé avec PHP, MySQL et Bootstrap. Ce projet permet de créer un portfolio personnalisé avec une gestion dynamique du contenu via une base de données.

## 🚀 Fonctionnalités

- Design responsive et moderne
- Mode sombre/clair
- Sections personnalisables :
  - Profil
  - Projets
  - Compétences
  - Expérience professionnelle
- Intégration GitHub (calendrier et flux d'activité)
- Animations fluides
- Gestion multilingue
- Mode debug
- Système de commentaires authentifié

## 📋 Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx)
- Composer (optionnel)

## 🛠️ Installation

1. Clonez le dépôt :
```bash
git clone https://github.com/votre-username/tutolabpro.git
```

2. Créez la base de données en utilisant le script SQL fourni :
```bash
mysql -u votre_utilisateur -p votre_base_de_donnees < database.sql
```

3. Configurez la connexion à la base de données dans `config.php` :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'votre_base_de_donnees');
define('DB_USER', 'votre_utilisateur');
define('DB_PASS', 'votre_mot_de_passe');
```

4. Configurez votre serveur web pour pointer vers le répertoire du projet

## 📁 Structure du Projet

```
tutolabpro/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── includes/
│   ├── Database.php
│   ├── functions.php
│   └── handle_comment.php
├── config.php
├── database.sql
├── index.php
├── project.php
├── login.php
└── logout.php
```

## ⚙️ Configuration

Le fichier `config.php` contient les paramètres suivants :

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'votre_base_de_donnees');
define('DB_USER', 'votre_utilisateur');
define('DB_PASS', 'votre_mot_de_passe');
define('DEBUGGING', false);
define('SYS_URL', 'http://votre-domaine.com/');
define('DEFAULT_LOCALE', 'fr');
```

## 🎨 Personnalisation

### Thème
- Modifiez les couleurs dans `assets/css/styles.css`
- Personnalisez les polices dans `index.php`

### Contenu
- Ajoutez/modifiez les données dans la base de données
- Personnalisez les sections dans `index.php`

## 🔒 Sécurité

- Protection contre les injections SQL via PDO
- Échappement des données affichées
- Gestion sécurisée des mots de passe
- Protection XSS

## 🌐 Support Multilingue

Le projet supporte le multilingue via la constante `DEFAULT_LOCALE`. Pour ajouter une nouvelle langue :

1. Créez un fichier de traduction dans `assets/locales/`
2. Modifiez `DEFAULT_LOCALE` dans `config.php`

## 🐛 Mode Debug

Activez le mode debug dans `config.php` pour afficher les erreurs :

```php
define('DEBUGGING', true);
```

## 📝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :

1. Fork le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commit vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 👥 Auteurs

- Votre Nom - [@votre_twitter](https://twitter.com/votre_twitter)

## 🙏 Remerciements

- [Bootstrap](https://getbootstrap.com/)
- [Font Awesome](https://fontawesome.com/)
- [GitHub Calendar](https://github.com/IonicaBizau/github-calendar)
- [GitHub Activity Feed](https://github.com/caseyscarborough/github-activity)
- [jQuery](https://jquery.com/)

## 📄 Système de commentaires

Le système de commentaires permet aux utilisateurs connectés de commenter les projets :

### 🚀 Fonctionnalités
- Authentification requise pour commenter
- Commentaires en temps réel avec AJAX
- Affichage du nom d'utilisateur et de la date
- Protection contre les injections SQL et XSS
- Gestion des sessions sécurisée

### 📋 Utilisation
1. Créez un compte utilisateur dans la base de données :
```sql
INSERT INTO users (username, email, password) 
VALUES ('votre_username', 'votre@email.com', '$2y$10$votre_hash_password');
```

2. Connectez-vous avec vos identifiants
3. Accédez à un projet pour voir et ajouter des commentaires

### 🔒 Sécurité
- Protection contre les injections SQL avec PDO
- Protection XSS avec htmlspecialchars()
- Mots de passe hashés avec password_hash()
- Sessions sécurisées
- Validation des données côté serveur 