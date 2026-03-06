-- Création de la base de données
CREATE DATABASE IF NOT EXISTS portfolio;
USE portfolio;

-- Création de la table profile
-- Stocke les informations personnelles de l'utilisateur
CREATE TABLE IF NOT EXISTS profile (
    id INT PRIMARY KEY AUTO_INCREMENT,        -- Identifiant unique auto-incrémenté
    name VARCHAR(100) NOT NULL,               -- Nom complet
    title VARCHAR(100) NOT NULL,              -- Titre professionnel
    description TEXT,                         -- Description détaillée
    location VARCHAR(100),                    -- Localisation
    email VARCHAR(100),                       -- Adresse email
    website VARCHAR(255),                     -- Site web personnel
    github_username VARCHAR(100),             -- Nom d'utilisateur GitHub
    linkedin_url VARCHAR(255),                -- URL LinkedIn
    twitter_url VARCHAR(255),                 -- URL Twitter
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Date de création
);

-- Création de la table projects
-- Stocke les projets réalisés
CREATE TABLE IF NOT EXISTS projects (
    id INT PRIMARY KEY AUTO_INCREMENT,        -- Identifiant unique auto-incrémenté
    title VARCHAR(255) NOT NULL,              -- Titre du projet
    description TEXT,                         -- Description du projet
    image_url VARCHAR(255),                   -- URL de l'image du projet
    project_url VARCHAR(255),                 -- URL du projet
    is_featured BOOLEAN DEFAULT FALSE,        -- Projet mis en avant
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des catégories de compétences
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
);

-- Création de la table skills
-- Stocke les compétences techniques
CREATE TABLE IF NOT EXISTS skills (
    id INT PRIMARY KEY AUTO_INCREMENT,        -- Identifiant unique auto-incrémenté
    name VARCHAR(100) NOT NULL,               -- Nom de la compétence
    level INT NOT NULL,                       -- Niveau de maîtrise (0-100)
    category VARCHAR(50),                     -- Catégorie de la compétence
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category) REFERENCES categories(name) ON DELETE SET NULL
);

-- Création de la table experience
-- Stocke l'expérience professionnelle
CREATE TABLE IF NOT EXISTS experience (
    id INT PRIMARY KEY AUTO_INCREMENT,        -- Identifiant unique auto-incrémenté
    title VARCHAR(255) NOT NULL,              -- Titre du poste
    company VARCHAR(255),                     -- Nom de l'entreprise
    location VARCHAR(100),                    -- Localisation
    start_date DATE NOT NULL,                 -- Date de début
    end_date DATE,                            -- Date de fin (NULL si en cours)
    description TEXT,                         -- Description du poste
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Date de création
);

-- Création de la table users
-- Stocke les informations des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,        -- Identifiant unique auto-incrémenté
    username VARCHAR(50) NOT NULL UNIQUE,     -- Nom d'utilisateur unique
    email VARCHAR(100) NOT NULL UNIQUE,       -- Email unique
    password VARCHAR(255) NOT NULL,           -- Mot de passe hashé
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Date de création
);

-- Création de la table comments
-- Stocke les commentaires des projets
CREATE TABLE IF NOT EXISTS comments (
    id INT PRIMARY KEY AUTO_INCREMENT,        -- Identifiant unique auto-incrémenté
    project_url VARCHAR(255) NOT NULL,        -- URL du projet commenté
    user_id INT NOT NULL,                     -- ID de l'utilisateur
    content TEXT NOT NULL,                    -- Contenu du commentaire
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date de création
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE -- Clé étrangère vers users
);

-- Insertion des catégories de compétences
INSERT INTO categories (name, description) VALUES
('Front-end', 'Technologies liées à l''interface utilisateur'),
('Back-end', 'Technologies liées au serveur et à la base de données'),
('Outils', 'Outils de développement, analyse de données et autres technologies');

-- Insertion des données de profil
INSERT INTO profile (name, title, description, location, email, website, github_username, linkedin_url, twitter_url) VALUES
(
    'Donatien TUMAINI KANANE',
    'Développeur Web Full-Stack & Analyste de Données',
    'Développeur web passionné et analyste de données basé à Goma, RDC. Diplômé en Sciences Biomédicales de l''Université de Goma, je combine mes compétences en développement web (PHP, Python, Django, Kotlin) avec une expertise en collecte et analyse de données (SPSS, Power BI, KOBO Collect). Je crée des solutions numériques innovantes pour les organisations humanitaires, les ONG et le secteur de la santé en Afrique Centrale.',
    'Goma, RDC',
    'donatienkanane@gmail.com',
    'http://schor.alwaysdata.net',
    'dontka',
    'https://cd.linkedin.com/in/afialab',
    NULL
);

-- Insertion des projets
INSERT INTO projects (title, description, image_url, project_url, is_featured) VALUES
('AfiaZone - Marketplace Médicale', 'AfiaZone est une marketplace médicale innovante avec E-Wallet Santé intégré. Cette plateforme facilite l''accès aux produits et services médicaux en RDC grâce à un système de paiement mobile sécurisé.', 'assets/images/projects/project-featured.jpg', 'https://github.com/dontka/afiazone', 1),
('ASBL/ONG Manager', 'Plateforme de gestion d''organisation tout-en-un, modulaire, intelligente, collaborative et conforme, couvrant tous les besoins métiers d''une ONG, association ou entreprise moderne.', 'assets/images/projects/project-1.png', 'https://github.com/dontka/asbl-ong-manager', 0),
('PHP Payment Gateway Manager', 'Package PHP complet permettant d''installer et gérer plusieurs systèmes de paiement : Mobile Money, Moov, Airtel, Orange, Vodacom, Africell, Stripe, PayPal et bien d''autres.', 'assets/images/projects/project-2.png', 'https://github.com/dontka/all-php-payment-gateway-manager', 0),
('Saint-Tharcisse', 'Application de gestion du groupe de servants de messe développée en PHP pour faciliter l''organisation et le suivi des activités paroissiales.', 'assets/images/projects/project-3.png', 'https://github.com/dontka/Saint-Tharcisse', 0),
('CRUD ASBL/ONG', 'Système adaptable pour tout type de site web pour associations à but non lucratif (ABNL) ou organisations non gouvernementales (ONG), avec gestion CRUD complète.', 'assets/images/projects/project-4.png', 'https://github.com/dontka/crud-asbl-ong', 0),
('SCHOR Center', 'Site web du Centre de Consultance Scientifique et d''Orientation Humanitaire (SCHOR), offrant des services de consultance et d''orientation professionnelle.', 'assets/images/projects/project-5.png', 'https://github.com/dontka/dontka.github.io', 0),
('E-Commerce Responsive', 'Site e-commerce responsive développé avec HTML, CSS et JavaScript, offrant une expérience d''achat moderne et adaptée à tous les appareils.', 'assets/images/projects/project-6.png', 'https://github.com/dontka/e-commerce-responsive-website', 0),
('Blog avec Back-end', 'Plateforme de blog complète avec système d''authentification, gestion des articles et commentaires, développée en PHP et MySQL.', 'assets/images/projects/project-7.png', 'https://github.com/dontka/blog-with-back-end', 0);

-- Insertion des compétences
INSERT INTO skills (name, level, category) VALUES
('PHP & Laravel', 92, 'Back-end'),
('Python & Django', 85, 'Back-end'),
('HTML5 & CSS3', 95, 'Front-end'),
('JavaScript & jQuery', 88, 'Front-end'),
('MySQL & Base de données', 90, 'Back-end'),
('Bootstrap & UI/UX', 90, 'Front-end'),
('Kotlin', 70, 'Back-end'),
('Power BI & Analyse de données', 85, 'Outils'),
('KOBO Collect & SPSS', 88, 'Outils'),
('Git & GitHub', 85, 'Outils');

-- Insertion de l'expérience professionnelle
INSERT INTO experience (title, company, location, start_date, end_date, description) VALUES
('Développeur Web Freelance', 'Afiatalk', 'Goma, RDC', '2023-01-01', NULL, 'Développement et maintenance de plateformes web. Création de solutions numériques innovantes pour le secteur de la santé et de la communication.'),
('Secrétaire Département Recherche', 'Medical Student Association (MSA/Nord-Kivu)', 'Goma, RDC', '2023-01-01', NULL, 'Coordination du département de recherche et échange. Organisation de formations en recherche, collecte et analyse de données.'),
('Volontaire Programme FIKIRI', 'PNUD', 'Goma, RDC', '2025-01-01', NULL, 'Vulgarisation de la plateforme FIKIRI visant à identifier, cartographier et expérimenter des solutions innovantes pour accélérer les ODD.'),
('Enquêteur et Encodeur de Données', 'Bio Grandlac Nord-Kivu', 'Goma, RDC', '2024-04-01', '2025-01-31', 'Collecte de données terrain, encodage et traitement des informations pour des projets de recherche en santé publique.'),
('Encodeur et Analyste de Données', 'CREHP', 'Goma, RDC', '2024-01-01', '2024-12-31', 'Encodage et analyse des données de recherche au sein du Center of Research Expertise and Health Promotion.'),
('Sensibilisateur Prévention Abus Sexuels', 'Heal Africa', 'Masisi, RDC', '2021-01-01', '2022-12-31', 'Sensibilisation communautaire dans le projet de Prévention et Abus Sexuels dans le territoire de Masisi.');

-- Insertion d'un utilisateur administrateur
INSERT INTO users (username, email, password) VALUES
('donatien', 'donatienkanane@gmail.com', SHA1('password123'));

-- Insertion d'un utilisateur administrateur par défaut
INSERT INTO users (username, email, password) VALUES
('admin', 'admin@portfolio.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- mot de passe: password

-- Création de la table blog
-- Stocke les articles de blog / actualités
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content TEXT NOT NULL,
    image_url VARCHAR(255),
    category VARCHAR(50) DEFAULT 'Général',
    is_published BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertion des articles de blog
INSERT INTO blog_posts (title, slug, excerpt, content, image_url, category) VALUES
('Introduction à l''analyse de données avec Power BI', 'introduction-power-bi', 'Découvrez comment Power BI peut transformer vos données brutes en visualisations percutantes pour la prise de décision.', 'Power BI est un outil de Business Intelligence développé par Microsoft qui permet de connecter, modéliser et visualiser des données de manière interactive. Dans cet article, nous explorons les fonctionnalités clés de Power BI et comment l''utiliser efficacement pour l''analyse de données dans le contexte humanitaire et de la santé publique en RDC.\n\nLes étapes clés :\n1. Connexion aux sources de données\n2. Nettoyage et transformation des données\n3. Création de modèles de données\n4. Conception de tableaux de bord interactifs\n5. Partage et collaboration', NULL, 'Data & Analyse'),
('Développer une API RESTful avec Laravel', 'api-restful-laravel', 'Guide pratique pour créer une API RESTful robuste et sécurisée avec le framework Laravel.', 'Laravel est l''un des frameworks PHP les plus populaires pour le développement d''applications web modernes. Dans ce guide, nous allons parcourir les étapes essentielles pour construire une API RESTful complète.\n\nNous aborderons :\n- La configuration du projet Laravel\n- La création des migrations et modèles\n- Les contrôleurs de ressources\n- L''authentification avec Laravel Sanctum\n- La validation des données\n- La gestion des erreurs\n- Les bonnes pratiques de versioning', NULL, 'Développement'),
('KOBO Collect : Outil essentiel pour la collecte de données terrain', 'kobo-collect-collecte-donnees', 'Comment KOBO Collect révolutionne la collecte de données dans les projets humanitaires et de recherche en santé.', 'KOBO Collect est une plateforme open-source de collecte de données largement utilisée par les organisations humanitaires et de recherche. Basé à Goma, je l''utilise quotidiennement pour des projets de santé publique.\n\nAvantages de KOBO Collect :\n- Collecte hors-ligne\n- Formulaires personnalisables\n- Analyses en temps réel\n- Exportation multiformat\n- Gratuit pour les organisations humanitaires', NULL, 'Data & Analyse'),
('Le rôle du numérique dans la santé en RDC', 'numerique-sante-rdc', 'Comment la technologie transforme l''accès aux soins de santé dans l''est de la République Démocratique du Congo.', 'La digitalisation du secteur de la santé en RDC représente un défi mais aussi une opportunité immense. En combinant mon parcours en sciences biomédicales et en développement web, je travaille sur des solutions qui améliorent l''accès aux soins.\n\nProjets et initiatives :\n- AfiaZone : marketplace médicale avec e-wallet santé\n- Systèmes de suivi des patients\n- Plateformes de télémédecine\n- Outils de collecte de données épidémiologiques', NULL, 'Santé & Tech'),
('Premiers pas avec Django pour les développeurs PHP', 'django-pour-developpeurs-php', 'Transition de PHP à Python/Django : guide pour les développeurs web souhaitant élargir leurs compétences.', 'Si vous êtes développeur PHP et souhaitez apprendre Django, cet article est fait pour vous. Django est un framework Python puissant qui suit le principe "batteries included".\n\nComparaisons clés :\n- Routing : routes.php vs urls.py\n- ORM : Eloquent vs Django ORM\n- Templates : Blade vs Django Templates\n- Admin : Nova vs Django Admin (intégré !)\n- Migrations : artisan migrate vs manage.py migrate', NULL, 'Développement'),
('Optimiser la gestion des ONG avec des outils numériques', 'gestion-ong-outils-numeriques', 'Solutions technologiques pour moderniser la gestion quotidienne des organisations à but non lucratif.', 'Les ONG et ASBL en RDC font face à des défis de gestion importants. Les outils numériques peuvent considérablement améliorer leur efficacité opérationnelle.\n\nSolutions présentées :\n- ASBL/ONG Manager : plateforme de gestion tout-en-un\n- Automatisation des rapports\n- Suivi des bénéficiaires\n- Gestion financière transparente\n- Communication interne', NULL, 'Général');