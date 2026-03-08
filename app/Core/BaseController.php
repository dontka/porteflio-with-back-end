<?php

namespace App\Core;

/**
 * BaseController - Classe de base pour tous les controllers
 */
abstract class BaseController
{
    protected $db;
    protected $view;

    public function __construct()
    {
        require_once CONFIG_DIR . 'config.php';
        require_once INCLUDES_DIR . 'Database.php';
        require_once INCLUDES_DIR . 'functions.php';

        // Initialiser la connexion BD
        $database = new \Database();
        $this->db = $database->getConnection();

        // Initialiser la vue
        $this->view = new View(APP_DIR . 'Views' . DIRECTORY_SEPARATOR);
    }

    /**
     * Rendre une vue (utilise le layout 'app' par défaut)
     */
    protected function render($template, $data = [])
    {
        return $this->view->renderWithLayout('layouts/app', $template, $data);
    }

    /**
     * Rendre une vue avec un layout spécifique
     */
    protected function renderWithLayout($layout, $template, $data = [])
    {
        return $this->view->renderWithLayout($layout, $template, $data);
    }

    /**
     * Rendre une vue sans layout
     */
    protected function renderPartial($template, $data = [])
    {
        return $this->view->render($template, $data);
    }

    /**
     * Afficher une réponse JSON
     */
    protected function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    /**
     * Rediriger vers une URL
     */
    protected function redirect($url)
    {
        header("Location: {$url}", true, 302);
        exit;
    }

    /**
     * Vérifier si la BD est connectée
     */
    protected function checkDatabase()
    {
        if (!$this->db) {
            $this->json(['error' => 'Database connection failed'], 500);
        }
    }
}
