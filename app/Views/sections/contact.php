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
