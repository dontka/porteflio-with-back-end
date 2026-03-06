<?php
require_once 'config.php';
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
?>
<!DOCTYPE html>
<html lang="<?php echo substr($locale, 0, 2); ?>">
<head>
    <title><?php echo $debugMode ? '[DEBUG] ' : ''; ?>Donatien KANANE — Développeur Full-Stack & Data Analyst</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo sanitizeOutput($profile['description'] ?? 'Portfolio de Donatien KANANE - Développeur Web Full-Stack & Analyste de Données'); ?>">
    <meta name="author" content="<?php echo sanitizeOutput($profile['name'] ?? 'Donatien KANANE'); ?>">
    <link rel="shortcut icon" href="favicon.ico">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <script defer src="<?php echo $systemUrl; ?>assets/fontawesome/js/all.js"></script>
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/plugins/github-calendar/dist/github-calendar-responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/octicons/2.0.2/octicons.min.css">
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/plugins/github-activity/src/github-activity.css">
    <link id="theme-style" rel="stylesheet" href="<?php echo $systemUrl; ?>assets/css/styles.css">
</head>

<body>

    <!-- ===== NAVIGATION ===== -->
    <nav class="navbar-top" id="navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="#" class="nav-brand">DK<span>.</span></a>
            <div class="nav-links d-none d-md-flex">
                <a href="#hero">Accueil</a>
                <a href="#about">À propos</a>
                <a href="#services">Services</a>
                <a href="#projects">Projets</a>
                <a href="#skills">Compétences</a>
                <a href="#experience">Expérience</a>
                <a href="#blog">Blog</a>
                <a href="#contact">Contact</a>
            </div>
            <div class="nav-actions d-flex align-items-center gap-3">
                <div class="form-check form-switch mb-0">
                    <input type="checkbox" class="form-check-input" id="darkSwitch" />
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
            <a href="#projects">Projets</a>
            <a href="#skills">Compétences</a>
            <a href="#experience">Expérience</a>
            <a href="#blog">Blog</a>
            <a href="#contact">Contact</a>
        </div>
    </nav>

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


                    
                    <div class="hero-socials">
                        <?php if (!empty($profile['github_username'])): ?>
                        <a href="https://github.com/<?php echo sanitizeOutput($profile['github_username']); ?>" target="_blank" title="GitHub"><i class="fa-brands fa-github"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($profile['linkedin_url'])): ?>
                        <a href="<?php echo sanitizeOutput($profile['linkedin_url']); ?>" target="_blank" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($profile['twitter_url'])): ?>
                        <a href="<?php echo sanitizeOutput($profile['twitter_url']); ?>" target="_blank" title="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($profile['email'])): ?>
                        <a href="mailto:<?php echo sanitizeOutput($profile['email']); ?>" title="Email"><i class="fas fa-envelope"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-5 hero-image-col d-none d-lg-block">
                    <div class="hero-image-wrapper">
                        <img class="hero-image" src="<?php echo $systemUrl; ?>assets/images/profile.png" alt="Donatien KANANE" />
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
                    <div class="stat-number" data-count="3">0</div>
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
            <div class="row g-4">
                <?php foreach ($skills as $skill): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="skill-card">
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
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
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
                            <img class="img-fluid rounded-3" src="<?php echo sanitizeOutput($featured['image_url']); ?>" alt="<?php echo sanitizeOutput($featured['title']); ?>" />
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
                                <a href="<?php echo sanitizeOutput($featured['project_url']); ?>" class="btn btn-primary-custom" target="_blank"><i class="fas fa-external-link-alt"></i> Voir le projet</a>
                                <a href="project.php?url=<?php echo urlencode($featured['project_url']); ?>" class="btn btn-outline-custom"><i class="fas fa-info-circle"></i> Détails</a>
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
                            <a href="project.php?url=<?php echo urlencode($proj['project_url']); ?>">
                                <img src="<?php echo sanitizeOutput($proj['image_url']); ?>" alt="<?php echo sanitizeOutput($proj['title']); ?>" />
                            </a>
                        </div>
                        <?php else: ?>
                        <div class="project-card-image project-card-placeholder">
                            <i class="fas fa-code fa-3x"></i>
                        </div>
                        <?php endif; ?>
                        <div class="project-card-body">
                            <h4><a href="project.php?url=<?php echo urlencode($proj['project_url']); ?>"><?php echo sanitizeOutput($proj['title']); ?></a></h4>
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
                            <a href="project.php?url=<?php echo urlencode($proj['project_url']); ?>" class="project-link"><i class="fas fa-arrow-right"></i> En savoir plus</a>
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
    <section class="section-block education-section">
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
                                <img src="<?php echo sanitizeOutput($featuredPost['image_url']); ?>" alt="<?php echo sanitizeOutput($featuredPost['title']); ?>" />
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
                            <h3><a href="blog.php?slug=<?php echo urlencode($featuredPost['slug']); ?>"><?php echo sanitizeOutput($featuredPost['title']); ?></a></h3>
                            <p><?php echo sanitizeOutput($featuredPost['excerpt']); ?></p>
                            <div class="card-engagement">
                                <?php
                                    $bComments = $blogStats[$featuredPost['slug']]['comments'] ?? 0;
                                    $bLikes = $blogStats[$featuredPost['slug']]['likes'] ?? 0;
                                ?>
                                <span class="engagement-badge" title="Commentaires"><i class="far fa-comment-dots"></i> <?php echo $bComments; ?></span>
                                <span class="engagement-badge" title="Likes"><i class="far fa-heart"></i> <?php echo $bLikes; ?></span>
                            </div>
                            <a href="blog.php?slug=<?php echo urlencode($featuredPost['slug']); ?>" class="btn btn-primary-custom btn-sm">
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
                            <h4><a href="blog.php?slug=<?php echo urlencode($post['slug']); ?>"><?php echo sanitizeOutput($post['title']); ?></a></h4>
                            <p><?php echo mb_strimwidth(sanitizeOutput($post['excerpt']), 0, 150, '...'); ?></p>
                            <div class="card-engagement">
                                <?php
                                    $bC = $blogStats[$post['slug']]['comments'] ?? 0;
                                    $bL = $blogStats[$post['slug']]['likes'] ?? 0;
                                ?>
                                <span class="engagement-badge" title="Commentaires"><i class="far fa-comment-dots"></i> <?php echo $bC; ?></span>
                                <span class="engagement-badge" title="Likes"><i class="far fa-heart"></i> <?php echo $bL; ?></span>
                            </div>
                            <a href="blog.php?slug=<?php echo urlencode($post['slug']); ?>" class="blog-read-more">
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
    <section class="section-block github-section" id="github">
        <div class="container">
            <div class="section-title text-center">
                <span class="section-subtitle">Open Source</span>
                <h2>Activité GitHub</h2>
                <div class="title-line"></div>
            </div>
            <div class="row g-4">
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
        </div>
    </section>

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
                        <a href="<?php echo sanitizeOutput($profile['website'] ?? '#'); ?>" target="_blank"><?php echo sanitizeOutput($profile['website'] ?? ''); ?></a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="mailto:<?php echo sanitizeOutput($profile['email'] ?? 'donatienkanane@gmail.com'); ?>" class="btn btn-primary-custom btn-lg"><i class="fas fa-paper-plane"></i> Envoyer un message</a>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">DK<span>.</span></div>
                <div class="footer-socials">
                    <?php if (!empty($profile['github_username'])): ?>
                    <a href="https://github.com/<?php echo sanitizeOutput($profile['github_username']); ?>" target="_blank"><i class="fa-brands fa-github"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($profile['linkedin_url'])): ?>
                    <a href="<?php echo sanitizeOutput($profile['linkedin_url']); ?>" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($profile['twitter_url'])): ?>
                    <a href="<?php echo sanitizeOutput($profile['twitter_url']); ?>" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                    <?php endif; ?>
                </div>
                <p class="footer-copy">&copy; <?php echo date('Y'); ?> Donatien KANANE. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Back to top -->
    <button class="back-to-top" id="backToTop" aria-label="Retour en haut"><i class="fas fa-arrow-up"></i></button>

    <!-- Javascript -->
    <script type="text/javascript" src="<?php echo $systemUrl; ?>assets/plugins/popper.min.js"></script>
    <script type="text/javascript" src="<?php echo $systemUrl; ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $systemUrl; ?>assets/plugins/vanilla-rss/dist/rss.global.min.js"></script>
    <script type="text/javascript" src="<?php echo $systemUrl; ?>assets/plugins/dark-mode-switch/dark-mode-switch.min.js"></script>
    <script type="text/javascript" src="<?php echo $systemUrl; ?>assets/plugins/github-calendar/dist/github-calendar.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/mustache.js/0.7.2/mustache.min.js"></script>
    <script type="text/javascript" src="<?php echo $systemUrl; ?>assets/plugins/github-activity/src/github-activity.js"></script>
    <script type="text/javascript" src="<?php echo $systemUrl; ?>assets/js/main.js"></script>

    <?php if (!empty($profile['github_username'])): ?>
    <script>
        GitHubCalendar("#github-graph", "<?php echo sanitizeOutput($profile['github_username']); ?>", { responsive: true, tooltips: true });
        GitHubActivity.feed({ username: "<?php echo sanitizeOutput($profile['github_username']); ?>", selector: "#ghfeed", limit: 10 });
    </script>
    <?php endif; ?>
</body>
</html>
