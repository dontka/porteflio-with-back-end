<div class="admin-projects">
    <div class="section-header">
        <h1>Projets</h1>
        <a href="<?php echo SYS_URL; ?>admin/projects/create" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Créer un projet
        </a>
    </div>

    <?php if (empty($projects)): ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Aucun projet</h3>
            <p>Commencez par créer votre premier projet.</p>
            <a href="<?php echo SYS_URL; ?>admin/projects/create" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Créer un projet
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Technos</th>
                        <th>Vedette</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $project): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($project['title']); ?></strong>
                            </td>
                            <td>
                                <small><?php echo htmlspecialchars(substr($project['technologies'], 0, 50)); ?></small>
                            </td>
                            <td>
                                <?php if ($project['is_featured']): ?>
                                    <span class="badge badge-success"><i class="fas fa-star"></i> Oui</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Non</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo date('d/m/Y', strtotime($project['created_at'])); ?>
                            </td>
                            <td class="actions">
                                <a href="<?php echo SYS_URL; ?>admin/projects/edit/<?php echo $project['id']; ?>" class="btn btn-sm btn-outline" title="Éditer">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="<?php echo SYS_URL; ?>admin/projects/delete/<?php echo $project['id']; ?>" class="inline-form" onsubmit="return confirm('Êtes-vous sûr ?')">
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
