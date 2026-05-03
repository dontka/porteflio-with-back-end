<div class="admin-logs">
    <div class="section-header">
        <h1>Logs d'Administration</h1>
    </div>

    <?php if (empty($logs)): ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Aucun log</h3>
            <p>Les logs des actions administrateur s'afficheront ici.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="admin-table logs-table">
                <thead>
                    <tr>
                        <th>Administrateur</th>
                        <th>Action</th>
                        <th>Détails</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($log['username'] ?? 'Système'); ?></strong>
                            </td>
                            <td>
                                <span class="action-badge action-<?php echo htmlspecialchars($log['action']); ?>">
                                    <?php 
                                        $actions = [
                                            'create_blog' => 'Créer Article',
                                            'update_blog' => 'Modifier Article',
                                            'delete_blog' => 'Supprimer Article',
                                            'create_project' => 'Créer Projet',
                                            'update_project' => 'Modifier Projet',
                                            'delete_project' => 'Supprimer Projet',
                                            'create_skill' => 'Créer Compétence',
                                            'update_skill' => 'Modifier Compétence',
                                            'delete_skill' => 'Supprimer Compétence',
                                            'create_experience' => 'Créer Expérience',
                                            'update_experience' => 'Modifier Expérience',
                                            'delete_experience' => 'Supprimer Expérience',
                                            'promote_user' => 'Promouvoir Utilisateur',
                                            'revoke_admin' => 'Retirer Droits Admin',
                                        ];
                                        echo $actions[$log['action']] ?? htmlspecialchars($log['action']);
                                    ?>
                                </span>
                            </td>
                            <td>
                                <small><?php echo htmlspecialchars($log['details']); ?></small>
                            </td>
                            <td>
                                <small><?php echo date('d/m/Y H:i:s', strtotime($log['created_at'])); ?></small>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
