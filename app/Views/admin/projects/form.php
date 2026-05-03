<div class="admin-project-form">
    <div class="form-header">
        <h1><?php echo $isEdit ? 'Éditer le projet' : 'Créer un nouveau projet'; ?></h1>
    </div>

    <form method="POST" action="<?php echo $isEdit ? SYS_URL . 'admin/projects/update/' . $project['id'] : SYS_URL . 'admin/projects/store'; ?>" enctype="multipart/form-data" class="admin-form">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="title">Titre *</label>
                <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($project['title'] ?? ''); ?>" placeholder="Titre du projet">
            </div>

            <div class="form-group">
                <label for="slug">Slug *</label>
                <input type="text" id="slug" name="slug" required value="<?php echo htmlspecialchars($project['slug'] ?? ''); ?>" placeholder="mon-projet" pattern="[a-z0-9_-]+">
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description Courte *</label>
            <textarea id="description" name="description" rows="3" required placeholder="Description courte du projet..."><?php echo htmlspecialchars($project['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="content">Contenu Détaillé</label>
            <textarea id="content" name="content" rows="10" placeholder="Description détaillée du projet..."><?php echo htmlspecialchars($project['content'] ?? ''); ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="technologies">Technologies</label>
                <input type="text" id="technologies" name="technologies" value="<?php echo htmlspecialchars($project['technologies'] ?? ''); ?>" placeholder="PHP, MySQL, JavaScript">
            </div>

            <div class="form-group">
                <label for="link">Lien du Projet</label>
                <input type="url" id="link" name="link" value="<?php echo htmlspecialchars($project['link'] ?? ''); ?>" placeholder="https://...">
            </div>
        </div>

        <div class="form-group">
            <label for="github">Lien GitHub</label>
            <input type="url" id="github" name="github" value="<?php echo htmlspecialchars($project['github'] ?? ''); ?>" placeholder="https://github.com/...">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="thumbnail">Image Principale</label>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png,image/webp">
                <?php if ($isEdit && isset($project['thumbnail'])): ?>
                    <small>Image actuelle: <code><?php echo htmlspecialchars($project['thumbnail']); ?></code></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="is_featured">
                    <input type="checkbox" id="is_featured" name="is_featured" <?php echo (($project['is_featured'] ?? 0) == 1) ? 'checked' : ''; ?>>
                    Afficher en vedette
                </label>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                <?php echo $isEdit ? 'Mettre à jour' : 'Créer'; ?>
            </button>
            <a href="<?php echo SYS_URL; ?>admin/projects" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Annuler
            </a>
        </div>
    </form>
</div>
