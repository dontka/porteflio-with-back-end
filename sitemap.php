<?php
require_once 'config.php';
require_once 'includes/Database.php';
require_once 'includes/functions.php';

header('Content-Type: application/xml; charset=utf-8');

$database = new Database();
$db = $database->getConnection();
$systemUrl = getSystemUrl();

$projects = getProjects($db);
$blogPosts = getBlogPosts($db, 100);

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?php echo sanitizeOutput($systemUrl); ?></loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
<?php foreach ($projects as $project): ?>
    <url>
        <loc><?php echo sanitizeOutput($systemUrl . 'project.php?url=' . urlencode($project['project_url'])); ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
<?php endforeach; ?>
<?php foreach ($blogPosts as $post): ?>
    <url>
        <loc><?php echo sanitizeOutput($systemUrl . 'blog.php?slug=' . urlencode($post['slug'])); ?></loc>
        <lastmod><?php echo date('Y-m-d', strtotime($post['updated_at'] ?? $post['created_at'])); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
<?php endforeach; ?>
</urlset>
