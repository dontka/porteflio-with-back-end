<div class="admin-blog-form">
    <div class="form-header">
        <h1><?php echo $isEdit ? 'Éditer l\'article' : 'Créer un nouvel article'; ?></h1>
    </div>

    <form method="POST" action="<?php echo $isEdit ? SYS_URL . 'admin/blog/update/' . $post['id'] : SYS_URL . 'admin/blog/store'; ?>" class="admin-form">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="title">Titre *</label>
                <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>" placeholder="Titre de l'article">
            </div>

            <div class="form-group">
                <label for="slug">Slug *</label>
                <input type="text" id="slug" name="slug" required value="<?php echo htmlspecialchars($post['slug'] ?? ''); ?>" placeholder="mon-article" pattern="[a-z0-9_-]+">
                <small>Caractères autorisés: lettres minuscules, chiffres, tirets, underscores</small>
            </div>
        </div>

        <div class="form-group">
            <label for="excerpt">Résumé</label>
            <textarea id="excerpt" name="excerpt" rows="3" placeholder="Résumé de l'article..."><?php echo htmlspecialchars($post['excerpt'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="content">Contenu *</label>
            <textarea id="content" name="content" rows="12" required placeholder="Contenu de l'article..."><?php echo htmlspecialchars($post['content'] ?? ''); ?></textarea>
            <small>HTML autorisé: p, h1-h6, strong, em, a, ul, ol, li, blockquote, code</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                <?php echo $isEdit ? 'Mettre à jour' : 'Créer'; ?>
            </button>
            <a href="<?php echo SYS_URL; ?>admin/blog" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Annuler
            </a>
        </div>
    </form>
</div>
