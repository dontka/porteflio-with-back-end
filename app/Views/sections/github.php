<!-- ===== GITHUB ===== -->
<?php $ghUser = sanitizeOutput($profile['github_username'] ?? ''); ?>
<?php if (!empty($ghUser)): ?>
<section class="section-block github-section" id="github">
    <div class="container">
        <div class="section-title text-center">
            <span class="section-subtitle">Open Source</span>
            <h2>Activité GitHub</h2>
            <div class="title-line"></div>
            <p class="section-description">Contributions, statistiques et activité récente sur <a href="https://github.com/<?php echo $ghUser; ?>" target="_blank" rel="noopener noreferrer">github.com/<?php echo $ghUser; ?></a></p>
        </div>

        <!-- Stats Cards Row -->
        <div class="github-stats-row">
            <div class="github-stat-card">
                <img src="https://github-readme-stats.vercel.app/api?username=<?php echo $ghUser; ?>&show_icons=true&theme=transparent&hide_border=true&include_all_commits=true&count_private=true&title_color=6366f1&icon_color=06b6d4&text_color=64748b" alt="Statistiques GitHub de <?php echo $ghUser; ?>" loading="lazy" width="495" height="195" onerror="this.parentElement.innerHTML='<div class=\'github-stat-fallback\'><i class=\'fas fa-chart-bar fa-3x mb-3\'></i><p>Statistiques temporairement indisponibles</p><a href=\'https://github.com/<?php echo $ghUser; ?>\' target=\'_blank\' rel=\'noopener noreferrer\'>Voir sur GitHub</a></div>'">
            </div>
            <div class="github-stat-card">
                <img src="https://github-readme-stats.vercel.app/api/top-langs/?username=<?php echo $ghUser; ?>&layout=compact&theme=transparent&hide_border=true&title_color=6366f1&text_color=64748b&langs_count=8" alt="Langages les plus utilisés" loading="lazy" width="350" height="195" onerror="this.parentElement.innerHTML='<div class=\'github-stat-fallback\'><i class=\'fas fa-code fa-3x mb-3\'></i><p>Langages temporairement indisponibles</p><a href=\'https://github.com/<?php echo $ghUser; ?>\' target=\'_blank\' rel=\'noopener noreferrer\'>Voir sur GitHub</a></div>'">
            </div>
        </div>

        <!-- Streak Stats -->
        <div class="github-streak-card">
            <img src="https://github-readme-streak-stats.herokuapp.com/?user=<?php echo $ghUser; ?>&theme=transparent&hide_border=true&ring=6366f1&fire=06b6d4&currStreakLabel=6366f1&sideLabels=64748b&dates=94a3b8" alt="Série de contributions GitHub" loading="lazy" width="800" height="220" onerror="this.parentElement.innerHTML='<div class=\'github-stat-fallback\'><i class=\'fas fa-fire fa-3x mb-3\'></i><p>Streak stats temporairement indisponibles</p></div>'">
        </div>

        <!-- Activity Graph -->
        <div class="github-graph-card">
            <img src="https://github-readme-activity-graph.vercel.app/graph?username=<?php echo $ghUser; ?>&theme=react-dark&hide_border=true&area=true&bg_color=00000000&color=6366f1&line=06b6d4&point=6366f1&area_color=6366f1" alt="Graphique d'activité GitHub" loading="lazy" width="900" height="300" onerror="this.parentElement.innerHTML='<div class=\'github-stat-fallback\'><i class=\'fas fa-chart-line fa-3x mb-3\'></i><p>Graphique d\'activité temporairement indisponible</p></div>'">
        </div>

        <!-- Calendar + Activity Feed -->
        <div class="row g-4 mt-2">
            <div class="col-lg-7">
                <div class="github-card">
                    <h5><i class="fas fa-calendar-alt"></i> Calendrier de contributions</h5>
                    <div id="github-graph" class="github-graph"></div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="github-card">
                    <h5><i class="fas fa-stream"></i> Activité récente</h5>
                    <div id="ghfeed" class="ghfeed"></div>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="text-center mt-4">
            <a href="https://github.com/<?php echo $ghUser; ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-custom">
                <i class="fa-brands fa-github"></i> Voir mon profil GitHub
            </a>
        </div>
    </div>
</section>
<?php endif; ?>
