<!-- ===== PROJECTS ===== -->
<section class="section-block projects-section" id="projects">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-subtitle">Mon travail</span>
            <h2>Projets Récents</h2>
            <div class="title-line"></div>
        </div>

        <?php
        $featured = null;
        $otherProjects = [];
        if (!empty($projects) && is_array($projects)):
        foreach ($projects as $p) {
            if ($p['is_featured'] && !$featured) {
                $featured = $p;
            } else {
                $otherProjects[] = $p;
            }
        }
        ?>

        <?php if ($featured): ?>
        <div class="featured-project mb-5">
            <div class="row align-items-center g-4">
                <?php if (!empty($featured['image_url'])): ?>
                <div class="col-lg-6">
                    <div class="featured-project-image">
                        <?php echo pictureTag(sanitizeOutput($featured['image_url']), sanitizeOutput($featured['title']), 'class="img-fluid rounded-3" loading="lazy" width="600" height="400"'); ?>
                        <div class="featured-badge"><i class="fas fa-star"></i> Projet phare</div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="col-lg-6">
                    <div class="featured-project-content">
                        <h3><?php echo sanitizeOutput($featured['title']); ?></h3>
                        <p><?php echo sanitizeWYSIWYG($featured['description']); ?></p>
                        <div class="card-engagement">
                            <?php
                                $fUrl = $featured['project_url'] ?? '';
                                $fComments = (!empty($projectStats) && isset($projectStats[$fUrl])) ? ($projectStats[$fUrl]['comments'] ?? 0) : 0;
                                $fLikes = (!empty($projectStats) && isset($projectStats[$fUrl])) ? ($projectStats[$fUrl]['likes'] ?? 0) : 0;
                            ?>
                            <span class="engagement-badge" title="Commentaires"><i class="far fa-comment-dots"></i> <?php echo $fComments; ?></span>
                            <span class="engagement-badge" title="Likes"><i class="far fa-heart"></i> <?php echo $fLikes; ?></span>
                        </div>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="<?php echo sanitizeOutput($featured['project_url']); ?>" class="btn btn-primary-custom" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt"></i> Voir le projet</a>
                            <a href="projet/<?php echo sanitizeOutput($featured['slug'] ?? slugify($featured['title'])); ?>" class="btn btn-outline-custom"><i class="fas fa-info-circle"></i> Détails</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php foreach ($otherProjects as $proj): ?>
            <div class="col-md-6 col-lg-4">
                <div class="project-card">
                    <?php if (!empty($proj['image_url'])): ?>
                    <div class="project-card-image">
                        <a href="projet/<?php echo sanitizeOutput($proj['slug'] ?? slugify($proj['title'])); ?>">
                            <?php echo pictureTag(sanitizeOutput($proj['image_url']), sanitizeOutput($proj['title']), 'loading="lazy" width="400" height="260"'); ?>
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="project-card-image project-card-placeholder">
                        <i class="fas fa-code fa-3x"></i>
                    </div>
                    <?php endif; ?>
                    <div class="project-card-body">
                        <h4><a href="projet/<?php echo sanitizeOutput($proj['slug'] ?? slugify($proj['title'])); ?>"><?php echo sanitizeOutput($proj['title']); ?></a></h4>
                        <p><?php echo mb_strimwidth(sanitizeWYSIWYG($proj['description']), 0, 120, '...'); ?></p>
                        <div class="card-engagement">
                            <?php
                                $pUrl = $proj['project_url'];
                                $pComments = $projectStats[$pUrl]['comments'] ?? 0;
                                $pLikes = $projectStats[$pUrl]['likes'] ?? 0;
                            ?>
                            <span class="engagement-badge" title="Commentaires"><i class="far fa-comment-dots"></i> <?php echo $pComments; ?></span>
                            <span class="engagement-badge" title="Likes"><i class="far fa-heart"></i> <?php echo $pLikes; ?></span>
                        </div>
                        <a href="projet/<?php echo sanitizeOutput($proj['slug'] ?? slugify($proj['title'])); ?>" class="project-link"><i class="fas fa-arrow-right"></i> En savoir plus</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
