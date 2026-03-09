<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Project;
use App\Models\Profile;

/**
 * ProjectController - Gère l'affichage des projets
 */
class ProjectController extends BaseController
{
    /**
     * Afficher la page d'un projet
     */
    public function show($params = [])
    {
        $this->checkDatabase();

        // Récupérer le slug du projet
        $slug = $params['slug'] ?? null;

        if (!$slug) {
            header('Location: ' . SYS_URL, true, 302);
            exit;
        }

        // Charger le projet
        $projectModel = new Project($this->db);
        $project = $projectModel->getBySlug($slug);

        if (!$project) {
            header('Location: ' . SYS_URL, true, 302);
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
        $projectStats = getAllProjectStats($this->db);
        
        // Préparer les meta tags pour le layout
        $projectTitle = sanitizeOutput($project['title']) . ' — Projets | Donatien KANANE';
        $projectDesc = sanitizeOutput($project['description']);
        $projectUrl = $systemUrl . 'projet/' . urlencode($project['slug']);
        $projectImage = !empty($project['image_url']) ? $systemUrl . sanitizeOutput($project['image_url']) : $systemUrl . 'assets/images/profile.png';

        // Afficher la vue
        echo $this->render('project', [
            'project' => $project,
            'profile' => $profile,
            'systemUrl' => $systemUrl,
            'locale' => $locale,
            'projectStats' => $projectStats,
            'pageTitle' => $projectTitle,
            'pageDescription' => $projectDesc,
            'pageUrl' => $projectUrl,
            'pageImage' => $projectImage,
            'ogType' => 'article',
            'isScrolled' => true,
            'db' => $this->db
        ]);
    }
}
