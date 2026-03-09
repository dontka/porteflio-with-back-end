<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Blog;
use App\Models\Profile;

/**
 * BlogController - Gère l'affichage des articles de blog
 */
class BlogController extends BaseController
{
    /**
     * Afficher un article de blog
     */
    public function show($params = [])
    {
        $this->checkDatabase();

        // Récupérer le slug de l'article
        $slug = $params['slug'] ?? null;

        if (!$slug) {
            header('Location: ' . SYS_URL . '#blog', true, 302);
            exit;
        }

        // Charger l'article
        $blogModel = new Blog($this->db);
        $post = $blogModel->getBySlug($slug);

        if (!$post) {
            header('Location: ' . SYS_URL . '#blog', true, 302);
            exit;
        }

        // Charger le profil
        $profileModel = new Profile($this->db);
        $profile = $profileModel->getProfile();

        // Récupérer les fonctions utiles
        require_once INCLUDES_DIR . 'functions.php';

        // Préparer les données pour la vue
        $systemUrl = SYS_URL;
        $locale = getDefaultLocale();
        $blogStats = getAllBlogStats($this->db);
        
        // Préparer les meta tags pour le layout
        $blogTitle = sanitizeOutput($post['title']) . ' — Blog | Donatien KANANE';
        $blogDesc = sanitizeOutput($post['excerpt']);
        $blogUrl = $systemUrl . 'blog/' . urlencode($post['slug']);
        $blogImage = !empty($post['image_url']) ? $systemUrl . sanitizeOutput($post['image_url']) : $systemUrl . 'assets/images/profile.png';

        // Afficher la vue
        echo $this->render('blog', [
            'post' => $post,
            'slug' => $slug,
            'profile' => $profile,
            'systemUrl' => $systemUrl,
            'locale' => $locale,
            'blogStats' => $blogStats,
            'pageTitle' => $blogTitle,
            'pageDescription' => $blogDesc,
            'pageUrl' => $blogUrl,
            'pageImage' => $blogImage,
            'ogType' => 'article',
            'isScrolled' => true,
            'db' => $this->db
        ]);
    }
}
