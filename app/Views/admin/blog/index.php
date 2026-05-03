<div class="admin-blog">
    <div class="section-header">
        <h1>Articles de Blog</h1>
        <a href="<?php echo SYS_URL; ?>admin/blog/create" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Créer un article
        </a>
    </div>

    <?php if (empty($posts)): ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Aucun article</h3>
            <p>Commencez par créer votre premier article de blog.</p>
            <a href="<?php echo SYS_URL; ?>admin/blog/create" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Créer un article
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Slug</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($post['title']); ?></strong>
                            </td>
                            <td>
                                <code><?php echo htmlspecialchars($post['slug']); ?></code>
                            </td>
                            <td>
                                <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?>
                            </td>
                            <td class="actions">
                                <a href="<?php echo SYS_URL; ?>admin/blog/edit/<?php echo $post['id']; ?>" class="btn btn-sm btn-outline" title="Éditer">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="<?php echo SYS_URL; ?>admin/blog/delete/<?php echo $post['id']; ?>" class="inline-form" onsubmit="return confirm('Êtes-vous sûr ?')">
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
