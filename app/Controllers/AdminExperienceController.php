<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Experience;

class AdminExperienceController extends BaseController
{
    protected $experienceModel;

    public function __construct()
    {
        parent::__construct();
        require_once INCLUDES_DIR . 'AdminAuth.php';
        $this->experienceModel = new Experience($this->db);
    }

    /**
     * Lister toutes les expériences
     */
    public function index()
    {
        requireAdmin();

        $experiences = $this->experienceModel->getAll();

        return $this->renderWithLayout('layouts/admin', 'admin/experience/index', [
            'pageTitle' => 'Gestion des Expériences',
            'pageDescription' => 'Voir toutes les expériences',
            'experiences' => $experiences,
            'currentUser' => getCurrentAdminUser(),
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        requireAdmin();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $this->render('admin/experience/form', [
            'pageTitle' => 'Ajouter une Expérience',
            'pageDescription' => 'Créer une nouvelle expérience',
            'experience' => null,
            'isEdit' => false,
            'csrfToken' => $_SESSION['csrf_token'],
            'currentUser' => getCurrentAdminUser(),
        ]);
    }

    /**
     * Sauvegarder une nouvelle expérience
     */
    public function store()
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            http_response_code(403);
            return;
        }

        $title = $_POST['title'] ?? '';
        $company = $_POST['company'] ?? '';
        $startDate = $_POST['start_date'] ?? '';
        $endDate = $_POST['end_date'] ?? '';
        $description = $_POST['description'] ?? '';
        $isCurrent = isset($_POST['is_current']) ? 1 : 0;

        if (!$title || !$company || !$startDate) {
            $_SESSION['error'] = 'Titre, Entreprise et Date de début sont obligatoires';
            header('Location: ' . SYS_URL . 'admin/experience/create');
            exit;
        }

        $data = [
            'title' => $title,
            'company' => $company,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'description' => $description,
            'is_current' => $isCurrent,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->experienceModel->create($data);
        logAdminAction($_SESSION['user_id'], 'create_experience', "Expérience: {$title}");

        $_SESSION['success'] = 'Expérience créée avec succès';
        header('Location: ' . SYS_URL . 'admin/experience');
        exit;
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($params)
    {
        requireAdmin();

        $experienceId = $params['id'] ?? null;
        $experience = $this->experienceModel->getById($experienceId);

        if (!$experience) {
            http_response_code(404);
            return $this->render('errors/404');
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $this->renderWithLayout('layouts/admin', 'admin/experience/form', [
            'pageTitle' => 'Éditer une Expérience',
            'pageDescription' => 'Modifier une expérience existante',
            'experience' => $experience,
            'isEdit' => true,
            'csrfToken' => $_SESSION['csrf_token'],
            'currentUser' => getCurrentAdminUser(),
        ]);
    }

    /**
     * Mettre à jour une expérience
     */
    public function update($params)
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            http_response_code(403);
            return;
        }

        $experienceId = $params['id'] ?? null;
        $experience = $this->experienceModel->getById($experienceId);

        if (!$experience) {
            http_response_code(404);
            return;
        }

        $title = $_POST['title'] ?? '';
        $company = $_POST['company'] ?? '';
        $startDate = $_POST['start_date'] ?? '';
        $endDate = $_POST['end_date'] ?? '';
        $description = $_POST['description'] ?? '';
        $isCurrent = isset($_POST['is_current']) ? 1 : 0;

        if (!$title || !$company || !$startDate) {
            $_SESSION['error'] = 'Titre, Entreprise et Date de début sont obligatoires';
            header('Location: ' . SYS_URL . 'admin/experience/edit/' . $experienceId);
            exit;
        }

        $data = [
            'title' => $title,
            'company' => $company,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'description' => $description,
            'is_current' => $isCurrent,
        ];

        $this->experienceModel->update($experienceId, $data);
        logAdminAction($_SESSION['user_id'], 'update_experience', "Expérience ID: {$experienceId}");

        $_SESSION['success'] = 'Expérience modifiée avec succès';
        header('Location: ' . SYS_URL . 'admin/experience');
        exit;
    }

    /**
     * Supprimer une expérience
     */
    public function delete($params)
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $experienceId = $params['id'] ?? null;
        $experience = $this->experienceModel->getById($experienceId);

        if (!$experience) {
            http_response_code(404);
            return;
        }

        $this->experienceModel->delete($experienceId);
        logAdminAction($_SESSION['user_id'], 'delete_experience', "Expérience ID: {$experienceId}");

        $_SESSION['success'] = 'Expérience supprimée avec succès';
        header('Location: ' . SYS_URL . 'admin/experience');
        exit;
    }
}
