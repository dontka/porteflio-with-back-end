<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Project;

class AdminProjectController extends BaseController
{
    protected $projectModel;

    public function __construct()
    {
        parent::__construct();
        require_once INCLUDES_DIR . 'AdminAuth.php';
        $this->projectModel = new Project($this->db);
    }

    /**
     * Lister tous les projets
     */
    public function index()
    {
        requireAdmin();

        $projects = $this->projectModel->getAll();

        return $this->renderWithLayout('layouts/admin', 'admin/projects/index', [
            'pageTitle' => 'Gestion des Projets',
            'pageDescription' => 'Voir tous les projets',
            'projects' => $projects,
            'currentUser' => getCurrentAdminUser(),
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        requireAdmin();

        // Générer un token CSRF
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $this->renderWithLayout('layouts/admin', 'admin/projects/form', [
            'pageTitle' => 'Créer un Projet',
            'pageDescription' => 'Créer un nouveau projet',
            'project' => null,
            'isEdit' => false,
            'csrfToken' => $_SESSION['csrf_token'],
            'currentUser' => getCurrentAdminUser(),
        ]);
    }

    /**
     * Sauvegarder un nouveau projet
     */
    public function store()
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        // Vérifier le token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            http_response_code(403);
            return;
        }

        $title = $_POST['title'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $description = $_POST['description'] ?? '';
        $content = $_POST['content'] ?? '';
        $technologies = $_POST['technologies'] ?? '';
        $link = $_POST['link'] ?? '';
        $github = $_POST['github'] ?? '';
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;

        if (!$title || !$slug || !$description) {
            $_SESSION['error'] = 'Les champs: Titre, Slug et Description sont obligatoires';
            header('Location: ' . SYS_URL . 'admin/projects/create');
            exit;
        }

        // Nettoyer le slug
        $slug = preg_replace('/[^a-z0-9_-]/', '', strtolower($slug));

        // Gérer l'upload d'image
        $thumbnail = '';
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
            $thumbnail = $this->handleImageUpload($_FILES['thumbnail'], 'projects');
        }

        $data = [
            'title' => $title,
            'slug' => $slug,
            'description' => $description,
            'content' => $content,
            'technologies' => $technologies,
            'link' => $link,
            'github' => $github,
            'is_featured' => $isFeatured,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if ($thumbnail) {
            $data['thumbnail'] = $thumbnail;
        }

        $this->projectModel->create($data);
        logAdminAction($_SESSION['user_id'], 'create_project', "Projet: {$title}");

        $_SESSION['success'] = 'Projet créé avec succès';
        header('Location: ' . SYS_URL . 'admin/projects');
        exit;
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($params)
    {
        requireAdmin();

        $projectId = $params['id'] ?? null;
        $project = $this->projectModel->getById($projectId);

        if (!$project) {
            http_response_code(404);
            return $this->render('errors/404');
        }

        // Générer un token CSRF
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $this->renderWithLayout('layouts/admin', 'admin/projects/form', [
            'pageTitle' => 'Éditer un Projet',
            'pageDescription' => 'Modifier un projet existant',
            'project' => $project,
            'isEdit' => true,
            'csrfToken' => $_SESSION['csrf_token'],
            'currentUser' => getCurrentAdminUser(),
        ]);
    }

    /**
     * Mettre à jour un projet
     */
    public function update($params)
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        // Vérifier le token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            http_response_code(403);
            return;
        }

        $projectId = $params['id'] ?? null;
        $project = $this->projectModel->getById($projectId);

        if (!$project) {
            http_response_code(404);
            return;
        }

        $title = $_POST['title'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $description = $_POST['description'] ?? '';
        $content = $_POST['content'] ?? '';
        $technologies = $_POST['technologies'] ?? '';
        $link = $_POST['link'] ?? '';
        $github = $_POST['github'] ?? '';
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;

        if (!$title || !$slug || !$description) {
            $_SESSION['error'] = 'Les champs: Titre, Slug et Description sont obligatoires';
            header('Location: ' . SYS_URL . 'admin/projects/edit/' . $projectId);
            exit;
        }

        // Nettoyer le slug
        $slug = preg_replace('/[^a-z0-9_-]/', '', strtolower($slug));

        // Gérer l'upload d'image
        $data = [
            'title' => $title,
            'slug' => $slug,
            'description' => $description,
            'content' => $content,
            'technologies' => $technologies,
            'link' => $link,
            'github' => $github,
            'is_featured' => $isFeatured,
        ];

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
            $thumbnail = $this->handleImageUpload($_FILES['thumbnail'], 'projects');
            if ($thumbnail) {
                $data['thumbnail'] = $thumbnail;
            }
        }

        $this->projectModel->update($projectId, $data);
        logAdminAction($_SESSION['user_id'], 'update_project', "Projet ID: {$projectId}");

        $_SESSION['success'] = 'Projet modifié avec succès';
        header('Location: ' . SYS_URL . 'admin/projects');
        exit;
    }

    /**
     * Supprimer un projet
     */
    public function delete($params)
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $projectId = $params['id'] ?? null;
        $project = $this->projectModel->getById($projectId);

        if (!$project) {
            http_response_code(404);
            return;
        }

        $this->projectModel->delete($projectId);
        logAdminAction($_SESSION['user_id'], 'delete_project', "Projet ID: {$projectId}");

        $_SESSION['success'] = 'Projet supprimé avec succès';
        header('Location: ' . SYS_URL . 'admin/projects');
        exit;
    }

    /**
     * Traiter l'upload d'image
     */
    private function handleImageUpload($file, $folder)
    {
        $uploadDir = ASSETS_DIR . 'images' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR;
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
        $filepath = $uploadDir . $filename;

        // Vérifier le type MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($mime, $allowed)) {
            $_SESSION['error'] = 'Format d\'image non supporté';
            return null;
        }

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $filename;
        }

        $_SESSION['error'] = 'Erreur lors de l\'upload de l\'image';
        return null;
    }
}
