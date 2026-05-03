<div class="admin-users">
    <div class="section-header">
        <h1>Utilisateurs</h1>
    </div>

    <div class="tabs">
        <button class="tab-btn active" data-tab="all-users">
            <i class="fas fa-users"></i>
            Tous les Utilisateurs (<?php echo count($users); ?>)
        </button>
        <button class="tab-btn" data-tab="admins">
            <i class="fas fa-crown"></i>
            Administrateurs (<?php echo count($admins); ?>)
        </button>
    </div>

    <!-- All Users Tab -->
    <div class="tab-content active" id="all-users">
        <?php if (empty($users)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>Aucun utilisateur</h3>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nom d'utilisateur</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Date d'inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                                </td>
                                <td>
                                    <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>">
                                        <?php echo htmlspecialchars($user['email']); ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if ($user['is_admin']): ?>
                                        <span class="badge badge-warning"><i class="fas fa-crown"></i> Admin</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Utilisateur</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y', strtotime($user['created_at'])); ?>
                                </td>
                                <td class="actions">
                                    <?php if (!$user['is_admin']): ?>
                                        <form method="POST" action="<?php echo SYS_URL; ?>admin/promote-user" class="inline-form">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-success" title="Promouvoir en admin">
                                                <i class="fas fa-arrow-up"></i>
                                            </button>
                                        </form>
                                    <?php elseif ($user['id'] != $currentUser['id']): ?>
                                        <form method="POST" action="<?php echo SYS_URL; ?>admin/revoke-user" class="inline-form" onsubmit="return confirm('Retirer les droits admin ?')">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-warning" title="Retirer les droits admin">
                                                <i class="fas fa-arrow-down"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Admins Tab -->
    <div class="tab-content" id="admins">
        <?php if (empty($admins)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>Aucun administrateur</h3>
            </div>
        <?php else: ?>
            <div class="admin-grid">
                <?php foreach ($admins as $admin): ?>
                    <div class="admin-card">
                        <div class="card-header">
                            <h3><?php echo htmlspecialchars($admin['username']); ?></h3>
                            <span class="badge badge-warning"><i class="fas fa-crown"></i> Admin</span>
                        </div>
                        <div class="card-body">
                            <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($admin['email']); ?>"><?php echo htmlspecialchars($admin['email']); ?></a></p>
                            <p><strong>Depuis:</strong> <?php echo date('d/m/Y', strtotime($admin['created_at'])); ?></p>
                        </div>
                        <div class="card-footer">
                            <?php if ($admin['id'] != $currentUser['id']): ?>
                                <form method="POST" action="<?php echo SYS_URL; ?>admin/revoke-user" class="inline-form" onsubmit="return confirm('Retirer les droits admin ?')">
                                    <input type="hidden" name="user_id" value="<?php echo $admin['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-remove"></i>
                                        Retirer les droits
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">C'est vous</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.dataset.tab;
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(tab).classList.add('active');
        });
    });
</script>
