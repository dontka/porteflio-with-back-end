<div class="admin-skills">
    <div class="section-header">
        <h1>Compétences</h1>
        <a href="<?php echo SYS_URL; ?>admin/skills/create" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Ajouter une compétence
        </a>
    </div>

    <?php if (empty($skills)): ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Aucune compétence</h3>
            <p>Commencez par ajouter vos premières compétences.</p>
            <a href="<?php echo SYS_URL; ?>admin/skills/create" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Ajouter une compétence
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Niveau</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($skills as $skill): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($skill['name']); ?></strong>
                            </td>
                            <td>
                                <span class="badge"><?php echo htmlspecialchars($skill['category']); ?></span>
                            </td>
                            <td>
                                <div class="progress-bar" style="width: 200px;">
                                    <div class="progress" style="width: <?php echo $skill['proficiency']; ?>%">
                                        <?php echo $skill['proficiency']; ?>%
                                    </div>
                                </div>
                            </td>
                            <td class="actions">
                                <a href="<?php echo SYS_URL; ?>admin/skills/edit/<?php echo $skill['id']; ?>" class="btn btn-sm btn-outline" title="Éditer">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="<?php echo SYS_URL; ?>admin/skills/delete/<?php echo $skill['id']; ?>" class="inline-form" onsubmit="return confirm('Êtes-vous sûr ?')">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
