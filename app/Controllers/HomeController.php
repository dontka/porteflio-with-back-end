<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Blog;
use App\Models\Skill;
use App\Models\Experience;

/**
 * HomeController - Gère la page d'accueil
 */
class HomeController extends BaseController
{
    /**
     * Afficher la page d'accueil
     */
    public function index($params = [])
    {
        $this->checkDatabase();

        // Charger les models
        $profileModel = new Profile($this->db);
        $projectModel = new Project($this->db);
        $skillModel = new Skill($this->db);
        $experienceModel = new Experience($this->db);
        $blogModel = new Blog($this->db);

        // Récupérer les données
        $profile = $profileModel->getProfile();
        $projects = $projectModel->getProjects();
        $skills = $skillModel->getSkills();
        $experience = $experienceModel->getExperience();
        $blogPosts = $blogModel->getRecent(6);

        // Récupérer les fonctions utiles pour backward compatibility
        require_once INCLUDES_DIR . 'functions.php';

        // Préparer les données pour la vue
        $systemUrl = SYS_URL;
        $locale = getDefaultLocale();
        $projectCount = count($projects);
        $skillCount = count($skills);
        $skillsByCategory = $skillModel->groupByCategory();
        $avgLevel = $skillModel->getAverageLevel();

        // Récupérer les stats des projets et articles
        $projectStats = getAllProjectStats($this->db);
        $blogStats = getAllBlogStats($this->db);

        // Préparer les meta tags pour le layout
        $pageTitle = 'Portfolio - ' . sanitizeOutput($profile['name'] ?? 'Donatien KANANE');
        $pageDescription = sanitizeOutput($profile['description'] ?? 'Développeur Web Full-Stack & Data Analyst');
        $pageUrl = $systemUrl;
        $pageImage = $systemUrl . 'assets/images/profile.png';

        // Afficher la vue
        echo $this->render('home', [
            'profile' => $profile,
            'projects' => $projects,
            'skills' => $skills,
            'experience' => $experience,
            'blogPosts' => $blogPosts,
            'systemUrl' => $systemUrl,
            'locale' => $locale,
            'projectCount' => $projectCount,
            'skillCount' => $skillCount,
            'skillsByCategory' => $skillsByCategory,
            'avgLevel' => $avgLevel,
            'projectStats' => $projectStats,
            'blogStats' => $blogStats,
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageUrl' => $pageUrl,
            'pageImage' => $pageImage,
            'ogType' => 'website'
        ]);
    }
}
