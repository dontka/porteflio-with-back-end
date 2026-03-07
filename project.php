<?php
require_once 'config.php';
session_start();
require_once 'includes/Database.php';
require_once 'includes/functions.php';


// Initialisation de la connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Récupération des données
$profile = getProfileData($db);

$slug = '';
if (isset($_GET['url'])) {
    $slug = $_GET['url'];
} else if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];
} else if (!empty($_SERVER['REQUEST_URI'])) {
    // SEO: récupère le slug depuis l'URL /projet/slug
    $parts = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
    $slugIdx = array_search('projet', $parts);
    if ($slugIdx !== false && isset($parts[$slugIdx+1])) {
        $slug = $parts[$slugIdx+1];
    }
}
$project = getProjectDetails($db, $slug);

// Si le projet n'existe pas, rediriger vers la page d'accueil
if (!$project) {
    header('Location: index.php');
    exit;
}

// Vérification du mode debug
$debugMode = isDebugMode();
$systemUrl = getSystemUrl();
$locale = getDefaultLocale();
?>
<?php
$projectTitle = sanitizeOutput($project['title']) . ' — Donatien KANANE';
$projectDesc = sanitizeOutput($project['description']);
$projectUrl = $systemUrl . 'projet/' . sanitizeOutput($project['slug']);
$projectImage = !empty($project['image_url']) ? $systemUrl . sanitizeOutput($project['image_url']) : $systemUrl . 'assets/images/profile.png';
?>
<!DOCTYPE html>
<html lang="<?php echo substr($locale, 0, 2); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests; default-src * 'self' data:; script-src * 'self' 'unsafe-inline' 'unsafe-eval'; style-src * 'self' 'unsafe-inline'; font-src * 'self' data:; img-src * 'self' data:; connect-src * 'self'; object-src 'none'">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $projectTitle; ?></title>
    <meta name="description" content="<?php echo $projectDesc; ?>">
    <meta name="author" content="<?php echo sanitizeOutput($profile['name'] ?? 'Donatien KANANE'); ?>">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#6366f1">
    <link rel="canonical" href="<?php echo $projectUrl; ?>">

    <!-- Open Graph -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php echo $projectTitle; ?>">
    <meta property="og:description" content="<?php echo $projectDesc; ?>">
    <meta property="og:url" content="<?php echo $projectUrl; ?>">
    <meta property="og:image" content="<?php echo $projectImage; ?>">
    <meta property="og:locale" content="fr_FR">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $projectTitle; ?>">
    <meta name="twitter:description" content="<?php echo $projectDesc; ?>">
    <meta name="twitter:image" content="<?php echo $projectImage; ?>">

    <link rel="shortcut icon" href="favicon.ico">

    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://code.jquery.com">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/plugins/bootstrap/css/bootstrap.min.css">
    <link id="theme-style" rel="stylesheet" href="<?php echo $systemUrl; ?>assets/css/styles.min.css">

    <!-- JSON-LD -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CreativeWork",
        "name": "<?php echo sanitizeOutput($project['title']); ?>",
        "description": "<?php echo $projectDesc; ?>",
        "url": "<?php echo $projectUrl; ?>",
        "image": "<?php echo $projectImage; ?>",
        "author": {
            "@type": "Person",
            "name": "<?php echo sanitizeOutput($profile['name'] ?? 'Donatien KANANE'); ?>"
        }
    }
    </script>
</head> 

<body>
    <!-- Skip Navigation -->
    <a href="#main-content" class="skip-link">Aller au contenu principal</a>

    <!-- ===== NAVIGATION ===== -->
    <nav class="navbar-top scrolled" id="navbar" aria-label="Navigation principale">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="/" class="nav-brand">DK<span>.</span></a>
            <ul class="nav-links d-none d-md-flex" role="menubar">
                <li role="none"><a href="/#projects" role="menuitem">Projets</a></li>
                <li role="none"><a href="/#skills" role="menuitem">Compétences</a></li>
                <li role="none"><a href="/#experience" role="menuitem">Expérience</a></li>
                <li role="none"><a href="/#contact" role="menuitem">Contact</a></li>
            </ul>
            <div class="nav-actions d-flex align-items-center gap-3">
                <div class="form-check form-switch mb-0">
                    <input type="checkbox" class="form-check-input" id="darkSwitch" aria-label="Activer le mode sombre" />
                    <label class="form-check-label" for="darkSwitch"><i class="fas fa-moon"></i></label>
                </div>
                <a class="btn btn-primary-custom btn-sm" href="/"><i class="fas fa-arrow-left"></i> Retour</a>
            </div>
        </div>
    </nav>

    <main id="main-content">

    <!-- ===== PROJECT HERO ===== -->
    <section class="project-hero">
        <div class="project-hero-bg">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
        </div>
        <div class="container">
            <div class="project-hero-content">
                <a href="/#projects" class="project-hero-back"><i class="fas fa-arrow-left"></i> Tous les projets</a>
                <h1><?php echo sanitizeOutput($project['title']); ?></h1>
                <div class="project-hero-meta">
                    <?php if ($project['is_featured']): ?>
                    <span class="project-meta-badge featured"><i class="fas fa-star"></i> Projet phare</span>
                    <?php endif; ?>
                    <span class="project-meta-badge"><i class="fas fa-calendar"></i> <?php echo formatDate($project['created_at']); ?></span>
                </div>
            </div>
        </div>
    </section>
    
    <div class="container project-main-wrapper">
        <div class="row g-4">
            <!-- Main content -->
            <div class="col-lg-8">
                <div class="project-detail-card">
                    <?php if (!empty($project['image_url'])): ?>
                    <div class="project-detail-image">
                        <img class="img-fluid" src="<?php echo sanitizeOutput($project['image_url']); ?>" alt="<?php echo sanitizeOutput($project['title']); ?>" loading="lazy" width="800" height="500" />
                    </div>
                    <?php endif; ?>
                    <div class="project-detail-body">
                        <h2 class="project-detail-heading">Description du projet</h2>
                        <div class="project-detail-text">
                            <?php echo sanitizeWYSIWYG($project['description']); ?>
                        </div>
                        <?php if (!empty($project['project_url'])): ?>
                        <div class="project-detail-actions">
                            <a href="<?php echo sanitizeOutput($project['project_url']); ?>" class="btn btn-primary-custom" target="_blank" rel="noopener noreferrer">
                                <i class="fas fa-external-link-alt"></i> Voir le projet en ligne
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="project-comments-section">
                    <!-- Section heading — portfolio pattern -->
                    <div class="comments-section-header">
                        <div class="section-title text-start mb-0">
                            <span class="section-subtitle"><i class="fas fa-comments"></i> Discussion</span>
                            <h2>Commentaires <span class="comment-count-pill" id="commentCount"><?php echo countProjectComments($db, $project['project_url']); ?></span></h2>
                            <div class="title-line" style="margin:0;"></div>
                        </div>
                        <div class="comments-sort-toggle">
                            <button class="sort-btn active" data-sort="newest"><i class="fas fa-arrow-down-short-wide"></i> Récents</button>
                            <button class="sort-btn" data-sort="oldest"><i class="fas fa-arrow-up-short-wide"></i> Anciens</button>
                        </div>
                    </div>
                    
                    <?php if(!isUserLoggedIn()): ?>
                        <div class="comment-login-card">
                            <div class="comment-login-icon-wrap">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h5>Rejoignez la conversation</h5>
                            <p>Connectez-vous pour commenter, liker et répondre aux autres développeurs</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="<?php echo $systemUrl; ?>login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn btn-primary-custom">
                                    <i class="fas fa-sign-in-alt"></i> Se connecter
                                </a>
                                <a href="<?php echo $systemUrl; ?>register.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn btn-outline-primary-custom">
                                    <i class="fas fa-user-plus"></i> S'inscrire
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="comment-composer-card">
                            <div class="composer-header">
                                <div class="comment-avatar" data-initial="<?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>">
                                    <?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>
                                </div>
                                <div class="composer-user-info">
                                    <span class="composer-username"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Utilisateur'); ?></span>
                                    <span class="composer-meta-date"><i class="fas fa-circle"></i> En ligne</span>
                                </div>
                            </div>
                            <form id="commentForm" data-parent-id="">
                                <div class="composer-body">
                                    <textarea class="composer-textarea" id="commentContent" rows="3" 
                                              placeholder="Partagez votre avis sur ce projet..." maxlength="1000" required></textarea>
                                    <div class="composer-footer">
                                        <div class="composer-meta-left">
                                            <div class="char-progress-ring">
                                                <svg viewBox="0 0 24 24">
                                                    <circle class="ring-bg" cx="12" cy="12" r="10" />
                                                    <circle class="ring-fill" id="charRing" cx="12" cy="12" r="10" />
                                                </svg>
                                                <span class="char-count-num" id="charCount">0</span>
                                            </div>
                                            <span class="composer-hint"><i class="fas fa-globe"></i> Public</span>
                                        </div>
                                        <div class="composer-actions">
                                            <button type="button" class="btn-composer-cancel d-none" id="cancelReply">Annuler</button>
                                            <button type="submit" class="btn btn-primary-custom btn-sm" id="submitBtn">
                                                <i class="fas fa-paper-plane"></i> Publier
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>

                    <!-- Toast container -->
                    <div class="comment-toast-container" id="toastContainer"></div>

                    <div id="commentsList">
                        <?php
                        $currentUserId = isUserLoggedIn() ? $_SESSION['user_id'] : null;
                        $comments = getProjectComments($db, $project['project_url'], $currentUserId);
                        if (empty($comments)): ?>
                            <div class="comment-empty-state">
                                <div class="comment-empty-icon">
                                    <i class="far fa-comment-dots"></i>
                                </div>
                                <h5>Pas encore de commentaires</h5>
                                <p>Soyez la première voix dans cette discussion !</p>
                            </div>
                        <?php else: ?>
                            <?php 
                            function renderComment($comment, $isReply = false, $currentUserId = null, $isLoggedIn = false) {
                                $initial = strtoupper(substr($comment['username'], 0, 1));
                                $isOwn = $currentUserId && $currentUserId == $comment['user_id'];
                                $likedClass = $comment['user_liked'] ? ' liked' : '';
                            ?>
                            <div class="comment-card <?php echo $isReply ? 'comment-card--reply' : ''; ?>" 
                                 data-comment-id="<?php echo $comment['id']; ?>"
                                 data-timestamp="<?php echo strtotime($comment['created_at']); ?>">
                                <div class="comment-card-content">
                                    <div class="comment-card-top">
                                        <div class="comment-card-author">
                                            <div class="comment-avatar comment-avatar--sm" data-initial="<?php echo $initial; ?>">
                                                <?php echo $initial; ?>
                                            </div>
                                            <div class="comment-author-info">
                                                <span class="comment-author-name">
                                                    <?php echo htmlspecialchars($comment['username']); ?>
                                                    <?php if ($isOwn): ?>
                                                    <span class="comment-badge-own">vous</span>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="comment-date" data-time="<?php echo $comment['created_at']; ?>"><?php echo $comment['created_at']; ?></span>
                                            </div>
                                        </div>
                                        <?php if ($isOwn): ?>
                                        <div class="comment-card-menu dropdown">
                                            <button class="comment-menu-trigger" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><button class="dropdown-item edit-comment" data-comment-id="<?php echo $comment['id']; ?>"><i class="fas fa-pen"></i> Modifier</button></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><button class="dropdown-item text-danger delete-comment" data-comment-id="<?php echo $comment['id']; ?>"><i class="fas fa-trash-alt"></i> Supprimer</button></li>
                                            </ul>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="comment-card-body">
                                        <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                        <?php if (!empty($comment['updated_at'])): ?>
                                        <span class="comment-edited-badge"><i class="fas fa-pen"></i> modifié</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="comment-card-footer">
                                        <button class="comment-action like-action<?php echo $likedClass; ?>" data-comment-id="<?php echo $comment['id']; ?>">
                                            <i class="<?php echo $comment['user_liked'] ? 'fas' : 'far'; ?> fa-heart"></i>
                                            <span class="like-count"><?php echo $comment['likes_count'] > 0 ? $comment['likes_count'] : ''; ?></span>
                                        </button>
                                        <?php if ($isLoggedIn && !$isReply): ?>
                                        <button class="comment-action reply-action" data-comment-id="<?php echo $comment['id']; ?>" data-username="<?php echo htmlspecialchars($comment['username']); ?>">
                                            <i class="fas fa-reply"></i> Répondre
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if (!$isReply && !empty($comment['replies'])): ?>
                                <div class="comment-replies-thread">
                                    <?php foreach ($comment['replies'] as $reply): ?>
                                        <?php renderComment($reply, true, $currentUserId, $isLoggedIn); ?>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php
                            }
                            $isLoggedIn = isUserLoggedIn();
                            foreach ($comments as $comment): 
                                renderComment($comment, false, $currentUserId, $isLoggedIn);
                            endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="project-sidebar-card">
                    <h4><i class="fas fa-info-circle"></i> Informations</h4>
                    <div class="sidebar-info-list">
                        <div class="sidebar-info-item">
                            <span class="sidebar-info-label"><i class="fas fa-user"></i> Auteur</span>
                            <span class="sidebar-info-value"><?php echo sanitizeOutput($profile['name'] ?? 'Donatien KANANE'); ?></span>
                        </div>
                        <div class="sidebar-info-item">
                            <span class="sidebar-info-label"><i class="fas fa-calendar"></i> Date</span>
                            <span class="sidebar-info-value"><?php echo formatDate($project['created_at']); ?></span>
                        </div>
                        <?php if ($project['is_featured']): ?>
                        <div class="sidebar-info-item">
                            <span class="sidebar-info-label"><i class="fas fa-star"></i> Statut</span>
                            <span class="sidebar-info-value featured-text">Projet phare</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($project['project_url'])): ?>
                    <a href="<?php echo sanitizeOutput($project['project_url']); ?>" class="btn btn-primary-custom w-100 mt-3" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-external-link-alt"></i> Visiter le projet
                    </a>
                    <?php endif; ?>
                </div>

                <div class="project-sidebar-card">
                    <h4><i class="fas fa-share-alt"></i> Partager</h4>
                    <div class="sidebar-share-links">
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($systemUrl . 'projet/' . $project['slug']); ?>" target="_blank" rel="noopener noreferrer" class="share-btn linkedin" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($systemUrl . 'projet/' . $project['slug']); ?>&text=<?php echo urlencode($project['title']); ?>" target="_blank" rel="noopener noreferrer" class="share-btn twitter" title="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="mailto:?subject=<?php echo urlencode($project['title']); ?>&body=<?php echo urlencode($systemUrl . 'projet/' . $project['slug']); ?>" class="share-btn email" title="Email"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </main>

    <!-- ******FOOTER****** --> 
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">DK<span>.</span></div>
                <p class="footer-copy">&copy; <?php echo date('Y'); ?> Donatien KANANE. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
 
    </main>

    <!-- Javascript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/popper.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/dark-mode-switch/dark-mode-switch.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/js/main.js"></script>
    
    <script>
    $(document).ready(function() {
        const SYSTEM_URL = <?php echo json_encode($systemUrl); ?>;
        const PROJECT_URL = <?php echo json_encode($project['project_url']); ?>;
        const MAX_CHARS = 1000;
        const RING_CIRCUMFERENCE = 2 * Math.PI * 10;
        const IS_LOGGED_IN = <?php echo isUserLoggedIn() ? 'true' : 'false'; ?>;

        // ── Time Ago ──────────────────────────────────────────
        function timeAgo(dateStr) {
            const now = new Date();
            const date = new Date(dateStr.replace(' ', 'T'));
            const seconds = Math.floor((now - date) / 1000);
            if (seconds < 60) return "à l'instant";
            const minutes = Math.floor(seconds / 60);
            if (minutes < 60) return `il y a ${minutes} min`;
            const hours = Math.floor(minutes / 60);
            if (hours < 24) return `il y a ${hours}h`;
            const days = Math.floor(hours / 24);
            if (days < 7) return `il y a ${days}j`;
            if (days < 30) return `il y a ${Math.floor(days / 7)} sem`;
            if (days < 365) return `il y a ${Math.floor(days / 30)} mois`;
            return `il y a ${Math.floor(days / 365)} an(s)`;
        }
        $('.comment-date[data-time]').each(function() {
            $(this).text(timeAgo($(this).data('time')));
        });
        setInterval(function() {
            $('.comment-date[data-time]').each(function() {
                $(this).text(timeAgo($(this).data('time')));
            });
        }, 60000);

        // ── Character Counter + Progress Ring ─────────────────
        function updateCharRing(textarea) {
            const len = textarea.val().length;
            const ring = $('#charRing');
            const counter = $('#charCount');
            const ratio = len / MAX_CHARS;
            const offset = RING_CIRCUMFERENCE * (1 - ratio);

            ring.css('stroke-dashoffset', offset);
            counter.text(len);

            ring.removeClass('warn danger');
            counter.removeClass('warn danger');
            if (ratio >= 1) {
                ring.addClass('danger'); counter.addClass('danger');
                textarea.val(textarea.val().substring(0, MAX_CHARS));
                counter.text(MAX_CHARS);
                ring.css('stroke-dashoffset', 0);
            } else if (ratio >= 0.8) {
                ring.addClass('warn'); counter.addClass('warn');
            }
        }
        $(document).on('input', '.composer-textarea', function() {
            updateCharRing($(this));
        });

        // ── Toast Notifications ───────────────────────────────
        function showToast(type, message) {
            const icons = { success: 'check-circle', error: 'times-circle', warning: 'exclamation-triangle', info: 'info-circle' };
            const toast = $(`
                <div class="comment-toast toast-${type}">
                    <div class="toast-icon"><i class="fas fa-${icons[type] || icons.info}"></i></div>
                    <div class="toast-msg">${$('<span>').text(message).html()}</div>
                    <button class="toast-close"><i class="fas fa-times"></i></button>
                </div>
            `).hide();
            $('#toastContainer').append(toast);
            toast.slideDown(300);
            toast.find('.toast-close').on('click', function() {
                toast.slideUp(200, function() { toast.remove(); });
            });
            setTimeout(() => toast.slideUp(300, function() { toast.remove(); }), 4000);
        }

        // ── Comment Count ─────────────────────────────────────
        function updateCommentCount() {
            const count = $('.comment-card').length;
            const badge = $('#commentCount');
            badge.text(count);
            badge.addClass('count-bump');
            setTimeout(() => badge.removeClass('count-bump'), 300);
        }

        // ── Build Comment HTML ────────────────────────────────
        function escapeHtml(text) {
            return $('<div>').text(text).html();
        }
        function buildCommentHtml(c, isReply) {
            const initial = c.username.charAt(0).toUpperCase();
            const time = timeAgo(c.created_at);
            const replyClass = isReply ? 'comment-card--reply' : '';
            return `
            <div class="comment-card ${replyClass} comment-enter" data-comment-id="${c.id}" data-timestamp="${Math.floor(new Date(c.created_at.replace(' ','T')).getTime()/1000)}">
                <div class="comment-card-content">
                    <div class="comment-card-top">
                        <div class="comment-card-author">
                            <div class="comment-avatar comment-avatar--sm" data-initial="${initial}">${initial}</div>
                            <div class="comment-author-info">
                                <span class="comment-author-name">
                                    ${escapeHtml(c.username)}
                                    <span class="comment-badge-own">vous</span>
                                </span>
                                <span class="comment-date" data-time="${c.created_at}">${time}</span>
                            </div>
                        </div>
                        <div class="comment-card-menu dropdown">
                            <button class="comment-menu-trigger" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item edit-comment" data-comment-id="${c.id}"><i class="fas fa-pen"></i> Modifier</button></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><button class="dropdown-item text-danger delete-comment" data-comment-id="${c.id}"><i class="fas fa-trash-alt"></i> Supprimer</button></li>
                            </ul>
                        </div>
                    </div>
                    <div class="comment-card-body">${escapeHtml(c.content).replace(/\n/g, '<br>')}</div>
                    <div class="comment-card-footer">
                        <button class="comment-action like-action" data-comment-id="${c.id}">
                            <i class="far fa-heart"></i> <span class="like-count"></span>
                        </button>
                        ${(!isReply && IS_LOGGED_IN) ? `<button class="comment-action reply-action" data-comment-id="${c.id}" data-username="${escapeHtml(c.username)}"><i class="fas fa-reply"></i> Répondre</button>` : ''}
                    </div>
                </div>
            </div>`;
        }

        // ── Submit Comment ────────────────────────────────────
        $(document).on('submit', '#commentForm, .reply-form', function(e) {
            e.preventDefault();
            const form = $(this);
            const isReply = form.hasClass('reply-form');
            const textarea = form.find('textarea');
            const content = textarea.val().trim();
            const parentId = isReply ? form.data('parent-id') : (form.data('parent-id') || '');
            const submitBtn = form.find('[type="submit"]');

            if (!content) { showToast('warning', 'Veuillez entrer un commentaire'); return; }

            submitBtn.prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i> Envoi...');

            $.ajax({
                url: SYSTEM_URL + 'includes/handle_comment.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    project_url: PROJECT_URL,
                    content: content,
                    parent_id: parentId || null
                }),
                success: function(response) {
                    if (response.success) {
                        const c = response.comment;
                        const html = buildCommentHtml(c, !!parentId);
                        
                        $('#commentsList .comment-empty-state').remove();

                        if (parentId) {
                            const parentCard = $(`.comment-card[data-comment-id="${parentId}"]`);
                            let repliesDiv = parentCard.children('.comment-replies-thread');
                            if (!repliesDiv.length) {
                                parentCard.append('<div class="comment-replies-thread"></div>');
                                repliesDiv = parentCard.children('.comment-replies-thread');
                            }
                            repliesDiv.append(html);
                            form.closest('.inline-reply-wrap').slideUp(200, function() { $(this).remove(); });
                        } else {
                            $('#commentsList').prepend(html);
                            textarea.val('');
                            updateCharRing(textarea);
                        }

                        updateCommentCount();
                        showToast('success', parentId ? 'Réponse publiée !' : 'Commentaire publié !');

                        // Init Bootstrap dropdown on the new comment
                        const newCard = $(`.comment-card[data-comment-id="${c.id}"]`);
                        newCard.find('[data-bs-toggle="dropdown"]').each(function() {
                            new bootstrap.Dropdown(this);
                        });
                        setTimeout(() => newCard.removeClass('comment-enter'), 50);
                    }
                },
                error: function(xhr) {
                    showToast('error', xhr.responseJSON?.error || 'Une erreur est survenue');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Publier');
                }
            });
        });

        // ── Reply Button ──────────────────────────────────────
        $(document).on('click', '.reply-action', function() {
            const commentId = $(this).data('comment-id');
            const username = $(this).data('username');
            const parentCard = $(`.comment-card[data-comment-id="${commentId}"]`);

            parentCard.find('.inline-reply-wrap').remove();

            const replyFormHtml = `
            <div class="inline-reply-wrap" style="display:none;">
                <form class="reply-form" data-parent-id="${commentId}">
                    <div class="inline-reply-composer">
                        <textarea class="composer-textarea" rows="2" placeholder="Répondre à @${escapeHtml(username)}..." maxlength="${MAX_CHARS}" required></textarea>
                        <div class="inline-reply-actions">
                            <button type="button" class="btn-composer-cancel cancel-inline-reply">Annuler</button>
                            <button type="submit" class="btn btn-primary-custom btn-sm"><i class="fas fa-paper-plane"></i> Répondre</button>
                        </div>
                    </div>
                </form>
            </div>`;

            parentCard.children('.comment-card-content').after(replyFormHtml);
            parentCard.find('.inline-reply-wrap').slideDown(200);
            parentCard.find('.inline-reply-wrap textarea').focus();
        });

        $(document).on('click', '.cancel-inline-reply', function() {
            $(this).closest('.inline-reply-wrap').slideUp(200, function() { $(this).remove(); });
        });

        // ── Like Toggle ───────────────────────────────────────
        $(document).on('click', '.like-action', function() {
            if (!IS_LOGGED_IN) {
                showToast('info', 'Connectez-vous pour liker');
                return;
            }
            const btn = $(this);
            const commentId = btn.data('comment-id');
            btn.addClass('like-pulse');

            $.ajax({
                url: SYSTEM_URL + 'includes/handle_like.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ comment_id: commentId }),
                success: function(res) {
                    if (res.success) {
                        btn.toggleClass('liked', res.liked);
                        btn.find('i').toggleClass('fas', res.liked).toggleClass('far', !res.liked);
                        btn.find('.like-count').text(res.count > 0 ? res.count : '');
                    }
                },
                error: function () {
                    showToast('error', 'Impossible de liker');
                },
                complete: function() {
                    setTimeout(() => btn.removeClass('like-pulse'), 400);
                }
            });
        });

        // ── Edit Comment (inline) ─────────────────────────────
        $(document).on('click', '.edit-comment', function() {
            const commentId = $(this).data('comment-id');
            const card = $(`.comment-card[data-comment-id="${commentId}"]`);
            const body = card.find('.comment-card-body').first();

            // Already editing?
            if (card.find('.comment-edit-form').length) return;

            // Get raw text (strip <br>, edited badge)
            const clone = body.clone();
            clone.find('.comment-edited-badge').remove();
            const rawText = clone.html().replace(/<br\s*\/?>/gi, '\n').replace(/&amp;/g,'&').replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&quot;/g,'"').replace(/&#039;/g,"'").trim();

            const editForm = $(`
                <form class="comment-edit-form">
                    <textarea class="composer-textarea" rows="3" maxlength="${MAX_CHARS}" required>${escapeHtml(rawText)}</textarea>
                    <div class="inline-reply-actions" style="margin-top:10px">
                        <button type="button" class="btn-composer-cancel cancel-edit">Annuler</button>
                        <button type="submit" class="btn btn-primary-custom btn-sm"><i class="fas fa-check"></i> Enregistrer</button>
                    </div>
                </form>
            `);

            body.hide().after(editForm);
            editForm.find('textarea').focus();
        });

        $(document).on('click', '.cancel-edit', function() {
            const form = $(this).closest('.comment-edit-form');
            form.prev('.comment-card-body').show();
            form.remove();
        });

        $(document).on('submit', '.comment-edit-form', function(e) {
            e.preventDefault();
            const form = $(this);
            const card = form.closest('.comment-card');
            const commentId = card.data('comment-id');
            const content = form.find('textarea').val().trim();
            const submitBtn = form.find('[type="submit"]');

            if (!content) { showToast('warning', 'Le commentaire ne peut pas être vide'); return; }

            submitBtn.prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i>');

            $.ajax({
                url: SYSTEM_URL + 'includes/handle_edit_comment.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ comment_id: commentId, content: content }),
                success: function(res) {
                    if (res.success) {
                        const body = form.prev('.comment-card-body');
                        body.html(escapeHtml(res.content).replace(/\n/g, '<br>') + ' <span class="comment-edited-badge"><i class="fas fa-pen"></i> modifié</span>');
                        body.show();
                        form.remove();
                        showToast('success', 'Commentaire modifié');
                    }
                },
                error: function(xhr) {
                    showToast('error', xhr.responseJSON?.error || 'Erreur lors de la modification');
                    submitBtn.prop('disabled', false).html('<i class="fas fa-check"></i> Enregistrer');
                }
            });
        });

        // ── Delete Comment ───────────────────────────────────
        let deleteTarget = null;
        $(document).on('click', '.delete-comment', function() {
            deleteTarget = $(this).data('comment-id');
            $('#deleteModal').addClass('active');
        });
        $(document).on('click', '#deleteCancel, .delete-modal-overlay', function() {
            $('#deleteModal').removeClass('active');
            deleteTarget = null;
        });
        $(document).on('click', '#deleteConfirm', function() {
            if (!deleteTarget) return;
            const modal = $('#deleteModal');
            const commentCard = $(`.comment-card[data-comment-id="${deleteTarget}"]`);

            $.ajax({
                url: SYSTEM_URL + 'includes/delete_comment.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ comment_id: deleteTarget }),
                success: function(res) {
                    if (res.success) {
                        commentCard.addClass('comment-exit');
                        setTimeout(() => {
                            commentCard.remove();
                            updateCommentCount();
                            if ($('.comment-card').length === 0) {
                                $('#commentsList').html(`
                                    <div class="comment-empty-state">
                                        <div class="comment-empty-icon"><i class="far fa-comment-dots"></i></div>
                                        <h5>Pas encore de commentaires</h5>
                                        <p>Soyez la première voix dans cette discussion !</p>
                                    </div>
                                `);
                            }
                        }, 400);
                        showToast('success', 'Commentaire supprimé');
                    }
                },
                error: function(xhr) {
                    showToast('error', xhr.responseJSON?.error || 'Erreur lors de la suppression');
                },
                complete: function() {
                    modal.removeClass('active');
                    deleteTarget = null;
                }
            });
        });

        // ── Sort Toggle ───────────────────────────────────────
        $(document).on('click', '.sort-btn', function() {
            const sort = $(this).data('sort');
            $('.sort-btn').removeClass('active');
            $(this).addClass('active');

            const list = $('#commentsList');
            const cards = list.children('.comment-card').get();
            cards.sort((a, b) => {
                const ta = parseInt($(a).data('timestamp'));
                const tb = parseInt($(b).data('timestamp'));
                return sort === 'newest' ? tb - ta : ta - tb;
            });
            $.each(cards, function(_, card) { list.append(card); });
        });
    });
    </script>

    <!-- Delete Confirmation Modal -->
    <div class="delete-modal" id="deleteModal">
        <div class="delete-modal-overlay"></div>
        <div class="delete-modal-content">
            <div class="delete-modal-icon"><i class="fas fa-trash-alt"></i></div>
            <h5>Supprimer ce commentaire ?</h5>
            <p>Cette action est irréversible.</p>
            <div class="delete-modal-actions">
                <button class="btn-modal-cancel" id="deleteCancel">Annuler</button>
                <button class="btn-modal-delete" id="deleteConfirm"><i class="fas fa-trash-alt"></i> Supprimer</button>
            </div>
        </div>
    </div>
</body>
</html> 