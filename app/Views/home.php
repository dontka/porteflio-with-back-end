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
    'assets/plugins/mustache.min.js'
];

// GitHub initialization - only load GitHub scripts if username is configured
if (!empty($profile['github_username'])):
    $ghUser = sanitizeOutput($profile['github_username']);
    
    // Pre-compute the activity script URL outside the heredoc
    $activityScriptUrl = $systemUrl . 'assets/plugins/github-activity/src/github-activity.min.js';
    
    // NOTE: github-calendar plugin has a bug where it tries to access null properties
    // The GitHub stats are already displayed via images, so this plugin is not critical
    // If you want to use it later, ensure the HTML elements have the correct IDs/structure
    
    $inlineScripts = <<<JS
(function() {
    var username = "$ghUser";
    var activityScriptUrl = "$activityScriptUrl";
    
    // Load github-activity.min.js only (calendar disabled due to plugin bug)
    setTimeout(function() {
        var activityScript = document.createElement('script');
        activityScript.src = activityScriptUrl;
        activityScript.onload = function() {
            var feedEl = document.getElementById('ghfeed');
            if (feedEl && typeof GitHubActivity !== 'undefined') {
                try {
                    GitHubActivity.feed({
                        username: username,
                        selector: '#ghfeed',
                        limit: 5
                    });
                } catch (e) {
                    console.warn('GitHub Activity error:', e);
                }
            }
        };
        activityScript.onerror = function() {
            console.warn('Failed to load github-activity.min.js');
        };
        document.body.appendChild(activityScript);
    }, 1000);
})();
JS;
endif;
?>
