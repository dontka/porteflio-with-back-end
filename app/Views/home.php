<?php
/**
 * HOME.PHP - Page d'accueil
 * Utilise le layout 'app.php' et inclut les sections
 */

// Include all sections
$view->include('sections/hero');
$view->include('sections/stats');
$view->include('sections/stack');
$view->include('sections/services');
$view->include('sections/skills');
$view->include('sections/projects');
$view->include('sections/experience');
$view->include('sections/education');
$view->include('sections/blog');
$view->include('sections/github');
$view->include('sections/contact');

// Home page specific styles
$pageStyles = [
    'assets/plugins/github-calendar/dist/github-calendar-responsive.css',
    'assets/plugins/github-activity/src/github-activity.min.css'
];

// Home page specific scripts
$pageScripts = [
    'assets/plugins/vanilla-rss/dist/rss.global.min.js',
    'assets/plugins/github-calendar/dist/github-calendar.min.js',
    'assets/plugins/mustache.min.js',
    'assets/plugins/github-activity/src/github-activity.min.js'
];

// GitHub initialization
if (!empty($profile['github_username'])):
    $ghUser = sanitizeOutput($profile['github_username']);
    $inlineScripts = <<<JS
(function() {
    var username = "$ghUser";
    var graphEl = document.getElementById('github-graph');
    var feedEl = document.getElementById('ghfeed');

    if (graphEl) {
        GitHubCalendar(graphEl, username, { responsive: true, global_stats: false });
    }

    if (feedEl) {
        GitHubActivity.feed({
            username: username,
            selector: '#ghfeed',
            limit: 5
        });
    }
})();
JS;
endif;
?>
