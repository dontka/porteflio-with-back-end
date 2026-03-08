<!-- ===== SKILLS ===== -->
<section class="section-block skills-section" id="skills">
    <!-- SVG gradient definition (hidden) -->
    <svg class="skill-svg-defs" aria-hidden="true">
        <defs>
            <linearGradient id="skillGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#6366f1" />
                <stop offset="100%" stop-color="#06b6d4" />
            </linearGradient>
        </defs>
    </svg>
    <div class="container">
        <div class="section-title text-center">
            <span class="section-subtitle">Mon expertise</span>
            <h2>Compétences Techniques</h2>
            <div class="title-line"></div>
        </div>

        <?php
        // Group skills by category
        $skillsByCategory = [];
        $categoryIcons = [
            'Front-end' => 'fa-palette',
            'Back-end'  => 'fa-server',
            'Outils'    => 'fa-toolbox',
        ];
        $categoryColors = [
            'Front-end' => '#6366f1',
            'Back-end'  => '#06b6d4',
            'Outils'    => '#f59e0b',
        ];
        
        if (!empty($skills) && is_array($skills)):
            foreach ($skills as $sk) {
                $cat = $sk['category'] ?? 'Autres';
                $skillsByCategory[$cat][] = $sk;
            }
        endif;
        
        $avgLevel = count($skillsByCategory) > 0 && !empty($skills) ? round(array_sum(array_column($skills, 'level')) / count($skills)) : 0;
        ?>

        <!-- Overall stat -->
        <div class="skills-overview">
            <div class="skills-overview-ring">
                <svg viewBox="0 0 120 120">
                    <circle class="skills-overview-bg" cx="60" cy="60" r="54" />
                    <circle class="skills-overview-fill" cx="60" cy="60" r="54" data-percent="<?php echo $avgLevel; ?>" />
                </svg>
                <div class="skills-overview-text">
                    <span class="skills-overview-number"><?php echo $avgLevel; ?></span>
                    <span class="skills-overview-label">score moyen</span>
                </div>
            </div>
            <div class="skills-overview-info">
                <h3><?php echo $skillCount; ?> technologies maîtrisées</h3>
                <p>Réparties en <?php echo count($skillsByCategory); ?> domaines d'expertise avec un score moyen de <strong><?php echo $avgLevel; ?>%</strong></p>
            </div>
        </div>

        <!-- Category filter tabs -->
        <div class="skills-filter">
            <button class="skills-filter-btn active" data-filter="all"><i class="fas fa-th"></i> Toutes</button>
            <?php foreach ($skillsByCategory as $catName => $catSkills): ?>
            <button class="skills-filter-btn" data-filter="<?php echo strtolower(str_replace([' ', '-'], '', $catName)); ?>">
                <i class="fas <?php echo $categoryIcons[$catName] ?? 'fa-cube'; ?>"></i> <?php echo sanitizeOutput($catName); ?>
                <span class="skills-filter-count"><?php echo count($catSkills); ?></span>
            </button>
            <?php endforeach; ?>
        </div>

        <!-- Skills grid by category -->
        <?php foreach ($skillsByCategory as $catName => $catSkills): ?>
        <div class="skills-category-group" data-category="<?php echo strtolower(str_replace([' ', '-'], '', $catName)); ?>">
            <div class="skills-category-header">
                <span class="skills-category-icon" style="--cat-color: <?php echo $categoryColors[$catName] ?? '#6366f1'; ?>">
                    <i class="fas <?php echo $categoryIcons[$catName] ?? 'fa-cube'; ?>"></i>
                </span>
                <h4><?php echo sanitizeOutput($catName); ?></h4>
                <span class="skills-category-line"></span>
            </div>
            <div class="row g-3">
                <?php foreach ($catSkills as $skill): ?>
                <div class="col-sm-6 col-lg-4">
                    <div class="skill-card" style="--skill-color: <?php echo $categoryColors[$catName] ?? '#6366f1'; ?>">
                        <div class="skill-ring-wrapper">
                            <svg class="skill-ring-svg" viewBox="0 0 56 56">
                                <circle class="skill-ring-bg" cx="28" cy="28" r="26" />
                                <circle class="skill-ring-fill" cx="28" cy="28" r="26" data-percent="<?php echo $skill['level']; ?>" />
                            </svg>
                            <span class="skill-ring-percent"><?php echo $skill['level']; ?></span>
                        </div>
                        <div class="skill-info">
                            <span class="skill-name"><?php echo sanitizeOutput($skill['name']); ?></span>
                            <div class="skill-bar-track">
                                <div class="skill-bar-fill" data-width="<?php echo $skill['level']; ?>"></div>
                            </div>
                            <span class="skill-level-label"><?php
                                $lvl = $skill['level'];
                                if ($lvl >= 90) echo 'Expert';
                                elseif ($lvl >= 80) echo 'Avancé';
                                elseif ($lvl >= 60) echo 'Intermédiaire';
                                else echo 'Débutant';
                            ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
