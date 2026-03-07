<?php
require_once 'config.php';
session_start();
require_once 'includes/Database.php';
require_once 'includes/functions.php';

$database = new Database();
$db = $database->getConnection();

$profile = getProfileData($db);
$projects = getProjects($db);
$skills = getSkills($db);
$experience = getExperience($db);
$blogPosts = getBlogPosts($db, 6);

$debugMode = isDebugMode();
$systemUrl = getSystemUrl();
$locale = getDefaultLocale();
$projectCount = count($projects);
$skillCount = count($skills);
$projectStats = getAllProjectStats($db);
$blogStats = getAllBlogStats($db);

$pageTitle = 'Donatien KANANE — Développeur Full-Stack & Data Analyst';
$pageDescription = sanitizeOutput($profile['description'] ?? 'Portfolio de Donatien KANANE - Développeur Web Full-Stack & Analyste de Données basé à Goma, RDC');
$pageUrl = $systemUrl;
$pageImage = $systemUrl . 'assets/images/profile.png';
?>
<!DOCTYPE html>
<html lang="<?php echo substr($locale, 0, 2); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests; default-src * 'self' data:; script-src * 'self' 'unsafe-inline' 'unsafe-eval'; style-src * 'self' 'unsafe-inline'; font-src * 'self' data:; img-src * 'self' data:; connect-src * 'self'; object-src 'none'">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $pageDescription; ?>">
    <meta name="author" content="<?php echo sanitizeOutput($profile['name'] ?? 'Donatien KANANE'); ?>">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#6366f1">
    <link rel="canonical" href="<?php echo $pageUrl; ?>">
    <link rel="alternate" hreflang="fr" href="<?php echo $pageUrl; ?>">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $pageTitle; ?>">
    <meta property="og:description" content="<?php echo $pageDescription; ?>">
    <meta property="og:url" content="<?php echo $pageUrl; ?>">
    <meta property="og:image" content="<?php echo $pageImage; ?>">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="Donatien KANANE Portfolio">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $pageTitle; ?>">
    <meta name="twitter:description" content="<?php echo $pageDescription; ?>">
    <meta name="twitter:image" content="<?php echo $pageImage; ?>">

    <link rel="shortcut icon" href="favicon.ico">

    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/plugins/github-calendar/dist/github-calendar-responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/octicons/2.0.2/octicons.min.css">
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/plugins/github-activity/src/github-activity.min.css">
    <link id="theme-style" rel="stylesheet" href="<?php echo $systemUrl; ?>assets/css/styles.min.css?v=<?php echo time(); ?>">

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "<?php echo sanitizeOutput($profile['name'] ?? 'Donatien KANANE'); ?>",
        "jobTitle": "<?php echo sanitizeOutput($profile['title'] ?? 'Développeur Web Full-Stack & Data Analyst'); ?>",
        "url": "<?php echo $pageUrl; ?>",
        "image": "<?php echo $pageImage; ?>",
        "description": "<?php echo $pageDescription; ?>",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "<?php echo sanitizeOutput($profile['location'] ?? 'Goma, RDC'); ?>"
        },
        "email": "<?php echo sanitizeOutput($profile['email'] ?? ''); ?>"
        <?php if (!empty($profile['github_username'])): ?>
        ,"sameAs": [
            "https://github.com/<?php echo sanitizeOutput($profile['github_username']); ?>"
            <?php if (!empty($profile['linkedin_url'])): ?>,"<?php echo sanitizeOutput($profile['linkedin_url']); ?>"<?php endif; ?>
        ]
        <?php endif; ?>
    }
    </script>
</head>

<body>

    <!-- Skip Navigation -->
    <a href="#main-content" class="skip-link">Aller au contenu principal</a>

    <!-- ===== NAVIGATION ===== -->
    <nav class="navbar-top" id="navbar" aria-label="Navigation principale">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="#" class="nav-brand">DK<span>.</span></a>
            <ul class="nav-links d-none d-md-flex" role="menubar">
                <li role="none"><a href="#hero" role="menuitem">Accueil</a></li>
                <li role="none"><a href="#about" role="menuitem">À propos</a></li>
                <li role="none"><a href="#services" role="menuitem">Services</a></li>
                <li role="none"><a href="#stack" role="menuitem">Stack</a></li>
                <li role="none"><a href="#projects" role="menuitem">Projets</a></li>
                <li role="none"><a href="#skills" role="menuitem">Compétences</a></li>
                <li role="none"><a href="#experience" role="menuitem">Expérience</a></li>
                <li role="none"><a href="#blog" role="menuitem">Blog</a></li>
                <li role="none"><a href="#contact" role="menuitem">Contact</a></li>
            </ul>
            <div class="nav-actions d-flex align-items-center gap-3">
                <div class="form-check form-switch mb-0">
                    <input type="checkbox" class="form-check-input" id="darkSwitch" aria-label="Activer le mode sombre" />
                    <label class="form-check-label" for="darkSwitch"><i class="fas fa-moon"></i></label>
                </div>
                <button class="nav-toggle d-md-none" id="navToggle" aria-label="Toggle navigation">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
        <!-- Mobile menu -->
        <div class="mobile-menu" id="mobileMenu">
            <a href="#hero">Accueil</a>
            <a href="#about">À propos</a>
            <a href="#services">Services</a>
            <a href="#stack">Stack</a>
            <a href="#projects">Projets</a>
            <a href="#skills">Compétences</a>
            <a href="#experience">Expérience</a>
            <a href="#blog">Blog</a>
            <a href="#contact">Contact</a>
        </div>
    </nav>

    <main id="main-content">

    <!-- ===== HERO SECTION ===== -->
    <section class="hero" id="hero">
        <div class="hero-bg-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
        <div class="container">
            <div class="row align-items-center min-vh-85">
                <div class="col-lg-7 hero-content">
                    <p class="hero-greeting">Bonjour, je suis</p>
                    <h1 class="hero-name"><?php
                        $fullName = sanitizeOutput($profile['name'] ?? 'Donatien KANANE');
                        $parts = explode(' ', $fullName, 2);
                        echo $parts[0];
                        if (isset($parts[1])) echo ' <span>' . $parts[1] . '</span>';
                    ?></h1>
                    <div class="hero-typing-wrapper">
                        <span class="hero-typing-prefix">Je suis </span>
                        <span class="hero-typed" id="typed-text"></span>
                        <span class="hero-cursor">|</span>
                    </div>
                    <p class="hero-description"><?php echo sanitizeOutput($profile['description'] ?? ''); ?></p>
                    <div class="hero-cta">
                        <a href="#contact" class="btn btn-primary-custom"><i class="fas fa-paper-plane"></i> Me Contacter</a>
                        <a href="#projects" class="btn btn-outline-custom"><i class="fas fa-briefcase"></i> Voir mes projets</a>
                        <a href="<?php echo $systemUrl; ?>cv_donatien_kanane.md" class="btn btn-ghost-custom" download><i class="fas fa-download"></i> Télécharger CV</a>
                    </div>


                    
                    <!-- Contact Buttons (badges) -->
                    <div class="hero-contact-badges">
                        <?php if (!empty($profile['linkedin_url'])): ?>
                        <a href="<?php echo sanitizeOutput($profile['linkedin_url']); ?>" target="_blank" rel="noopener noreferrer">
                            <img src="https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white" alt="LinkedIn" loading="lazy" height="30">
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($profile['github_username'])): ?>
                        <a href="https://github.com/<?php echo sanitizeOutput($profile['github_username']); ?>" target="_blank" rel="noopener noreferrer">
                            <img src="https://img.shields.io/badge/GitHub-181717?style=for-the-badge&logo=github&logoColor=white" alt="GitHub" loading="lazy" height="30">
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($profile['email'])): ?>
                        <a href="mailto:<?php echo sanitizeOutput($profile['email']); ?>">
                            <img src="https://img.shields.io/badge/Email-D14836?style=for-the-badge&logo=gmail&logoColor=white" alt="Email" loading="lazy" height="30">
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($profile['twitter_url'])): ?>
                        <a href="<?php echo sanitizeOutput($profile['twitter_url']); ?>" target="_blank" rel="noopener noreferrer">
                            <img src="https://img.shields.io/badge/Twitter-1DA1F2?style=for-the-badge&logo=twitter&logoColor=white" alt="Twitter" loading="lazy" height="30">
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($profile['website'])): ?>
                        <a href="<?php echo sanitizeOutput($profile['website']); ?>" target="_blank" rel="noopener noreferrer">
                            <img src="https://img.shields.io/badge/Website-4285F4?style=for-the-badge&logo=googlechrome&logoColor=white" alt="Website" loading="lazy" height="30">
                        </a>
                        <?php endif; ?>
                    </div>

                    <div class="hero-socials">
                        <?php if (!empty($profile['github_username'])): ?>
                        <a href="https://github.com/<?php echo sanitizeOutput($profile['github_username']); ?>" target="_blank" rel="noopener noreferrer" title="GitHub" aria-label="GitHub"><i class="fa-brands fa-github"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($profile['linkedin_url'])): ?>
                        <a href="<?php echo sanitizeOutput($profile['linkedin_url']); ?>" target="_blank" rel="noopener noreferrer" title="LinkedIn" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($profile['twitter_url'])): ?>
                        <a href="<?php echo sanitizeOutput($profile['twitter_url']); ?>" target="_blank" rel="noopener noreferrer" title="Twitter" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($profile['email'])): ?>
                        <a href="mailto:<?php echo sanitizeOutput($profile['email']); ?>" title="Email"><i class="fas fa-envelope"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-5 hero-image-col d-block">
                    <div class="hero-image-wrapper">
                        <?php echo pictureTag($systemUrl . 'assets/images/profile.png', 'Photo de Donatien KANANE', 'class="hero-image" width="400" height="400"'); ?>
                        <div class="hero-image-ring"></div>
                        <div class="hero-image-dots"></div>
                    </div>
                </div>
            </div>
        </div>
        <a href="#about" class="hero-scroll-indicator">
            <span>Scroll</span>
            <i class="fas fa-chevron-down"></i>
        </a>
    </section>

    <!-- ===== STATS BAR ===== -->
    <section class="stats-bar mt-2">
        <div class="container">
            <div class="row text-center">
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-number" data-count="5">0</div>
                    <div class="stat-label">Années d'expérience</div>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-number" data-count="<?php echo $projectCount; ?>">0</div>
                    <div class="stat-label">Projets réalisés</div>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-number" data-count="<?php echo $skillCount; ?>">0</div>
                    <div class="stat-label">Technologies maîtrisées</div>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-number" data-count="6">0</div>
                    <div class="stat-label">Organisations</div>
                </div>
            </div>
        </div>
    </section>
    <!-- ===== STACK & OUTILS ===== -->
    <section class="section-block stack-section" id="stack">
        <div class="container">
            <div class="section-title text-center">
                <span class="section-subtitle">Technologies</span>
                <h2>Stack & Outils</h2>
                <div class="title-line"></div>
            </div>

            <div class="stack-bento">
                <!-- Développement — large card -->
                <div class="stack-panel stack-panel-lg" data-tilt>
                    <div class="stack-panel-glow"></div>
                    <div class="stack-panel-header">
                        <span class="stack-panel-icon"><i class="fas fa-code"></i></span>
                        <h4>Développement</h4>
                        <span class="stack-panel-count">11</span>
                    </div>
                    <div class="stack-panel-items">
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=php" alt="PHP" width="40" height="40" loading="lazy"><span>PHP</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=laravel" alt="Laravel" width="40" height="40" loading="lazy"><span>Laravel</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=python" alt="Python" width="40" height="40" loading="lazy"><span>Python</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=django" alt="Django" width="40" height="40" loading="lazy"><span>Django</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=js" alt="JavaScript" width="40" height="40" loading="lazy"><span>JavaScript</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=jquery" alt="jQuery" width="40" height="40" loading="lazy"><span>jQuery</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=html" alt="HTML5" width="40" height="40" loading="lazy"><span>HTML5</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=css" alt="CSS3" width="40" height="40" loading="lazy"><span>CSS3</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=kotlin" alt="Kotlin" width="40" height="40" loading="lazy"><span>Kotlin</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=mysql" alt="MySQL" width="40" height="40" loading="lazy"><span>MySQL</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=bootstrap" alt="Bootstrap" width="40" height="40" loading="lazy"><span>Bootstrap</span></div>
                    </div>
                </div>

                <!-- Cloud & DevOps -->
                <div class="stack-panel" data-tilt>
                    <div class="stack-panel-glow"></div>
                    <div class="stack-panel-header">
                        <span class="stack-panel-icon stack-panel-icon--cyan"><i class="fas fa-cloud"></i></span>
                        <h4>Cloud & DevOps</h4>
                        <span class="stack-panel-count">5</span>
                    </div>
                    <div class="stack-panel-items">
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=docker" alt="Docker" width="40" height="40" loading="lazy"><span>Docker</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=linux" alt="Linux" width="40" height="40" loading="lazy"><span>Linux</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=aws" alt="AWS" width="40" height="40" loading="lazy"><span>AWS</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=nginx" alt="Nginx" width="40" height="40" loading="lazy"><span>Nginx</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=githubactions" alt="CI/CD" width="40" height="40" loading="lazy"><span>CI/CD</span></div>
                    </div>
                </div>

                <!-- Outils -->
                <div class="stack-panel" data-tilt>
                    <div class="stack-panel-glow"></div>
                    <div class="stack-panel-header">
                        <span class="stack-panel-icon stack-panel-icon--amber"><i class="fas fa-tools"></i></span>
                        <h4>Outils</h4>
                        <span class="stack-panel-count">5</span>
                    </div>
                    <div class="stack-panel-items">
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=vscode" alt="VS Code" width="40" height="40" loading="lazy"><span>VS Code</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=git" alt="Git" width="40" height="40" loading="lazy"><span>Git</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=github" alt="GitHub" width="40" height="40" loading="lazy"><span>GitHub</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=powershell" alt="PowerShell" width="40" height="40" loading="lazy"><span>PowerShell</span></div>
                        <div class="stack-item"><img src="https://skillicons.dev/icons?i=npm" alt="NPM" width="40" height="40" loading="lazy"><span>NPM</span></div>
                    </div>
                </div>

                <!-- Data & Analyse -->
                <div class="stack-panel" data-tilt>
                    <div class="stack-panel-glow"></div>
                    <div class="stack-panel-header">
                        <span class="stack-panel-icon stack-panel-icon--rose"><i class="fas fa-chart-pie"></i></span>
                        <h4>Data & Analyse</h4>
                        <span class="stack-panel-count">4</span>
                    </div>
                    <div class="stack-panel-items">
                        <div class="stack-item"><img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/powerbi.svg" alt="Power BI" width="32" height="32" loading="lazy"><span>Power BI</span></div>
                        <div class="stack-item"><span style="font-size:1.7em;vertical-align:middle;">SPSS</span></div>
                        <div class="stack-item"><span style="font-size:1.7em;vertical-align:middle;">KOBO Collect</span></div>
                        <div class="stack-item"><img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/microsoftexcel.svg" alt="Excel" width="32" height="32" loading="lazy"><span>Excel</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SERVICES ===== -->
    <section class="section-block services-section" id="services">
        <div class="container">
            <div class="section-title text-center">
                <span class="section-subtitle">Ce que je fais</span>
                <h2>Mes Services</h2>
                <div class="title-line"></div>
            </div>
            <div class="services-carousel-wrapper">
                <button class="carousel-arrow carousel-prev" id="servicesPrev" aria-label="Précédent">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="services-carousel" id="servicesCarousel">
                    <div class="services-track" id="servicesTrack">
                        <div class="service-slide">
                            <div class="service-card">
                                <div class="service-icon"><i class="fas fa-code"></i></div>
                                <h3>Développement Web Full-Stack</h3>
                                <p>Architecture d'applications web structurées et maintenables — PHP/Laravel, Python/Django, JavaScript — avec séparation claire des responsabilités et code documenté.</p>
                            </div>
                        </div>
                        <div class="service-slide">
                            <div class="service-card">
                                <div class="service-icon"><i class="fas fa-database"></i></div>
                                <h3>Bases de Données & Modélisation</h3>
                                <p>Conception de schémas relationnels évolutifs, requêtes optimisées via PDO préparé, migrations versionnées et intégrité référentielle garantie.</p>
                            </div>
                        </div>
                        <div class="service-slide">
                            <div class="service-card">
                                <div class="service-icon"><i class="fas fa-chart-line"></i></div>
                                <h3>Data & Aide à la Décision</h3>
                                <p>Collecte terrain (KOBO Collect), traitement statistique (SPSS) et tableaux de bord interactifs (Power BI) pour piloter des projets à impact social.</p>
                            </div>
                        </div>
                        <div class="service-slide">
                            <div class="service-card">
                                <div class="service-icon"><i class="fas fa-mobile-alt"></i></div>
                                <h3>UI/UX Responsive</h3>
                                <p>Interfaces mobile-first avec Bootstrap 5, progressive enhancement et accessibilité — pensées pour fonctionner sur les connexions et appareils d'Afrique Centrale.</p>
                            </div>
                        </div>
                        <div class="service-slide">
                            <div class="service-card">
                                <div class="service-icon"><i class="fas fa-server"></i></div>
                                <h3>APIs & Architectures Évolutives</h3>
                                <p>Conception d'APIs RESTful sécurisées, intégration de passerelles de paiement (Mobile Money, Stripe), architecture modulaire prête pour la montée en charge.</p>
                            </div>
                        </div>
                        <div class="service-slide">
                            <div class="service-card">
                                <div class="service-icon"><i class="fas fa-heartbeat"></i></div>
                                <h3>Tech for Good & Santé</h3>
                                <p>Solutions numériques pour le secteur humanitaire et la santé publique — marketplace médicale, suivi épidémiologique, outils de gestion pour ONG.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-arrow carousel-next" id="servicesNext" aria-label="Suivant">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="carousel-dots" id="servicesDots"></div>
            </div>
        </div>
    </section>

    <!-- ===== SKILLS ===== -->
    <section class="section-block skills-section" id="skills">
        <!-- SVG gradient definition (hidden) -->
        <svg class="skill-svg-defs" aria-hidden="true">
            <defs>
                <linearGradient id="skillGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#6366f1" />
                    <stop offset="100%" stop-color="#06b6d4" />
                </linearGradient>
            </defs>
        </svg>
        <div class="container">
            <div class="section-title text-center">
                <span class="section-subtitle">Mon expertise</span>
                <h2>Compétences Techniques</h2>
                <div class="title-line"></div>
            </div>

            <?php
            // Group skills by category
            $skillsByCategory = [];
            $categoryIcons = [
                'Front-end' => 'fa-palette',
                'Back-end'  => 'fa-server',
                'Outils'    => 'fa-toolbox',
            ];
            $categoryColors = [
                'Front-end' => '#6366f1',
                'Back-end'  => '#06b6d4',
                'Outils'    => '#f59e0b',
            ];
            foreach ($skills as $sk) {
                $cat = $sk['category'] ?? 'Autres';
                $skillsByCategory[$cat][] = $sk;
            }
            $avgLevel = count($skills) > 0 ? round(array_sum(array_column($skills, 'level')) / count($skills)) : 0;
            ?>

            <!-- Overall stat -->
            <div class="skills-overview">
                <div class="skills-overview-ring">
                    <svg viewBox="0 0 120 120">
                        <circle class="skills-overview-bg" cx="60" cy="60" r="54" />
                        <circle class="skills-overview-fill" cx="60" cy="60" r="54" data-percent="<?php echo $avgLevel; ?>" />
                    </svg>
                    <div class="skills-overview-text">
                        <span class="skills-overview-number"><?php echo $avgLevel; ?></span>
                        <span class="skills-overview-label">score moyen</span>
                    </div>
                </div>
                <div class="skills-overview-info">
                    <h3><?php echo $skillCount; ?> technologies maîtrisées</h3>
                    <p>Réparties en <?php echo count($skillsByCategory); ?> domaines d'expertise avec un score moyen de <strong><?php echo $avgLevel; ?>%</strong></p>
                </div>
            </div>

            <!-- Category filter tabs -->
            <div class="skills-filter">
                <button class="skills-filter-btn active" data-filter="all"><i class="fas fa-th"></i> Toutes</button>
                <?php foreach ($skillsByCategory as $catName => $catSkills): ?>
                <button class="skills-filter-btn" data-filter="<?php echo strtolower(str_replace([' ', '-'], '', $catName)); ?>">
                    <i class="fas <?php echo $categoryIcons[$catName] ?? 'fa-cube'; ?>"></i> <?php echo sanitizeOutput($catName); ?>
                    <span class="skills-filter-count"><?php echo count($catSkills); ?></span>
                </button>
                <?php endforeach; ?>
            </div>

            <!-- Skills grid by category -->
            <?php foreach ($skillsByCategory as $catName => $catSkills): ?>
            <div class="skills-category-group" data-category="<?php echo strtolower(str_replace([' ', '-'], '', $catName)); ?>">
                <div class="skills-category-header">
                    <span class="skills-category-icon" style="--cat-color: <?php echo $categoryColors[$catName] ?? '#6366f1'; ?>">
                        <i class="fas <?php echo $categoryIcons[$catName] ?? 'fa-cube'; ?>"></i>
                    </span>
                    <h4><?php echo sanitizeOutput($catName); ?></h4>
                    <span class="skills-category-line"></span>
                </div>
                <div class="row g-3">
                    <?php foreach ($catSkills as $skill): ?>
                    <div class="col-sm-6 col-lg-4">
                        <div class="skill-card" style="--skill-color: <?php echo $categoryColors[$catName] ?? '#6366f1'; ?>">
                            <div class="skill-ring-wrapper">
                                <svg class="skill-ring-svg" viewBox="0 0 56 56">
                                    <circle class="skill-ring-bg" cx="28" cy="28" r="26" />
                                    <circle class="skill-ring-fill" cx="28" cy="28" r="26" data-percent="<?php echo $skill['level']; ?>" />
                                </svg>
                                <span class="skill-ring-percent"><?php echo $skill['level']; ?></span>
                            </div>
                            <div class="skill-info">
                                <span class="skill-name"><?php echo sanitizeOutput($skill['name']); ?></span>
                                <div class="skill-bar-track">
                                    <div class="skill-bar-fill" data-width="<?php echo $skill['level']; ?>"></div>
                                </div>
                                <span class="skill-level-label"><?php
                                    $lvl = $skill['level'];
                                    if ($lvl >= 90) echo 'Expert';
                                    elseif ($lvl >= 80) echo 'Avancé';
                                    elseif ($lvl >= 60) echo 'Intermédiaire';
                                    else echo 'Débutant';
                                ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ===== PROJECTS ===== -->
    <section class="section-block projects-section" id="projects">
        <div class="container">
            <div class="section-title text-center">
                <span class="section-subtitle">Mon travail</span>
                <h2>Projets Récents</h2>
                <div class="title-line"></div>
            </div>

            <?php
            $featured = null;
            $otherProjects = [];
            foreach ($projects as $p) {
                if ($p['is_featured'] && !$featured) {
                    $featured = $p;
                } else {
                    $otherProjects[] = $p;
                }
            }
            ?>

            <?php if ($featured): ?>
            <div class="featured-project mb-5">
                <div class="row align-items-center g-4">
                    <?php if (!empty($featured['image_url'])): ?>
                    <div class="col-lg-6">
                        <div class="featured-project-image">
                            <?php echo pictureTag(sanitizeOutput($featured['image_url']), sanitizeOutput($featured['title']), 'class="img-fluid rounded-3" loading="lazy" width="600" height="400"'); ?>
                            <div class="featured-badge"><i class="fas fa-star"></i> Projet phare</div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="col-lg-6">
                        <div class="featured-project-content">
                            <h3><?php echo sanitizeOutput($featured['title']); ?></h3>
                            <p><?php echo nl2br(sanitizeOutput($featured['description'])); ?></p>
                            <div class="card-engagement">
                                <?php
                                    $fUrl = $featured['project_url'];
                                    $fComments = $projectStats[$fUrl]['comments'] ?? 0;
                                    $fLikes = $projectStats[$fUrl]['likes'] ?? 0;
                                ?>
                                <span class="engagement-badge" title="Commentaires"><i class="far fa-comment-dots"></i> <?php echo $fComments; ?></span>
                                <span class="engagement-badge" title="Likes"><i class="far fa-heart"></i> <?php echo $fLikes; ?></span>
                            </div>
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="<?php echo sanitizeOutput($featured['project_url']); ?>" class="btn btn-primary-custom" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt"></i> Voir le projet</a>
                                <a href="projet/<?php echo sanitizeOutput($featured['slug'] ?? slugify($featured['title'])); ?>" class="btn btn-outline-custom"><i class="fas fa-info-circle"></i> Détails</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="row g-4">
                <?php foreach ($otherProjects as $proj): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="project-card">
                        <?php if (!empty($proj['image_url'])): ?>
                        <div class="project-card-image">
                            <a href="projet/<?php echo sanitizeOutput($proj['slug'] ?? slugify($proj['title'])); ?>">
                                <?php echo pictureTag(sanitizeOutput($proj['image_url']), sanitizeOutput($proj['title']), 'loading="lazy" width="400" height="260"'); ?>
                            </a>
                        </div>
                        <?php else: ?>
                        <div class="project-card-image project-card-placeholder">
                            <i class="fas fa-code fa-3x"></i>
                        </div>
                        <?php endif; ?>
                        <div class="project-card-body">
                            <h4><a href="projet/<?php echo sanitizeOutput($proj['slug'] ?? slugify($proj['title'])); ?>"><?php echo sanitizeOutput($proj['title']); ?></a></h4>
                            <p><?php echo mb_strimwidth(sanitizeOutput($proj['description']), 0, 120, '...'); ?></p>
                            <div class="card-engagement">
                                <?php
                                    $pUrl = $proj['project_url'];
                                    $pComments = $projectStats[$pUrl]['comments'] ?? 0;
                                    $pLikes = $projectStats[$pUrl]['likes'] ?? 0;
                                ?>
                                <span class="engagement-badge" title="Commentaires"><i class="far fa-comment-dots"></i> <?php echo $pComments; ?></span>
                                <span class="engagement-badge" title="Likes"><i class="far fa-heart"></i> <?php echo $pLikes; ?></span>
                            </div>
                            <a href="projet/<?php echo sanitizeOutput($proj['slug'] ?? slugify($proj['title'])); ?>" class="project-link"><i class="fas fa-arrow-right"></i> En savoir plus</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ===== EXPERIENCE ===== -->
    <section class="section-block experience-section" id="experience">
        <div class="container">
            <div class="section-title text-center">
                <span class="section-subtitle">Mon parcours</span>
                <h2>Expérience Professionnelle</h2>
                <div class="title-line"></div>
            </div>
            <div class="timeline">
                <?php foreach ($experience as $i => $exp): ?>
                <div class="timeline-item <?php echo $i % 2 === 0 ? 'left' : 'right'; ?>">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">
                            <?php echo formatDate($exp['start_date']); ?> — <?php echo $exp['end_date'] ? formatDate($exp['end_date']) : 'Présent'; ?>
                        </div>
                        <h3><?php echo sanitizeOutput($exp['title']); ?></h3>
                        <h4 class="timeline-company"><i class="fas fa-building"></i> <?php echo sanitizeOutput($exp['company']); ?></h4>
                        <p><?php echo nl2br(sanitizeOutput($exp['description'])); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ===== EDUCATION ===== -->
    <section class="section-block education-section" id="education">
        <div class="container">
            <div class="section-title text-center">
                <span class="section-subtitle">Ma formation</span>
                <h2>Formation Académique</h2>
                <div class="title-line"></div>
            </div>
            <div class="row justify-content-center g-4">
                <div class="col-md-6">
                    <div class="education-card">
                        <div class="education-icon"><i class="fas fa-university"></i></div>
                        <div class="education-info">
                            <h4>Licence en Sciences Biomédicales</h4>
                            <p class="education-school">Université de Goma — Formation alliant rigueur scientifique et approche terrain, fondement de mon expertise data & santé</p>
                            <span class="education-year">2021 — 2024</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="education-card">
                        <div class="education-icon"><i class="fas fa-school"></i></div>
                        <div class="education-info">
                            <h4>Diplôme d'État en Scientifique</h4>
                            <p class="education-school">Institut Isidore Bakanja — Bases solides en sciences exactes et méthodologie analytique</p>
                            <span class="education-year">2016 — 2021</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== BLOG / ACTUALITÉS ===== -->
    <section class="section-block blog-section" id="blog">
        <div class="container">
            <div class="section-title text-center">
                <span class="section-subtitle">Mes publications</span>
                <h2>Blog & Actualités</h2>
                <div class="title-line"></div>
            </div>
            <?php if (!empty($blogPosts)):
                $featuredPost = $blogPosts[0];
                $otherPosts = array_slice($blogPosts, 1);
            ?>

            <!-- Article principal -->
            <div class="blog-featured">
                <div class="row g-0 align-items-stretch">
                    <div class="col-lg-6">
                        <div class="blog-featured-image">
                            <?php if (!empty($featuredPost['image_url'])): ?>
                                <?php echo pictureTag(sanitizeOutput($featuredPost['image_url']), sanitizeOutput($featuredPost['title']), 'loading="lazy" width="600" height="400"'); ?>
                            <?php else: ?>
                                <div class="blog-card-placeholder">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                            <?php endif; ?>
                            <span class="blog-card-category"><?php echo sanitizeOutput($featuredPost['category']); ?></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="blog-featured-body">
                            <span class="blog-featured-label"><i class="fas fa-fire"></i> À la une</span>
                            <div class="blog-card-date">
                                <i class="far fa-calendar-alt"></i>
                                <?php echo date('d M Y', strtotime($featuredPost['created_at'])); ?>
                            </div>
                            <h3><a href="blog/<?php echo sanitizeOutput($featuredPost['slug']); ?>"><?php echo sanitizeOutput($featuredPost['title']); ?></a></h3>
                            <p><?php echo sanitizeOutput($featuredPost['excerpt']); ?></p>
                            <div class="card-engagement">
                                <?php
                                    $bComments = $blogStats[$featuredPost['slug']]['comments'] ?? 0;
                                    $bLikes = $blogStats[$featuredPost['slug']]['likes'] ?? 0;
                                ?>
                                <span class="engagement-badge" title="Commentaires"><i class="far fa-comment-dots"></i> <?php echo $bComments; ?></span>
                                <span class="engagement-badge" title="Likes"><i class="far fa-heart"></i> <?php echo $bLikes; ?></span>
                            </div>
                            <a href="blog/<?php echo sanitizeOutput($featuredPost['slug']); ?>" class="btn btn-primary-custom btn-sm">
                                <i class="fas fa-arrow-right"></i> Lire l'article
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($otherPosts)): ?>
            <!-- Fil d'actualités connecté -->
            <div class="blog-feed">
                <div class="blog-feed-line"></div>
                <?php foreach ($otherPosts as $i => $post): ?>
                <div class="blog-feed-item <?php echo $i % 2 === 0 ? '' : 'blog-feed-item-alt'; ?>">
                    <div class="blog-feed-dot">
                        <i class="fas fa-circle"></i>
                    </div>
                    <div class="blog-feed-card">
                        <div class="blog-feed-card-inner">
                            <div class="blog-feed-meta">
                                <span class="blog-feed-category"><?php echo sanitizeOutput($post['category']); ?></span>
                                <span class="blog-feed-date"><i class="far fa-clock"></i> <?php echo date('d M Y', strtotime($post['created_at'])); ?></span>
                            </div>
                            <h4><a href="blog/<?php echo sanitizeOutput($post['slug']); ?>"><?php echo sanitizeOutput($post['title']); ?></a></h4>
                            <p><?php echo mb_strimwidth(sanitizeOutput($post['excerpt']), 0, 150, '...'); ?></p>
                            <div class="card-engagement">
                                <?php
                                    $bC = $blogStats[$post['slug']]['comments'] ?? 0;
                                    $bL = $blogStats[$post['slug']]['likes'] ?? 0;
                                ?>
                                <span class="engagement-badge" title="Commentaires"><i class="far fa-comment-dots"></i> <?php echo $bC; ?></span>
                                <span class="engagement-badge" title="Likes"><i class="far fa-heart"></i> <?php echo $bL; ?></span>
                            </div>
                            <a href="blog/<?php echo sanitizeOutput($post['slug']); ?>" class="blog-read-more">
                                Lire la suite <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php else: ?>
            <div class="text-center">
                <p class="text-muted">Aucun article pour le moment. Revenez bientôt !</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- ===== GITHUB ===== -->
    <?php $ghUser = sanitizeOutput($profile['github_username'] ?? ''); ?>
    <?php if (!empty($ghUser)): ?>
    <section class="section-block github-section" id="github">
        <div class="container">
            <div class="section-title text-center">
                <span class="section-subtitle">Open Source</span>
                <h2>Activité GitHub</h2>
                <div class="title-line"></div>
                <p class="section-description">Contributions, statistiques et activité récente sur <a href="https://github.com/<?php echo $ghUser; ?>" target="_blank" rel="noopener noreferrer">github.com/<?php echo $ghUser; ?></a></p>
            </div>

            <!-- Stats Cards Row -->
            <div class="github-stats-row">
                <div class="github-stat-card">
                    <img src="https://github-readme-stats.vercel.app/api?username=<?php echo $ghUser; ?>&show_icons=true&theme=transparent&hide_border=true&include_all_commits=true&count_private=true&title_color=6366f1&icon_color=06b6d4&text_color=64748b" alt="Statistiques GitHub de <?php echo $ghUser; ?>" loading="lazy" width="495" height="195" onerror="this.parentElement.innerHTML='<div class=\'github-stat-fallback\'><i class=\'fas fa-chart-bar fa-3x mb-3\'></i><p>Statistiques temporairement indisponibles</p><a href=\'https://github.com/<?php echo $ghUser; ?>\' target=\'_blank\' rel=\'noopener noreferrer\'>Voir sur GitHub</a></div>'">
                </div>
                <div class="github-stat-card">
                    <img src="https://github-readme-stats.vercel.app/api/top-langs/?username=<?php echo $ghUser; ?>&layout=compact&theme=transparent&hide_border=true&title_color=6366f1&text_color=64748b&langs_count=8" alt="Langages les plus utilisés" loading="lazy" width="350" height="195" onerror="this.parentElement.innerHTML='<div class=\'github-stat-fallback\'><i class=\'fas fa-code fa-3x mb-3\'></i><p>Langages temporairement indisponibles</p><a href=\'https://github.com/<?php echo $ghUser; ?>\' target=\'_blank\' rel=\'noopener noreferrer\'>Voir sur GitHub</a></div>'">
                </div>
            </div>

            <!-- Streak Stats -->
            <div class="github-streak-card">
                <img src="https://github-readme-streak-stats.herokuapp.com/?user=<?php echo $ghUser; ?>&theme=transparent&hide_border=true&ring=6366f1&fire=06b6d4&currStreakLabel=6366f1&sideLabels=64748b&dates=94a3b8" alt="Série de contributions GitHub" loading="lazy" width="800" height="220" onerror="this.parentElement.innerHTML='<div class=\'github-stat-fallback\'><i class=\'fas fa-fire fa-3x mb-3\'></i><p>Streak stats temporairement indisponibles</p></div>'">
            </div>

            <!-- Activity Graph -->
            <div class="github-graph-card">
                <img src="https://github-readme-activity-graph.vercel.app/graph?username=<?php echo $ghUser; ?>&theme=react-dark&hide_border=true&area=true&bg_color=00000000&color=6366f1&line=06b6d4&point=6366f1&area_color=6366f1" alt="Graphique d'activité GitHub" loading="lazy" width="900" height="300" onerror="this.parentElement.innerHTML='<div class=\'github-stat-fallback\'><i class=\'fas fa-chart-line fa-3x mb-3\'></i><p>Graphique d\'activité temporairement indisponible</p></div>'">
            </div>

            <!-- Calendar + Activity Feed -->
            <div class="row g-4 mt-2">
                <div class="col-lg-7">
                    <div class="github-card">
                        <h5><i class="fas fa-calendar-alt"></i> Calendrier de contributions</h5>
                        <div id="github-graph" class="github-graph"></div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="github-card">
                        <h5><i class="fas fa-stream"></i> Activité récente</h5>
                        <div id="ghfeed" class="ghfeed"></div>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center mt-4">
                <a href="https://github.com/<?php echo $ghUser; ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-custom">
                    <i class="fa-brands fa-github"></i> Voir mon profil GitHub
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ===== CONTACT ===== -->
    <section class="section-block contact-section" id="contact">
        <div class="container">
            <div class="section-title text-center">
                <span class="section-subtitle">Collaborons ensemble</span>
                <h2>Me Contacter</h2>
                <div class="title-line"></div>
                <p class="section-description">Disponible pour des missions freelance, des collaborations à distance ou des projets à impact social. N'hésitez pas à me contacter.</p>
            </div>
            <div class="row justify-content-center g-4">
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-card-icon"><i class="fas fa-envelope"></i></div>
                        <h4>Email</h4>
                        <a href="mailto:<?php echo sanitizeOutput($profile['email'] ?? 'donatienkanane@gmail.com'); ?>"><?php echo sanitizeOutput($profile['email'] ?? 'donatienkanane@gmail.com'); ?></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-card-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <h4>Localisation</h4>
                        <p><?php echo sanitizeOutput($profile['location'] ?? 'Goma, RDC'); ?></p>
                        <small class="text-muted">Disponible en remote</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-card-icon"><i class="fas fa-globe"></i></div>
                        <h4>Web</h4>
                        <a href="<?php echo sanitizeOutput($profile['website'] ?? '#'); ?>" target="_blank" rel="noopener noreferrer"><?php echo sanitizeOutput($profile['website'] ?? ''); ?></a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="mailto:<?php echo sanitizeOutput($profile['email'] ?? 'donatienkanane@gmail.com'); ?>" class="btn btn-primary-custom btn-lg"><i class="fas fa-paper-plane"></i> Envoyer un message</a>
            </div>
        </div>
    </section>

    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">DK<span>.</span></div>
                <div class="footer-socials">
                    <?php if (!empty($profile['github_username'])): ?>
                    <a href="https://github.com/<?php echo sanitizeOutput($profile['github_username']); ?>" target="_blank" rel="noopener noreferrer" aria-label="GitHub"><i class="fa-brands fa-github"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($profile['linkedin_url'])): ?>
                    <a href="<?php echo sanitizeOutput($profile['linkedin_url']); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($profile['twitter_url'])): ?>
                    <a href="<?php echo sanitizeOutput($profile['twitter_url']); ?>" target="_blank" rel="noopener noreferrer" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                    <?php endif; ?>
                </div>
                <p class="footer-copy">&copy; <?php echo date('Y'); ?> Donatien KANANE. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Back to top -->
    <button class="back-to-top" id="backToTop" aria-label="Retour en haut"><i class="fas fa-arrow-up"></i></button>

    <!-- Javascript -->
    <script src="<?php echo $systemUrl; ?>assets/plugins/popper.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/vanilla-rss/dist/rss.global.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/dark-mode-switch/dark-mode-switch.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/github-calendar/dist/github-calendar.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/mustache.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/github-activity/src/github-activity.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/js/main.min.js?v=<?php echo time(); ?>"></script>

    <?php if (!empty($profile['github_username'])): ?>
    <script>
    (function() {
        var username = "<?php echo sanitizeOutput($profile['github_username']); ?>";
        var graphEl = document.getElementById('github-graph');
        var feedEl = document.getElementById('ghfeed');

        // Loading indicators
        if (graphEl) graphEl.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2 text-muted">Chargement du calendrier...</p></div>';
        if (feedEl) feedEl.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2 text-muted">Chargement des activités...</p></div>';

        // GitHub Calendar
        if (graphEl && typeof GitHubCalendar === 'function') {
            try {
                var calPromise = GitHubCalendar("#github-graph", username, { responsive: true, tooltips: true });
                if (calPromise && typeof calPromise.catch === 'function') {
                    calPromise.catch(function() {
                        graphEl.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-calendar-times fa-2x mb-2"></i><p>Calendrier temporairement indisponible</p></div>';
                    });
                }
            } catch(e) {
                graphEl.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-calendar-times fa-2x mb-2"></i><p>Calendrier temporairement indisponible</p></div>';
            }
        }

        // GitHub Activity Feed
        if (feedEl && typeof GitHubActivity === 'object' && typeof Mustache === 'object') {
            try {
                GitHubActivity.feed({ username: username, selector: "#ghfeed", limit: 10 });
            } catch(e) {
                feedEl.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-exclamation-circle fa-2x mb-2"></i><p>Activités temporairement indisponibles</p></div>';
            }
            // Fallback if feed stays empty after 10s
            setTimeout(function() {
                if (feedEl && feedEl.innerHTML.indexOf('gha-feed') === -1 && feedEl.innerHTML.indexOf('fa-spinner') !== -1) {
                    feedEl.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-clock fa-2x mb-2"></i><p>L\'API GitHub est lente. <a href="https://github.com/' + username + '" target="_blank" rel="noopener noreferrer">Voir sur GitHub <i class="fas fa-external-link-alt"></i></a></p></div>';
                }
            }, 10000);
        } else {
            if (feedEl) feedEl.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-code fa-2x mb-2"></i><p>Visitez mon profil <a href="https://github.com/' + username + '" target="_blank" rel="noopener noreferrer">GitHub <i class="fas fa-external-link-alt"></i></a></p></div>';
        }
    })();
    </script>
    <?php endif; ?>
</body>
</html>
