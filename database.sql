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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Date de création
);

-- Création de la table skills
-- Stocke les compétences techniques
CREATE TABLE IF NOT EXISTS skills (
    id INT PRIMARY KEY AUTO_INCREMENT,        -- Identifiant unique auto-incrémenté
    name VARCHAR(100) NOT NULL,               -- Nom de la compétence
    level INT NOT NULL,                       -- Niveau de maîtrise (0-100)
    category VARCHAR(50),                     -- Catégorie de la compétence
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Date de création
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

-- Insertion des données de test
-- Données de profil
INSERT INTO profile (name, title, description, location, email, website) VALUES
('James Lee', 'Web App Developer', 'Développeur web passionné avec plus de 5 ans d''expérience', 'San Francisco, US', 'jameslee@website.com', 'https://www.website.com');

-- Données des projets
INSERT INTO projects (title, description, image_url, project_url, is_featured) VALUES
('Launch - Template SaaS', 'Un template Bootstrap parfait pour les produits SaaS', 'assets/images/projects/project-featured.jpg', 'https://example.com/launch', 1),
('CoderPro - Template Startup', 'Template Bootstrap pour projets logiciels', 'assets/images/projects/project-1.png', 'https://example.com/coderpro', 0);

-- Données des compétences
INSERT INTO skills (name, level, category) VALUES
('Python & Django', 96, 'Backend'),
('Javascript & jQuery', 94, 'Frontend'),
('HTML5, CSS3, SASS & LESS', 93, 'Frontend'),
('Ruby on Rails', 86, 'Backend');

-- Données de l'expérience
INSERT INTO experience (title, company, location, start_date, end_date, description) VALUES
('Co-Founder & Lead Developer', 'Startup Hub', 'San Francisco', '2024-01-01', NULL, 'Description du poste...'),
('Software Engineer', 'Google', 'Mountain View', '2020-01-01', '2024-01-01', 'Description du poste...');

-- Insertion d'un utilisateur de test
INSERT INTO users (username, email, password) VALUES
('test_user', 'test@example.com', SHA1('password123')); -- mot de passe: password123 