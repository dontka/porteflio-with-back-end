<div class="admin-skill-form">
    <div class="form-header">
        <h1><?php echo $isEdit ? 'Éditer la compétence' : 'Ajouter une compétence'; ?></h1>
    </div>

    <form method="POST" action="<?php echo $isEdit ? SYS_URL . 'admin/skills/update/' . $skill['id'] : SYS_URL . 'admin/skills/store'; ?>" class="admin-form">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="name">Nom *</label>
                <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($skill['name'] ?? ''); ?>" placeholder="PHP, JavaScript, etc.">
            </div>

            <div class="form-group">
                <label for="category">Catégorie *</label>
                <select id="category" name="category" required>
                    <option value="">-- Sélectionner --</option>
                    <option value="Backend" <?php echo ($skill['category'] ?? '') === 'Backend' ? 'selected' : ''; ?>>Backend</option>
                    <option value="Frontend" <?php echo ($skill['category'] ?? '') === 'Frontend' ? 'selected' : ''; ?>>Frontend</option>
                    <option value="Mobile" <?php echo ($skill['category'] ?? '') === 'Mobile' ? 'selected' : ''; ?>>Mobile</option>
                    <option value="DevOps" <?php echo ($skill['category'] ?? '') === 'DevOps' ? 'selected' : ''; ?>>DevOps</option>
                    <option value="Database" <?php echo ($skill['category'] ?? '') === 'Database' ? 'selected' : ''; ?>>Database</option>
                    <option value="Tools" <?php echo ($skill['category'] ?? '') === 'Tools' ? 'selected' : ''; ?>>Outils</option>
                    <option value="Autre" <?php echo ($skill['category'] ?? '') === 'Autre' ? 'selected' : ''; ?>>Autre</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="proficiency">Niveau de maîtrise (%)</label>
            <div class="slider-group">
                <input type="range" id="proficiency" name="proficiency" min="0" max="100" value="<?php echo $skill['proficiency'] ?? 50; ?>" class="slider">
                <span class="slider-value"><?php echo $skill['proficiency'] ?? 50; ?>%</span>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                <?php echo $isEdit ? 'Mettre à jour' : 'Ajouter'; ?>
            </button>
            <a href="<?php echo SYS_URL; ?>admin/skills" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
    document.getElementById('proficiency').addEventListener('input', function(e) {
        document.querySelector('.slider-value').textContent = e.target.value + '%';
    });
</script>
