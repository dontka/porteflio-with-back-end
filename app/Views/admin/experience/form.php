<div class="admin-experience-form">
    <div class="form-header">
        <h1><?php echo $isEdit ? 'Éditer l\'expérience' : 'Ajouter une expérience'; ?></h1>
    </div>

    <form method="POST" action="<?php echo $isEdit ? SYS_URL . 'admin/experience/update/' . $experience['id'] : SYS_URL . 'admin/experience/store'; ?>" class="admin-form">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="title">Titre du Poste *</label>
                <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($experience['title'] ?? ''); ?>" placeholder="Développeur PHP">
            </div>

            <div class="form-group">
                <label for="company">Entreprise *</label>
                <input type="text" id="company" name="company" required value="<?php echo htmlspecialchars($experience['company'] ?? ''); ?>" placeholder="Nom de l'entreprise">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="start_date">Date de Début *</label>
                <input type="date" id="start_date" name="start_date" required value="<?php echo htmlspecialchars($experience['start_date'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="end_date">Date de Fin</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($experience['end_date'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="6" placeholder="Descriptions des responsabilités et accomplissements..."><?php echo htmlspecialchars($experience['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="is_current">
                <input type="checkbox" id="is_current" name="is_current" <?php echo (($experience['is_current'] ?? 0) == 1) ? 'checked' : ''; ?>>
                Je travaille actuellement ici
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                <?php echo $isEdit ? 'Mettre à jour' : 'Ajouter'; ?>
            </button>
            <a href="<?php echo SYS_URL; ?>admin/experience" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Annuler
            </a>
        </div>
    </form>
</div>
