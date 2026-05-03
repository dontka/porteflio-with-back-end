<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Admin'; ?></title>
    <link rel="stylesheet" href="<?php echo SYS_URL; ?>assets/css/admin.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="admin-header">
            <h1 class="admin-logo">
                <i class="fas fa-tachometer-alt"></i>
                Admin
            </h1>
        </div>

        <nav class="admin-menu">
            <a href="<?php echo SYS_URL; ?>admin" class="admin-menu-item">
                <i class="fas fa-home"></i>
                <span>Tableau de Bord</span>
            </a>

            <div class="admin-menu-section">
                <h3>Contenu</h3>
                <a href="<?php echo SYS_URL; ?>admin/blog" class="admin-menu-item">
                    <i class="fas fa-blog"></i>
                    <span>Articles de Blog</span>
                </a>
                <a href="<?php echo SYS_URL; ?>admin/projects" class="admin-menu-item">
                    <i class="fas fa-project-diagram"></i>
                    <span>Projets</span>
                </a>
                <a href="<?php echo SYS_URL; ?>admin/skills" class="admin-menu-item">
                    <i class="fas fa-star"></i>
                    <span>Compétences</span>
                </a>
                <a href="<?php echo SYS_URL; ?>admin/experience" class="admin-menu-item">
                    <i class="fas fa-briefcase"></i>
                    <span>Expériences</span>
                </a>
            </div>

            <div class="admin-menu-section">
                <h3>Gestion</h3>
                <a href="<?php echo SYS_URL; ?>admin/users" class="admin-menu-item">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="<?php echo SYS_URL; ?>admin/logs" class="admin-menu-item">
                    <i class="fas fa-history"></i>
                    <span>Logs</span>
                </a>
                <a href="<?php echo SYS_URL; ?>admin/settings" class="admin-menu-item">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
                </a>
            </div>
        </nav>

        <div class="admin-user-info">
            <div class="user-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-details">
                <p class="user-name"><?php echo htmlspecialchars($currentUser['username'] ?? 'Admin'); ?></p>
                <a href="<?php echo SYS_URL; ?>logout" class="admin-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Top Bar -->
        <header class="admin-topbar">
            <div class="topbar-content">
                <h2 class="page-title"><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : ''; ?></h2>
                <div class="topbar-actions">
                    <a href="<?php echo SYS_URL; ?>" class="btn btn-secondary" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        Voir le site
                    </a>
                </div>
            </div>
        </header>

        <!-- Alerts -->
        <div class="admin-alerts">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($_SESSION['success']); ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Page Content -->
        <section class="admin-content">
            <?php echo $content; ?>
        </section>
    </main>

    <script src="<?php echo SYS_URL; ?>assets/js/admin.min.js"></script>
</body>
</html>
