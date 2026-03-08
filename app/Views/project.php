<?php
/**
 * PROJECT.PHP - Page détail d'un projet
 * Utilise le layout 'app.php'
 */
?>

<!-- Project Hero Section -->
<section class="project-hero">
    <div class="project-hero-bg">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>
    <div class="container">
        <div class="project-hero-content">
            <h1><?php echo sanitizeOutput($project['title']); ?></h1>
            <p class="project-hero-subtitle"><?php echo sanitizeWYSIWYG($project['description'] ?? ''); ?></p>
            <?php if (!empty($project['project_url'])): ?>
            <a href="<?php echo sanitizeOutput($project['project_url']); ?>" class="btn btn-primary-custom" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-external-link-alt"></i> Visiter le projet
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Project Main Content -->
<div class="project-main-wrapper">
    <div class="container">
        <div class="row g-4">
            <!-- Main Content Column -->
            <div class="col-lg-8">
                <!-- Project Image -->
                <?php if (!empty($project['image_url'])): ?>
                <div class="project-main-image mb-5">
                    <?php echo pictureTag(sanitizeOutput($project['image_url']), sanitizeOutput($project['title']), 'class="img-fluid rounded-3" loading="lazy" width="800" height="500"'); ?>
                </div>
                <?php endif; ?>

                <!-- Project Body  -->
                <div class="project-body">
                    <?php if (!empty($project['body'])): ?>
                        <?php echo sanitizeWYSIWYG($project['body']); ?>
                    <?php else: ?>
                        <p><?php echo sanitizeWYSIWYG($project['description'] ?? ''); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Comments Section -->
                <section class="project-comments-section mt-5 pt-5 border-top">
                    <h3 class="mb-4"><i class="fas fa-comments"></i> Commentaires</h3>
                    
                    <?php if (isUserLoggedIn()): ?>
                        <!-- Composer -->
                        <form method="POST" action="<?php echo $systemUrl; ?>api.php" class="comment-composer mb-5" id="commentForm">
                            <input type="hidden" name="action" value="add_comment">
                            <input type="hidden" name="project_slug" value="<?php echo htmlspecialchars($project['slug']); ?>">
                            
                            <textarea class="composer-textarea" name="text" placeholder="Partager votre retour..." rows="4" maxlength="1000" required></textarea>
                            
                            <div class="composer-footer d-flex justify-content-between align-items-center">
                                <div class="char-counter">
                                    <svg class="progress-ring" width="30" height="30" viewBox="0 0 30 30">
                                        <circle class="progress-ring-bg" r="10" cx="15" cy="15"/>
                                        <circle class="progress-ring-fill" r="10" cx="15" cy="15" style="stroke-dashoffset: 62.8"/>
                                    </svg>
                                    <span class="char-count">0/1000</span>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary-custom">
                                    <i class="fas fa-send"></i> Partager
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info mb-5">
                            <i class="fas fa-info-circle"></i> 
                            <a href="login.php?redirect=projet/<?php echo htmlspecialchars($project['slug']); ?>">Connectez-vous</a> pour laisser un commentaire.
                        </div>
                    <?php endif; ?>

                    <!-- Comments List -->
                    <div id="commentsList" class="comments-list">
                        <!-- Loaded via JavaScript -->
                    </div>
                </section>
            </div>

            <!-- Sidebar -->
            <aside class="col-lg-4">
                <div class="project-sidebar">
                    <div class="project-meta">
                        <h4><i class="fas fa-info-circle"></i> Informations</h4>
                        
                        <?php if (!empty($project['category'])): ?>
                        <div class="meta-item">
                            <span class="meta-label">Catégorie</span>
                            <span class="meta-value"><?php echo sanitizeOutput($project['category']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($project['created_at'])): ?>
                        <div class="meta-item">
                            <span class="meta-label">Date</span>
                            <span class="meta-value"><?php echo date('d M Y', strtotime($project['created_at'])); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($project['project_url'])): ?>
                        <div class="meta-item">
                            <a href="<?php echo sanitizeOutput($project['project_url']); ?>" class="btn btn-outline-custom btn-sm w-100" target="_blank" rel="noopener noreferrer">
                                <i class="fas fa-external-link-alt"></i> Visiter
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Engagement Stats -->
                    <div class="project-stats mt-4">
                        <h4><i class="fas fa-chart-bar"></i> Engagement</h4>
                        <div class="stat-item">
                            <span class="stat-icon"><i class="far fa-comment"></i></span>
                            <span class="stat-count" id="commentCount">0</span>
                            <span class="stat-label">commentaires</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-icon"><i class="far fa-heart"></i></span>
                            <span class="stat-count" id="likeCount">0</span>
                            <span class="stat-label">likes</span>
                        </div>
                    </div>

                    <!-- Share Buttons -->
                    <div class="project-share mt-4">
                        <h4><i class="fas fa-share-alt"></i> Partager</h4>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($pageUrl); ?>" target="_blank" rel="noopener noreferrer" class="share-btn" title="Facebook">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($pageUrl); ?>&text=<?php echo urlencode($project['title']); ?>" target="_blank" rel="noopener noreferrer" class="share-btn" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($pageUrl); ?>" target="_blank" rel="noopener noreferrer" class="share-btn" title="LinkedIn">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <button class="share-btn" onclick="navigator.clipboard.writeText('<?php echo $pageUrl; ?>')" title="Copier le lien">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
