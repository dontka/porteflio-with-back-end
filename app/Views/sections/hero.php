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
                <p class="hero-description"><?php echo sanitizeWYSIWYG($profile['description'] ?? ''); ?></p>
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
