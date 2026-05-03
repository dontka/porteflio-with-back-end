<div class="admin-dashboard">
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $stats['totalBlogPosts'] ?? 0; ?></h3>
                <p>Articles de Blog</p>
            </div>
            <a href="<?php echo SYS_URL; ?>admin/blog" class="stat-link">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%)">
                <i class="fas fa-project-diagram"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $stats['totalProjects'] ?? 0; ?></h3>
                <p>Projets</p>
            </div>
            <a href="<?php echo SYS_URL; ?>admin/projects" class="stat-link">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $stats['totalAdmins'] ?? 0; ?></h3>
                <p>Administrateurs</p>
            </div>
            <a href="<?php echo SYS_URL; ?>admin/users" class="stat-link">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $stats['totalUsers'] ?? 0; ?></h3>
                <p>Utilisateurs Totaux</p>
            </div>
            <a href="<?php echo SYS_URL; ?>admin/users" class="stat-link">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Recent Content -->
    <div class="dashboard-grid">
        <!-- Recent Blog Posts -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Articles Récents</h2>
                <a href="<?php echo SYS_URL; ?>admin/blog/create" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i>
                    Ajouter un article
                </a>
            </div>
            <div class="recent-list">
                <?php if (empty($recentBlogPosts)): ?>
                    <p class="empty-message">Aucun article pour le moment</p>
                <?php else: ?>
                    <?php foreach ($recentBlogPosts as $post): ?>
                        <div class="recent-item">
                            <h4><?php echo htmlspecialchars($post['title']); ?></h4>
                            <p class="meta">
                                <i class="fas fa-clock"></i>
                                <?php echo date('d/m/Y', strtotime($post['created_at'])); ?>
                            </p>
                            <a href="<?php echo SYS_URL; ?>admin/blog/edit/<?php echo $post['id']; ?>" class="btn btn-sm btn-outline">
                                <i class="fas fa-edit"></i>
                                Éditer
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Projects -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Projets en Vedette</h2>
                <a href="<?php echo SYS_URL; ?>admin/projects/create" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i>
                    Ajouter un projet
                </a>
            </div>
            <div class="recent-list">
                <?php if (empty($recentProjects)): ?>
                    <p class="empty-message">Aucun projet en vedette</p>
                <?php else: ?>
                    <?php foreach ($recentProjects as $project): ?>
                        <div class="recent-item">
                            <h4><?php echo htmlspecialchars($project['title']); ?></h4>
                            <p class="meta">
                                <i class="fas fa-code"></i>
                                <?php echo htmlspecialchars($project['technologies']); ?>
                            </p>
                            <a href="<?php echo SYS_URL; ?>admin/projects/edit/<?php echo $project['id']; ?>" class="btn btn-sm btn-outline">
                                <i class="fas fa-edit"></i>
                                Éditer
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
