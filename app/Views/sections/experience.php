<!-- ===== EXPERIENCE ===== -->
<section class="section-block experience-section" id="experience">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-subtitle">Mon parcours</span>
            <h2>Expérience Professionnelle</h2>
            <div class="title-line"></div>
        </div>
        <div class="timeline">
            <?php foreach (($experience ?? []) as $i => $exp): ?>
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
