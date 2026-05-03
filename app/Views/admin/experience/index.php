<div class="admin-experience">
    <div class="section-header">
        <h1>Expériences</h1>
        <a href="<?php echo SYS_URL; ?>admin/experience/create" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Ajouter une expérience
        </a>
    </div>

    <?php if (empty($experiences)): ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Aucune expérience</h3>
            <p>Commencez par ajouter vos premières expériences professionnelles.</p>
            <a href="<?php echo SYS_URL; ?>admin/experience/create" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Ajouter une expérience
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Entreprise</th>
                        <th>Période</th>
                        <th>Actuel</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($experiences as $exp): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($exp['title']); ?></strong>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($exp['company']); ?>
                            </td>
                            <td>
                                <small>
                                    <?php echo date('d/m/Y', strtotime($exp['start_date'])); ?> 
                                    → 
                                    <?php echo $exp['end_date'] ? date('d/m/Y', strtotime($exp['end_date'])) : 'Maintenant'; ?>
                                </small>
                            </td>
                            <td>
                                <?php if ($exp['is_current']): ?>
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Oui</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Non</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a href="<?php echo SYS_URL; ?>admin/experience/edit/<?php echo $exp['id']; ?>" class="btn btn-sm btn-outline" title="Éditer">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="<?php echo SYS_URL; ?>admin/experience/delete/<?php echo $exp['id']; ?>" class="inline-form" onsubmit="return confirm('Êtes-vous sûr ?')">
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
