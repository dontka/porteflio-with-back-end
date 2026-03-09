

    <main id="main-content">

    <!-- ===== BLOG HERO ===== -->
    <section class="project-hero">
        <div class="project-hero-bg">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
        </div>
        <div class="container">
            <div class="project-hero-content">
                <a href="/#blog" class="project-hero-back">
                    <i class="fas fa-arrow-left"></i> Retour au blog
                </a>
                <h1><?php echo sanitizeOutput($post['title']); ?></h1>
                <div class="project-hero-meta">
                    <span class="project-meta-badge">
                        <i class="fas fa-tag"></i> <?php echo sanitizeOutput($post['category']); ?>
                    </span>
                    <span class="project-meta-badge">
                        <i class="far fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($post['created_at'])); ?>
                    </span>
                    <span class="project-meta-badge">
                        <i class="far fa-clock"></i> <?php echo max(1, round(str_word_count($post['content']) / 200)); ?> min de lecture
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== BLOG CONTENT ===== -->
    <div class="project-main-wrapper">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="blog-detail-card">
                        <?php if (!empty($post['image_url'])): ?>
                        <div class="blog-detail-image">
                            <img src="<?php echo $systemUrl . sanitizeOutput($post['image_url']); ?>" alt="<?php echo sanitizeOutput($post['title']); ?>" />
                        </div>
                        <?php else: ?>
                        <div class="blog-detail-image">
                            <div class="blog-detail-placeholder">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="blog-detail-body">
                            <div class="blog-detail-content">
                                <?php echo sanitizeWYSIWYG($post['content']); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="project-comments-section">
                        <div class="comments-section-header">
                            <div class="section-title text-start mb-0">
                                <span class="section-subtitle"><i class="fas fa-comments"></i> Discussion</span>
                                <h2>Commentaires <span class="comment-count-pill" id="commentCount"><?php echo countBlogComments($db, $post['slug']); ?></span></h2>
                                <div class="title-line" style="margin:0;"></div>
                            </div>
                            <div class="comments-sort-toggle">
                                <button class="sort-btn active" data-sort="newest"><i class="fas fa-arrow-down-short-wide"></i> Récents</button>
                                <button class="sort-btn" data-sort="oldest"><i class="fas fa-arrow-up-short-wide"></i> Anciens</button>
                            </div>
                        </div>

                        <?php if(!isUserLoggedIn()): ?>
                            <div class="comment-login-card">
                                <div class="comment-login-icon-wrap">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <h5>Rejoignez la conversation</h5>
                                <p>Connectez-vous pour commenter, liker et répondre</p>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="<?php echo $systemUrl; ?>login?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn btn-primary-custom">
                                        <i class="fas fa-sign-in-alt"></i> Se connecter
                                    </a>
                                    <a href="<?php echo $systemUrl; ?>register?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn btn-outline-primary-custom">
                                        <i class="fas fa-user-plus"></i> S'inscrire
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="comment-composer-card">
                                <div class="composer-header">
                                    <div class="comment-avatar" data-initial="<?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>">
                                        <?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>
                                    </div>
                                    <div class="composer-user-info">
                                        <span class="composer-username"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Utilisateur'); ?></span>
                                        <span class="composer-meta-date"><i class="fas fa-circle"></i> En ligne</span>
                                    </div>
                                </div>
                                <form id="commentForm" data-parent-id="">
                                    <div class="composer-body">
                                        <textarea class="composer-textarea" id="commentContent" rows="3" 
                                                  placeholder="Partagez votre avis sur cet article..." maxlength="1000" required></textarea>
                                        <div class="composer-footer">
                                            <div class="composer-meta-left">
                                                <div class="char-progress-ring">
                                                    <svg viewBox="0 0 24 24">
                                                        <circle class="ring-bg" cx="12" cy="12" r="10" />
                                                        <circle class="ring-fill" id="charRing" cx="12" cy="12" r="10" />
                                                    </svg>
                                                    <span class="char-count-num" id="charCount">0</span>
                                                </div>
                                                <span class="composer-hint"><i class="fas fa-globe"></i> Public</span>
                                            </div>
                                            <div class="composer-actions">
                                                <button type="button" class="btn-composer-cancel d-none" id="cancelReply">Annuler</button>
                                                <button type="submit" class="btn btn-primary-custom btn-sm" id="submitBtn">
                                                    <i class="fas fa-paper-plane"></i> Publier
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>

                        <div class="comment-toast-container" id="toastContainer"></div>

                        <div id="commentsList">
                            <?php
                            $currentUserId = isUserLoggedIn() ? $_SESSION['user_id'] : null;
                            $comments = getBlogComments($db, $post['slug'], $currentUserId);
                            if (empty($comments)): ?>
                                <div class="comment-empty-state">
                                    <div class="comment-empty-icon">
                                        <i class="far fa-comment-dots"></i>
                                    </div>
                                    <h5>Pas encore de commentaires</h5>
                                    <p>Soyez la première voix dans cette discussion !</p>
                                </div>
                            <?php else: ?>
                                <?php 
                                function renderComment($comment, $isReply = false, $currentUserId = null, $isLoggedIn = false) {
                                    $initial = strtoupper(substr($comment['username'], 0, 1));
                                    $isOwn = $currentUserId && $currentUserId == $comment['user_id'];
                                    $likedClass = $comment['user_liked'] ? ' liked' : '';
                                ?>
                                <div class="comment-card <?php echo $isReply ? 'comment-card--reply' : ''; ?>" 
                                     data-comment-id="<?php echo $comment['id']; ?>"
                                     data-timestamp="<?php echo strtotime($comment['created_at']); ?>">
                                    <div class="comment-card-content">
                                        <div class="comment-card-top">
                                            <div class="comment-card-author">
                                                <div class="comment-avatar comment-avatar--sm" data-initial="<?php echo $initial; ?>">
                                                    <?php echo $initial; ?>
                                                </div>
                                                <div class="comment-author-info">
                                                    <span class="comment-author-name">
                                                        <?php echo htmlspecialchars($comment['username']); ?>
                                                        <?php if ($isOwn): ?>
                                                        <span class="comment-badge-own">vous</span>
                                                        <?php endif; ?>
                                                    </span>
                                                    <span class="comment-date" data-time="<?php echo $comment['created_at']; ?>"><?php echo $comment['created_at']; ?></span>
                                                </div>
                                            </div>
                                            <?php if ($isOwn): ?>
                                            <div class="comment-card-menu dropdown">
                                                <button class="comment-menu-trigger" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><button class="dropdown-item edit-comment" data-comment-id="<?php echo $comment['id']; ?>"><i class="fas fa-pen"></i> Modifier</button></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><button class="dropdown-item text-danger delete-comment" data-comment-id="<?php echo $comment['id']; ?>"><i class="fas fa-trash-alt"></i> Supprimer</button></li>
                                                </ul>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="comment-card-body">
                                            <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                            <?php if (!empty($comment['updated_at'])): ?>
                                            <span class="comment-edited-badge"><i class="fas fa-pen"></i> modifié</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="comment-card-footer">
                                            <button class="comment-action like-action<?php echo $likedClass; ?>" data-comment-id="<?php echo $comment['id']; ?>">
                                                <i class="<?php echo $comment['user_liked'] ? 'fas' : 'far'; ?> fa-heart"></i>
                                                <span class="like-count"><?php echo $comment['likes_count'] > 0 ? $comment['likes_count'] : ''; ?></span>
                                            </button>
                                            <?php if ($isLoggedIn && !$isReply): ?>
                                            <button class="comment-action reply-action" data-comment-id="<?php echo $comment['id']; ?>" data-username="<?php echo htmlspecialchars($comment['username']); ?>">
                                                <i class="fas fa-reply"></i> Répondre
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if (!$isReply && !empty($comment['replies'])): ?>
                                    <div class="comment-replies-thread">
                                        <?php foreach ($comment['replies'] as $reply): ?>
                                            <?php renderComment($reply, true, $currentUserId, $isLoggedIn); ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php
                                }
                                $isLoggedIn = isUserLoggedIn();
                                foreach ($comments as $comment): 
                                    renderComment($comment, false, $currentUserId, $isLoggedIn);
                                endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Author card -->
                    <div class="project-sidebar-card">
                        <h4><i class="fas fa-user-pen"></i> Auteur</h4>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="comment-avatar" style="width:48px;height:48px;font-size:20px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <strong style="color:var(--text-primary);"><?php echo sanitizeOutput($profile['name'] ?? 'Donatien KANANE'); ?></strong>
                                <div style="font-size:13px;color:var(--text-secondary);"><?php echo sanitizeOutput($profile['title'] ?? 'Développeur Full-Stack'); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Info card -->
                    <div class="project-sidebar-card">
                        <h4><i class="fas fa-info-circle"></i> Informations</h4>
                        <div class="sidebar-info-list">
                            <div class="sidebar-info-item">
                                <span class="sidebar-info-label"><i class="fas fa-tag"></i> Catégorie</span>
                                <span class="sidebar-info-value"><?php echo sanitizeOutput($post['category']); ?></span>
                            </div>
                            <div class="sidebar-info-item">
                                <span class="sidebar-info-label"><i class="far fa-calendar-alt"></i> Publié le</span>
                                <span class="sidebar-info-value"><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></span>
                            </div>
                            <div class="sidebar-info-item">
                                <span class="sidebar-info-label"><i class="far fa-clock"></i> Temps de lecture</span>
                                <span class="sidebar-info-value"><?php echo max(1, round(str_word_count($post['content']) / 200)); ?> min</span>
                            </div>
                        </div>
                    </div>

                    <!-- Share card -->
                    <div class="project-sidebar-card">
                        <h4><i class="fas fa-share-nodes"></i> Partager</h4>
                        <div class="sidebar-share-links">
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($systemUrl . 'blog/' . $post['slug']); ?>" target="_blank" rel="noopener noreferrer" class="share-btn linkedin" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                            <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($post['title']); ?>&url=<?php echo urlencode($systemUrl . 'blog/' . $post['slug']); ?>" target="_blank" rel="noopener noreferrer" class="share-btn twitter" title="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                            <a href="mailto:?subject=<?php echo rawurlencode($post['title']); ?>&body=<?php echo rawurlencode($post['excerpt'] . "\n\n" . $systemUrl . 'blog/' . $post['slug']); ?>" class="share-btn email" title="Email"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </main>

    
    <script>
    $(document).ready(function() {
        const BLOG_SLUG = <?php echo json_encode($post['slug']); ?>;
        const MAX_CHARS = 1000;
        const RING_CIRCUMFERENCE = 2 * Math.PI * 10;
        const IS_LOGGED_IN = <?php echo isUserLoggedIn() ? 'true' : 'false'; ?>;
        const SYSTEM_URL = <?php echo json_encode($systemUrl); ?>;

        function timeAgo(dateStr) {
            const now = new Date();
            const date = new Date(dateStr.replace(' ', 'T'));
            const seconds = Math.floor((now - date) / 1000);
            if (seconds < 60) return "à l'instant";
            const minutes = Math.floor(seconds / 60);
            if (minutes < 60) return `il y a ${minutes} min`;
            const hours = Math.floor(minutes / 60);
            if (hours < 24) return `il y a ${hours}h`;
            const days = Math.floor(hours / 24);
            if (days < 7) return `il y a ${days}j`;
            if (days < 30) return `il y a ${Math.floor(days / 7)} sem`;
            if (days < 365) return `il y a ${Math.floor(days / 30)} mois`;
            return `il y a ${Math.floor(days / 365)} an(s)`;
        }
        $('.comment-date[data-time]').each(function() { $(this).text(timeAgo($(this).data('time'))); });
        setInterval(function() { $('.comment-date[data-time]').each(function() { $(this).text(timeAgo($(this).data('time'))); }); }, 60000);

        function updateCharRing(textarea) {
            const len = textarea.val().length;
            const ring = $('#charRing');
            const counter = $('#charCount');
            const ratio = len / MAX_CHARS;
            const offset = RING_CIRCUMFERENCE * (1 - ratio);
            ring.css('stroke-dashoffset', offset);
            counter.text(len);
            ring.removeClass('warn danger');
            counter.removeClass('warn danger');
            if (ratio >= 1) {
                ring.addClass('danger'); counter.addClass('danger');
                textarea.val(textarea.val().substring(0, MAX_CHARS));
                counter.text(MAX_CHARS);
                ring.css('stroke-dashoffset', 0);
            } else if (ratio >= 0.8) {
                ring.addClass('warn'); counter.addClass('warn');
            }
        }
        $(document).on('input', '.composer-textarea', function() { updateCharRing($(this)); });

        function showToast(type, message) {
            const icons = { success: 'check-circle', error: 'times-circle', warning: 'exclamation-triangle', info: 'info-circle' };
            const toast = $(`
                <div class="comment-toast toast-${type}">
                    <div class="toast-icon"><i class="fas fa-${icons[type] || icons.info}"></i></div>
                    <div class="toast-msg">${$('<span>').text(message).html()}</div>
                    <button class="toast-close"><i class="fas fa-times"></i></button>
                </div>
            `).hide();
            $('#toastContainer').append(toast);
            toast.slideDown(300);
            toast.find('.toast-close').on('click', function() { toast.slideUp(200, function() { toast.remove(); }); });
            setTimeout(() => toast.slideUp(300, function() { toast.remove(); }), 4000);
        }

        function updateCommentCount() {
            const count = $('.comment-card').length;
            const badge = $('#commentCount');
            badge.text(count);
            badge.addClass('count-bump');
            setTimeout(() => badge.removeClass('count-bump'), 300);
        }

        function escapeHtml(text) { return $('<div>').text(text).html(); }

        function buildCommentHtml(c, isReply) {
            const initial = c.username.charAt(0).toUpperCase();
            const time = timeAgo(c.created_at);
            const replyClass = isReply ? 'comment-card--reply' : '';
            return `
            <div class="comment-card ${replyClass} comment-enter" data-comment-id="${c.id}" data-timestamp="${Math.floor(new Date(c.created_at.replace(' ','T')).getTime()/1000)}">
                <div class="comment-card-content">
                    <div class="comment-card-top">
                        <div class="comment-card-author">
                            <div class="comment-avatar comment-avatar--sm" data-initial="${initial}">${initial}</div>
                            <div class="comment-author-info">
                                <span class="comment-author-name">
                                    ${escapeHtml(c.username)}
                                    <span class="comment-badge-own">vous</span>
                                </span>
                                <span class="comment-date" data-time="${c.created_at}">${time}</span>
                            </div>
                        </div>
                        <div class="comment-card-menu dropdown">
                            <button class="comment-menu-trigger" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item edit-comment" data-comment-id="${c.id}"><i class="fas fa-pen"></i> Modifier</button></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><button class="dropdown-item text-danger delete-comment" data-comment-id="${c.id}"><i class="fas fa-trash-alt"></i> Supprimer</button></li>
                            </ul>
                        </div>
                    </div>
                    <div class="comment-card-body">${escapeHtml(c.content).replace(/\n/g, '<br>')}</div>
                    <div class="comment-card-footer">
                        <button class="comment-action like-action" data-comment-id="${c.id}">
                            <i class="far fa-heart"></i> <span class="like-count"></span>
                        </button>
                        ${(!isReply && IS_LOGGED_IN) ? `<button class="comment-action reply-action" data-comment-id="${c.id}" data-username="${escapeHtml(c.username)}"><i class="fas fa-reply"></i> Répondre</button>` : ''}
                    </div>
                </div>
            </div>`;
        }

        // Submit
        $(document).on('submit', '#commentForm, .reply-form', function(e) {
            e.preventDefault();
            const form = $(this);
            const isReply = form.hasClass('reply-form');
            const textarea = form.find('textarea');
            const content = textarea.val().trim();
            const parentId = isReply ? form.data('parent-id') : (form.data('parent-id') || '');
            const submitBtn = form.find('[type="submit"]');
            if (!content) { showToast('warning', 'Veuillez entrer un commentaire'); return; }
            submitBtn.prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i> Envoi...');
            $.ajax({
                url: SYSTEM_URL + 'api.php?action=comment',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ blog_slug: BLOG_SLUG, content: content, parent_id: parentId || null }),
                success: function(response) {
                    if (response.success) {
                        const c = response.comment;
                        const html = buildCommentHtml(c, !!parentId);
                        $('#commentsList .comment-empty-state').remove();
                        if (parentId) {
                            const parentCard = $(`.comment-card[data-comment-id="${parentId}"]`);
                            let repliesDiv = parentCard.children('.comment-replies-thread');
                            if (!repliesDiv.length) {
                                parentCard.append('<div class="comment-replies-thread"></div>');
                                repliesDiv = parentCard.children('.comment-replies-thread');
                            }
                            repliesDiv.append(html);
                            form.closest('.inline-reply-wrap').slideUp(200, function() { $(this).remove(); });
                        } else {
                            $('#commentsList').prepend(html);
                            textarea.val('');
                            updateCharRing(textarea);
                        }
                        updateCommentCount();
                        showToast('success', parentId ? 'Réponse publiée !' : 'Commentaire publié !');
                        const newCard = $(`.comment-card[data-comment-id="${c.id}"]`);
                        newCard.find('[data-bs-toggle="dropdown"]').each(function() { new bootstrap.Dropdown(this); });
                        setTimeout(() => newCard.removeClass('comment-enter'), 50);
                    }
                },
                error: function(xhr) { showToast('error', xhr.responseJSON?.error || 'Une erreur est survenue'); },
                complete: function() { submitBtn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Publier'); }
            });
        });

        // Reply
        $(document).on('click', '.reply-action', function() {
            const commentId = $(this).data('comment-id');
            const username = $(this).data('username');
            const parentCard = $(`.comment-card[data-comment-id="${commentId}"]`);
            parentCard.find('.inline-reply-wrap').remove();
            const replyFormHtml = `
            <div class="inline-reply-wrap" style="display:none;">
                <form class="reply-form" data-parent-id="${commentId}">
                    <div class="inline-reply-composer">
                        <textarea class="composer-textarea" rows="2" placeholder="Répondre à @${escapeHtml(username)}..." maxlength="${MAX_CHARS}" required></textarea>
                        <div class="inline-reply-actions">
                            <button type="button" class="btn-composer-cancel cancel-inline-reply">Annuler</button>
                            <button type="submit" class="btn btn-primary-custom btn-sm"><i class="fas fa-paper-plane"></i> Répondre</button>
                        </div>
                    </div>
                </form>
            </div>`;
            parentCard.children('.comment-card-content').after(replyFormHtml);
            parentCard.find('.inline-reply-wrap').slideDown(200);
            parentCard.find('.inline-reply-wrap textarea').focus();
        });
        $(document).on('click', '.cancel-inline-reply', function() {
            $(this).closest('.inline-reply-wrap').slideUp(200, function() { $(this).remove(); });
        });

        // Like
        $(document).on('click', '.like-action', function() {
            if (!IS_LOGGED_IN) { showToast('info', 'Connectez-vous pour liker'); return; }
            const btn = $(this);
            const commentId = btn.data('comment-id');
            btn.addClass('like-pulse');
            $.ajax({
                url: SYSTEM_URL + 'api.php?action=like',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ comment_id: commentId }),
                success: function(res) {
                    if (res.success) {
                        btn.toggleClass('liked', res.liked);
                        btn.find('i').toggleClass('fas', res.liked).toggleClass('far', !res.liked);
                        btn.find('.like-count').text(res.count > 0 ? res.count : '');
                    }
                },
                error: function() { showToast('error', 'Impossible de liker'); },
                complete: function() { setTimeout(() => btn.removeClass('like-pulse'), 400); }
            });
        });

        // Edit
        $(document).on('click', '.edit-comment', function() {
            const commentId = $(this).data('comment-id');
            const card = $(`.comment-card[data-comment-id="${commentId}"]`);
            const body = card.find('.comment-card-body').first();
            if (card.find('.comment-edit-form').length) return;
            const clone = body.clone();
            clone.find('.comment-edited-badge').remove();
            const rawText = clone.html().replace(/<br\s*\/?>/gi, '\n').replace(/&amp;/g,'&').replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&quot;/g,'"').replace(/&#039;/g,"'").trim();
            const editForm = $(`
                <form class="comment-edit-form">
                    <textarea class="composer-textarea" rows="3" maxlength="${MAX_CHARS}" required>${escapeHtml(rawText)}</textarea>
                    <div class="inline-reply-actions" style="margin-top:10px">
                        <button type="button" class="btn-composer-cancel cancel-edit">Annuler</button>
                        <button type="submit" class="btn btn-primary-custom btn-sm"><i class="fas fa-check"></i> Enregistrer</button>
                    </div>
                </form>
            `);
            body.hide().after(editForm);
            editForm.find('textarea').focus();
        });
        $(document).on('click', '.cancel-edit', function() {
            const form = $(this).closest('.comment-edit-form');
            form.prev('.comment-card-body').show();
            form.remove();
        });
        $(document).on('submit', '.comment-edit-form', function(e) {
            e.preventDefault();
            const form = $(this);
            const card = form.closest('.comment-card');
            const commentId = card.data('comment-id');
            const content = form.find('textarea').val().trim();
            const submitBtn = form.find('[type="submit"]');
            if (!content) { showToast('warning', 'Le commentaire ne peut pas être vide'); return; }
            submitBtn.prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i>');
            $.ajax({
                url: SYSTEM_URL + 'api.php?action=edit-comment',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ comment_id: commentId, content: content }),
                success: function(res) {
                    if (res.success) {
                        const body = form.prev('.comment-card-body');
                        body.html(escapeHtml(res.content).replace(/\n/g, '<br>') + ' <span class="comment-edited-badge"><i class="fas fa-pen"></i> modifié</span>');
                        body.show();
                        form.remove();
                        showToast('success', 'Commentaire modifié');
                    }
                },
                error: function(xhr) {
                    showToast('error', xhr.responseJSON?.error || 'Erreur lors de la modification');
                    submitBtn.prop('disabled', false).html('<i class="fas fa-check"></i> Enregistrer');
                }
            });
        });

        // Delete
        let deleteTarget = null;
        $(document).on('click', '.delete-comment', function() {
            deleteTarget = $(this).data('comment-id');
            $('#deleteModal').addClass('active');
        });
        $(document).on('click', '#deleteCancel, .delete-modal-overlay', function() {
            $('#deleteModal').removeClass('active');
            deleteTarget = null;
        });
        $(document).on('click', '#deleteConfirm', function() {
            if (!deleteTarget) return;
            const modal = $('#deleteModal');
            const commentCard = $(`.comment-card[data-comment-id="${deleteTarget}"]`);
            $.ajax({
                url: SYSTEM_URL + 'api.php?action=delete-comment',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ comment_id: deleteTarget }),
                success: function(res) {
                    if (res.success) {
                        commentCard.addClass('comment-exit');
                        setTimeout(() => {
                            commentCard.remove();
                            updateCommentCount();
                            if ($('.comment-card').length === 0) {
                                $('#commentsList').html(`
                                    <div class="comment-empty-state">
                                        <div class="comment-empty-icon"><i class="far fa-comment-dots"></i></div>
                                        <h5>Pas encore de commentaires</h5>
                                        <p>Soyez la première voix dans cette discussion !</p>
                                    </div>
                                `);
                            }
                        }, 400);
                        showToast('success', 'Commentaire supprimé');
                    }
                },
                error: function(xhr) { showToast('error', xhr.responseJSON?.error || 'Erreur lors de la suppression'); },
                complete: function() { modal.removeClass('active'); deleteTarget = null; }
            });
        });

        // Sort
        $(document).on('click', '.sort-btn', function() {
            const sort = $(this).data('sort');
            $('.sort-btn').removeClass('active');
            $(this).addClass('active');
            const list = $('#commentsList');
            const cards = list.children('.comment-card').get();
            cards.sort((a, b) => {
                const ta = parseInt($(a).data('timestamp'));
                const tb = parseInt($(b).data('timestamp'));
                return sort === 'newest' ? tb - ta : ta - tb;
            });
            $.each(cards, function(_, card) { list.append(card); });
        });
    });
    </script>

    <!-- Delete Confirmation Modal -->
    <div class="delete-modal" id="deleteModal">
        <div class="delete-modal-overlay"></div>
        <div class="delete-modal-content">
            <div class="delete-modal-icon"><i class="fas fa-trash-alt"></i></div>
            <h5>Supprimer ce commentaire ?</h5>
            <p>Cette action est irréversible.</p>
            <div class="delete-modal-actions">
                <button class="btn-modal-cancel" id="deleteCancel">Annuler</button>
                <button class="btn-modal-delete" id="deleteConfirm"><i class="fas fa-trash-alt"></i> Supprimer</button>
            </div>
        </div>
    </div>
</body>
</html>
