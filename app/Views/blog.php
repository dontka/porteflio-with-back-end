<?php
/**
 * BLOG.PHP - Page détail d'un article
 * Utilise le layout 'app.php'
 */
?>

<!-- Blog Hero Section -->
<section class="blog-hero">
    <div class="blog-hero-bg">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>
    <div class="container">
        <div class="blog-hero-content">
            <span class="blog-category"><?php echo sanitizeOutput($post['category'] ?? ''); ?></span>
            <h1><?php echo sanitizeOutput($post['title']); ?></h1>
            <p class="blog-date">
                <i class="far fa-calendar-alt"></i> 
                <?php echo date('d F Y', strtotime($post['created_at'])); ?>
            </p>
        </div>
    </div>
</section>

<!-- Blog Main Content -->
<div class="blog-main-wrapper">
    <div class="container">
        <div class="row g-4">
            <!-- Main Content Column -->
            <div class="col-lg-8">
                <!-- Featured Image -->
                <?php if (!empty($post['image_url'])): ?>
                <div class="blog-featured-image mb-5">
                    <?php echo pictureTag(sanitizeOutput($post['image_url']), sanitizeOutput($post['title']), 'class="img-fluid rounded-3" loading="lazy" width="800" height="500"'); ?>
                </div>
                <?php endif; ?>

                <!-- Article Body -->
                <article class="blog-content">
                    <?php if (!empty($post['body'])): ?>
                        <?php echo sanitizeWYSIWYG($post['body']); ?>
                    <?php else: ?>
                        <p><?php echo sanitizeWYSIWYG($post['excerpt'] ?? ''); ?></p>
                    <?php endif; ?>
                </article>

                <!-- Comments Section -->
                <section class="blog-comments-section mt-5 pt-5 border-top">
                    <h3 class="mb-4"><i class="fas fa-comments"></i> Commentaires</h3>
                    
                    <?php if (isUserLoggedIn()): ?>
                        <!-- Composer -->
                        <form method="POST" action="<?php echo $systemUrl; ?>api.php" class="comment-composer mb-5" id="commentForm">
                            <input type="hidden" name="action" value="add_comment">
                            <input type="hidden" name="blog_slug" value="<?php echo htmlspecialchars($post['slug']); ?>">
                            
                            <textarea class="composer-textarea" name="text" placeholder="Partager votre avis..." rows="4" maxlength="1000" required></textarea>
                            
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
                            <a href="login.php?redirect=blog/<?php echo htmlspecialchars($post['slug']); ?>">Connectez-vous</a> pour laisser un commentaire.
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
                <div class="blog-sidebar">
                    <div class="blog-meta">
                        <h4><i class="fas fa-info-circle"></i> À propos</h4>
                        
                        <div class="meta-item">
                            <span class="meta-label">Publié</span>
                            <span class="meta-value"><?php echo date('d M Y', strtotime($post['created_at'])); ?></span>
                        </div>
                        
                        <?php if (!empty($post['category'])): ?>
                        <div class="meta-item">
                            <span class="meta-label">Catégorie</span>
                            <span class="meta-value"><?php echo sanitizeOutput($post['category']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Engagement Stats -->
                    <div class="blog-stats mt-4">
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
                    <div class="blog-share mt-4">
                        <h4><i class="fas fa-share-alt"></i> Partager</h4>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($pageUrl); ?>" target="_blank" rel="noopener noreferrer" class="share-btn" title="Facebook">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($pageUrl); ?>&text=<?php echo urlencode($post['title']); ?>" target="_blank" rel="noopener noreferrer" class="share-btn" title="Twitter">
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
