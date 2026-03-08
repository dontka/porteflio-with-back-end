<?php
/**
 * Partial: Footer
 * Réutilisable dans tous les templates
 */
?>
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">DK<span>.</span></div>
            
            <div class="footer-socials">
                <?php if (!empty($profile['github_username'])): ?>
                    <a href="https://github.com/<?php echo sanitizeOutput($profile['github_username']); ?>" target="_blank" rel="noopener noreferrer" aria-label="GitHub">
                        <i class="fa-brands fa-github"></i>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($profile['linkedin_url'])): ?>
                    <a href="<?php echo sanitizeOutput($profile['linkedin_url']); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($profile['twitter_url'])): ?>
                    <a href="<?php echo sanitizeOutput($profile['twitter_url']); ?>" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($profile['email'])): ?>
                    <a href="mailto:<?php echo sanitizeOutput($profile['email']); ?>" title="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                <?php endif; ?>
            </div>
            
            <p class="footer-copy">&copy; <?php echo date('Y'); ?> <?php echo sanitizeOutput($profile['name'] ?? 'Donatien KANANE'); ?>. Tous droits réservés.</p>
        </div>
    </div>
</footer>
