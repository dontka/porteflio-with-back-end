<?php
/**
 * Partial: Navigation Bar
 * Réutilisable dans tous les templates
 */
?>
  <!-- Skip Navigation -->
    <a href="#main-content" class="skip-link">Aller au contenu principal</a>

    <!-- ===== NAVIGATION ===== -->
    <nav class="navbar-top" id="navbar" aria-label="Navigation principale">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="<?php echo $systemUrl; ?>" class="nav-brand">DK<span>.</span></a>
            <ul class="nav-links d-none d-md-flex" role="menubar">
                <li role="none"><a href="<?php echo $systemUrl; ?>#hero" role="menuitem">Accueil</a></li>
                <li role="none"><a href="<?php echo $systemUrl; ?>#about" role="menuitem">À propos</a></li>
                <li role="none"><a href="<?php echo $systemUrl; ?>#services" role="menuitem">Services</a></li>
                <li role="none"><a href="<?php echo $systemUrl; ?>#stack" role="menuitem">Stack</a></li>
                <li role="none"><a href="<?php echo $systemUrl; ?>#projects" role="menuitem">Projets</a></li>
                <li role="none"><a href="<?php echo $systemUrl; ?>#skills" role="menuitem">Compétences</a></li>
                <li role="none"><a href="<?php echo $systemUrl; ?>#experience" role="menuitem">Expérience</a></li>
                <li role="none"><a href="<?php echo $systemUrl; ?>#blog" role="menuitem">Blog</a></li>
                <li role="none"><a href="<?php echo $systemUrl; ?>#contact" role="menuitem">Contact</a></li>
            </ul>
            <div class="nav-actions d-flex align-items-center gap-2">
                <!-- Authentication Links -->
                <div class="nav-auth d-flex align-items-center gap-2">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="nav-user-profile d-flex align-items-center gap-2">
                            <i class="fas fa-user-circle"></i>
                            <span class="nav-username" title="<?php echo htmlspecialchars($_SESSION['username'] ?? 'Utilisateur'); ?>">
                                <?php echo htmlspecialchars($_SESSION['username'] ?? 'Utilisateur'); ?>
                            </span>
                        </div>
                        <a href="<?php echo $systemUrl; ?>logout" class="nav-auth-btn nav-logout" title="Se déconnecter">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="d-none d-lg-inline">Déconnexion</span>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo $systemUrl; ?>login" class="nav-auth-btn nav-login" title="Se connecter">
                            <i class="fas fa-sign-in-alt"></i>
                            <span class="d-none d-lg-inline">Connexion</span>
                        </a>
                        <a href="<?php echo $systemUrl; ?>register" class="nav-auth-btn nav-register-btn" title="Créer un compte">
                            <i class="fas fa-user-plus"></i>
                            <span class="d-none d-lg-inline">Inscription</span>
                        </a>
                    <?php endif; ?>
                </div>
                
                <!-- Separator -->
                <div class="nav-separator d-none d-md-block"></div>
                
                <!-- Dark Mode Toggle -->
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
            <a href="<?php echo $systemUrl; ?>#hero">Accueil</a>
            <a href="<?php echo $systemUrl; ?>#about">À propos</a>
            <a href="<?php echo $systemUrl; ?>#services">Services</a>
            <a href="<?php echo $systemUrl; ?>#stack">Stack</a>
            <a href="<?php echo $systemUrl; ?>#projects">Projets</a>
            <a href="<?php echo $systemUrl; ?>#skills">Compétences</a>
            <a href="<?php echo $systemUrl; ?>#experience">Expérience</a>
            <a href="<?php echo $systemUrl; ?>#blog">Blog</a>
            <a href="<?php echo $systemUrl; ?>#contact">Contact</a>
            <hr>
            <!-- Mobile Authentication -->
            <div class="mobile-auth">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="mobile-user-profile d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-user-circle"></i>
                        <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'Utilisateur'); ?></strong>
                    </div>
                    <a href="<?php echo $systemUrl; ?>logout" class="mobile-auth-link mobile-logout">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                <?php else: ?>
                    <a href="<?php echo $systemUrl; ?>login" class="mobile-auth-link mobile-login">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </a>
                    <a href="<?php echo $systemUrl; ?>register" class="mobile-auth-link mobile-register">
                        <i class="fas fa-user-plus"></i> Inscription
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
