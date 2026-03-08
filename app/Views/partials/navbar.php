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
            <a href="/" class="nav-brand">DK<span>.</span></a>
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
