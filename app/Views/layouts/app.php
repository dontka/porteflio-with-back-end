<!DOCTYPE html>
<html lang="<?php echo substr($locale, 0, 2); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests; default-src * 'self' data:; script-src * 'self' 'unsafe-inline' 'unsafe-eval'; style-src * 'self' 'unsafe-inline'; font-src * 'self' data:; img-src * 'self' data:; connect-src * 'self'; object-src 'none'">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Portfolio | Donatien KANANE'; ?></title>
    <meta name="description" content="<?php echo $pageDescription ?? 'Développeur Web Full-Stack & Data Analyst'; ?>">
    <meta name="author" content="<?php echo sanitizeOutput($profile['name'] ?? 'Donatien KANANE'); ?>">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#6366f1">
    <link rel="canonical" href="<?php echo $pageUrl ?? $systemUrl; ?>">

    <!-- Open Graph -->
    <meta property="og:type" content="<?php echo $ogType ?? 'website'; ?>">
    <meta property="og:title" content="<?php echo $pageTitle ?? 'Portfolio'; ?>">
    <meta property="og:description" content="<?php echo $pageDescription ?? ''; ?>">
    <meta property="og:url" content="<?php echo $pageUrl ?? $systemUrl; ?>">
    <meta property="og:image" content="<?php echo $pageImage ?? $systemUrl . 'assets/images/profile.png'; ?>">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="Donatien KANANE Portfolio">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $pageTitle ?? 'Portfolio'; ?>">
    <meta name="twitter:description" content="<?php echo $pageDescription ?? ''; ?>">
    <meta name="twitter:image" content="<?php echo $pageImage ?? $systemUrl . 'assets/images/profile.png'; ?>">

    <link rel="shortcut icon" href="favicon.ico">

    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/plugins/bootstrap/css/bootstrap.min.css">
    
    <!-- GitHub specific styles (conditionally loaded) -->
    <?php if (strpos($pageTitle ?? '', 'Portfolio') !== false): ?>
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/plugins/github-calendar/dist/github-calendar-responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/octicons/2.0.2/octicons.min.css">
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/plugins/github-activity/src/github-activity.min.css">
    <?php endif; ?>
    
    <!-- Page-specific styles -->
    <?php if (!empty($pageStyles)): ?>
        <?php foreach ((array)$pageStyles as $style): ?>
            <link rel="stylesheet" href="<?php echo $systemUrl; ?><?php echo $style; ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Theme style -->
    <link id="theme-style" rel="stylesheet" href="<?php echo $systemUrl; ?>assets/css/styles.min.css?v=<?php echo time(); ?>">

    <!-- Initialize dark mode: body has dark-mode class by default, remove if localStorage says so -->
    <script>
        // If user previously disabled dark mode, remove the class immediately to prevent flash
        if (localStorage.getItem('darkMode') === 'disabled') {
            document.body.classList.remove('dark-mode');
        }
    </script>
</head>

<body class="dark-mode">
    <!-- Skip Navigation -->
    <a href="#main-content" class="skip-link">Aller au contenu principal</a>

    <!-- Include Navigation Partial -->
    <?php $view->include('partials/navbar', ['isScrolled' => $isScrolled ?? false]); ?>

    <!-- Main Content -->
    <main id="main-content">
        <?php echo $content; ?>
    </main>

    <!-- Include Footer Partial -->
    <?php $view->include('partials/footer'); ?>

    <!-- Back to top Button -->
    <button class="back-to-top" id="backToTop" aria-label="Retour en haut"><i class="fas fa-arrow-up"></i></button>

    <!-- Scripts -->
    <script src="<?php echo $systemUrl; ?>assets/plugins/popper.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/dark-mode-switch/dark-mode-switch.min.js"></script>
    
    <!-- GitHub specific scripts (conditionally loaded) -->
    <?php if (strpos($pageTitle ?? '', 'Portfolio') !== false): ?>
    <script src="<?php echo $systemUrl; ?>assets/plugins/vanilla-rss/dist/rss.global.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/github-calendar/dist/github-calendar.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/mustache.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/github-activity/src/github-activity.min.js"></script>
    <?php endif; ?>
    
    <!-- Page-specific scripts -->
    <?php if (!empty($pageScripts)): ?>
        <?php foreach ((array)$pageScripts as $script): ?>
            <script src="<?php echo $systemUrl; ?><?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Default scripts -->
    <script src="<?php echo $systemUrl; ?>assets/js/main.min.js?v=<?php echo time(); ?>"></script>

    <!-- Page-specific inline scripts -->
    <?php if (!empty($inlineScripts)): ?>
        <script>
            <?php echo $inlineScripts; ?>
        </script>
    <?php endif; ?>
</body>
</html>
