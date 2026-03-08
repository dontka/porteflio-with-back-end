<!-- ===== BLOG / ACTUALITÉS ===== -->
<section class="section-block blog-section" id="blog">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-subtitle">Mes publications</span>
            <h2>Blog & Actualités</h2>
            <div class="title-line"></div>
        </div>
        <?php if (!empty($blogPosts) && is_array($blogPosts)):
            $featuredPost = $blogPosts[0] ?? null;
            $otherPosts = !empty($featuredPost) ? array_slice($blogPosts, 1) : [];
        ?>

        <!-- Article principal -->
        <div class="blog-featured">
            <div class="row g-0 align-items-stretch">
                <div class="col-lg-6">
                    <div class="blog-featured-image">
                        <?php if (!empty($featuredPost['image_url'])): ?>
                            <?php echo pictureTag(sanitizeOutput($featuredPost['image_url']), sanitizeOutput($featuredPost['title']), 'loading="lazy" width="600" height="400"'); ?>
                        <?php else: ?>
                            <div class="blog-card-placeholder">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        <?php endif; ?>
                        <span class="blog-card-category"><?php echo sanitizeOutput($featuredPost['category']); ?></span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="blog-featured-body">
                        <span class="blog-featured-label"><i class="fas fa-fire"></i> À la une</span>
                        <div class="blog-card-date">
                            <i class="far fa-calendar-alt"></i>
                            <?php echo date('d M Y', strtotime($featuredPost['created_at'])); ?>
                        </div>
                        <h3><a href="blog/<?php echo sanitizeOutput($featuredPost['slug']); ?>"><?php echo sanitizeOutput($featuredPost['title']); ?></a></h3>
                        <p><?php echo sanitizeWYSIWYG($featuredPost['excerpt']); ?></p>
                        <div class="card-engagement">
                            <?php
                                $fSlug = $featuredPost['slug'] ?? '';
                                $bComments = (!empty($blogStats) && isset($blogStats[$fSlug])) ? ($blogStats[$fSlug]['comments'] ?? 0) : 0;
                                $bLikes = (!empty($blogStats) && isset($blogStats[$fSlug])) ? ($blogStats[$fSlug]['likes'] ?? 0) : 0;
                            ?>
                            <span class="engagement-badge" title="Commentaires"><i class="far fa-comment-dots"></i> <?php echo $bComments; ?></span>
                            <span class="engagement-badge" title="Likes"><i class="far fa-heart"></i> <?php echo $bLikes; ?></span>
                        </div>
                        <a href="blog/<?php echo sanitizeOutput($featuredPost['slug']); ?>" class="btn btn-primary-custom btn-sm">
                            <i class="fas fa-arrow-right"></i> Lire l'article
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($otherPosts) && is_array($otherPosts)): ?>
        <!-- Fil d'actualités connecté -->
        <div class="blog-feed">
            <div class="blog-feed-line"></div>
            <?php foreach ($otherPosts as $i => $post): ?>
            <div class="blog-feed-item <?php echo $i % 2 === 0 ? '' : 'blog-feed-item-alt'; ?>">
                <div class="blog-feed-dot">
                    <i class="fas fa-circle"></i>
                </div>
                <div class="blog-feed-card">
                    <div class="blog-feed-card-inner">
                        <div class="blog-feed-meta">
                            <span class="blog-feed-category"><?php echo sanitizeOutput($post['category']); ?></span>
                            <span class="blog-feed-date"><i class="far fa-clock"></i> <?php echo date('d M Y', strtotime($post['created_at'])); ?></span>
                        </div>
                        <h4><a href="blog/<?php echo sanitizeOutput($post['slug']); ?>"><?php echo sanitizeOutput($post['title']); ?></a></h4>
                        <p><?php echo mb_strimwidth(sanitizeWYSIWYG($post['excerpt']), 0, 150, '...'); ?></p>
                        <div class="card-engagement">
                            <?php
                                $pSlug = $post['slug'] ?? '';
                                $bC = (!empty($blogStats) && isset($blogStats[$pSlug])) ? ($blogStats[$pSlug]['comments'] ?? 0) : 0;
                                $bL = (!empty($blogStats) && isset($blogStats[$pSlug])) ? ($blogStats[$pSlug]['likes'] ?? 0) : 0;
                            ?>
                            <span class="engagement-badge" title="Commentaires"><i class="far fa-comment-dots"></i> <?php echo $bC; ?></span>
                            <span class="engagement-badge" title="Likes"><i class="far fa-heart"></i> <?php echo $bL; ?></span>
                        </div>
                        <a href="blog/<?php echo sanitizeOutput($post['slug']); ?>" class="blog-read-more">
                            Lire la suite <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <div class="text-center">
            <p class="text-muted">Aucun article pour le moment. Revenez bientôt !</p>
        </div>
        <?php endif; ?>
    </div>
</section>
