<?php
session_start();
require_once 'config.php';
require_once 'includes/Database.php';
require_once 'includes/functions.php';

$database = new Database();
$db = $database->getConnection();

$profile = getProfileData($db);
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$post = getBlogPost($db, $slug);

if (!$post) {
    header('Location: index.php#blog');
    exit;
}

$systemUrl = getSystemUrl();
$locale = getDefaultLocale();
?>
<!DOCTYPE html>
<html lang="<?php echo substr($locale, 0, 2); ?>">
<head>
    <title><?php echo sanitizeOutput($post['title']); ?> — Blog | Donatien KANANE</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo sanitizeOutput($post['excerpt']); ?>">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script defer src="<?php echo $systemUrl; ?>assets/fontawesome/js/all.js"></script>
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/plugins/bootstrap/css/bootstrap.min.css">
    <link id="theme-style" rel="stylesheet" href="<?php echo $systemUrl; ?>assets/css/styles.css">
</head>
<body>

    <!-- ===== NAVIGATION ===== -->
    <nav class="navbar-top scrolled" id="navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="index.php" class="nav-brand">DK<span>.</span></a>
            <div class="nav-links d-none d-md-flex">
                <a href="index.php">Accueil</a>
                <a href="index.php#projects">Projets</a>
                <a href="index.php#blog">Blog</a>
                <a href="index.php#contact">Contact</a>
            </div>
            <div class="nav-actions d-flex align-items-center gap-3">
                <div class="form-check form-switch mb-0">
                    <input type="checkbox" class="form-check-input" id="darkSwitch" />
                    <label class="form-check-label" for="darkSwitch"><i class="fas fa-moon"></i></label>
                </div>
            </div>
        </div>
    </nav>

    <!-- ===== BLOG HERO ===== -->
    <section class="project-hero">
        <div class="project-hero-bg">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
        </div>
        <div class="container">
            <div class="project-hero-content">
                <a href="index.php#blog" class="project-hero-back">
                    <i class="fas fa-arrow-left"></i> Retour au blog
                </a>
                <h1><?php echo sanitizeOutput($post['title']); ?></h1>
                <div class="project-hero-meta">
                    <span class="project-meta-badge">
                        <i class="fas fa-tag"></i> <?php echo sanitizeOutput($post['category']); ?>
                    </span>
                    <span class="project-meta-badge">
                        <i class="far fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($post['created_at'])); ?>
                    </span>
                    <span class="project-meta-badge">
                        <i class="far fa-clock"></i> <?php echo max(1, round(str_word_count($post['content']) / 200)); ?> min de lecture
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== BLOG CONTENT ===== -->
    <div class="project-main-wrapper">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="blog-detail-card">
                        <?php if (!empty($post['image_url'])): ?>
                        <div class="blog-detail-image">
                            <img src="<?php echo sanitizeOutput($post['image_url']); ?>" alt="<?php echo sanitizeOutput($post['title']); ?>" />
                        </div>
                        <?php else: ?>
                        <div class="blog-detail-image">
                            <div class="blog-detail-placeholder">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="blog-detail-body">
                            <div class="blog-detail-content">
                                <?php echo nl2br(sanitizeOutput($post['content'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Author card -->
                    <div class="project-sidebar-card">
                        <h4><i class="fas fa-user-pen"></i> Auteur</h4>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="comment-avatar" style="width:48px;height:48px;font-size:20px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <strong style="color:var(--text-primary);"><?php echo sanitizeOutput($profile['name'] ?? 'Donatien KANANE'); ?></strong>
                                <div style="font-size:13px;color:var(--text-secondary);"><?php echo sanitizeOutput($profile['title'] ?? 'Développeur Full-Stack'); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Info card -->
                    <div class="project-sidebar-card">
                        <h4><i class="fas fa-info-circle"></i> Informations</h4>
                        <div class="sidebar-info-list">
                            <div class="sidebar-info-item">
                                <span class="sidebar-info-label"><i class="fas fa-tag"></i> Catégorie</span>
                                <span class="sidebar-info-value"><?php echo sanitizeOutput($post['category']); ?></span>
                            </div>
                            <div class="sidebar-info-item">
                                <span class="sidebar-info-label"><i class="far fa-calendar-alt"></i> Publié le</span>
                                <span class="sidebar-info-value"><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></span>
                            </div>
                            <div class="sidebar-info-item">
                                <span class="sidebar-info-label"><i class="far fa-clock"></i> Temps de lecture</span>
                                <span class="sidebar-info-value"><?php echo max(1, round(str_word_count($post['content']) / 200)); ?> min</span>
                            </div>
                        </div>
                    </div>

                    <!-- Share card -->
                    <div class="project-sidebar-card">
                        <h4><i class="fas fa-share-nodes"></i> Partager</h4>
                        <div class="sidebar-share-links">
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($systemUrl . 'blog.php?slug=' . $post['slug']); ?>" target="_blank" class="share-btn linkedin" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($post['title']); ?>&url=<?php echo urlencode($systemUrl . 'blog.php?slug=' . $post['slug']); ?>" target="_blank" class="share-btn twitter" title="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                            <a href="mailto:?subject=<?php echo rawurlencode($post['title']); ?>&body=<?php echo rawurlencode($post['excerpt'] . "\n\n" . $systemUrl . 'blog.php?slug=' . $post['slug']); ?>" class="share-btn email" title="Email"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                </div>
                <p class="footer-copy">&copy; <?php echo date('Y'); ?> Donatien KANANE. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <button class="back-to-top" id="backToTop" aria-label="Retour en haut"><i class="fas fa-arrow-up"></i></button>

    <script src="<?php echo $systemUrl; ?>assets/plugins/popper.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/dark-mode-switch/dark-mode-switch.min.js"></script>
</body>
</html>
