<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Skill;

class AdminSkillController extends BaseController
{
    protected $skillModel;

    public function __construct()
    {
        parent::__construct();
        require_once INCLUDES_DIR . 'AdminAuth.php';
        $this->skillModel = new Skill($this->db);
    }

    /**
     * Lister toutes les compétences
     */
    public function index()
    {
        requireAdmin();

        $skills = $this->skillModel->getAll();

        return $this->renderWithLayout('layouts/admin', 'admin/skills/index', [
            'pageTitle' => 'Gestion des Compétences',
            'pageDescription' => 'Voir toutes les compétences',
            'skills' => $skills,
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

        return $this->render('admin/skills/form', [
            'pageTitle' => 'Ajouter une Compétence',
            'pageDescription' => 'Créer une nouvelle compétence',
            'skill' => null,
            'isEdit' => false,
            'csrfToken' => $_SESSION['csrf_token'],
            'currentUser' => getCurrentAdminUser(),
        ]);
    }

    /**
     * Sauvegarder une nouvelle compétence
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

        $name = $_POST['name'] ?? '';
        $category = $_POST['category'] ?? '';
        $proficiency = $_POST['proficiency'] ?? 50;

        if (!$name || !$category) {
            $_SESSION['error'] = 'Nom et catégorie sont obligatoires';
            header('Location: ' . SYS_URL . 'admin/skills/create');
            exit;
        }

        $data = [
            'name' => $name,
            'category' => $category,
            'proficiency' => $proficiency,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->skillModel->create($data);
        logAdminAction($_SESSION['user_id'], 'create_skill', "Compétence: {$name}");

        $_SESSION['success'] = 'Compétence créée avec succès';
        header('Location: ' . SYS_URL . 'admin/skills');
        exit;
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($params)
    {
        requireAdmin();

        $skillId = $params['id'] ?? null;
        $skill = $this->skillModel->getById($skillId);

        if (!$skill) {
            http_response_code(404);
            return $this->render('errors/404');
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $this->renderWithLayout('layouts/admin', 'admin/skills/form', [
            'pageTitle' => 'Éditer une Compétence',
            'pageDescription' => 'Modifier une compétence existante',
            'skill' => $skill,
            'isEdit' => true,
            'csrfToken' => $_SESSION['csrf_token'],
            'currentUser' => getCurrentAdminUser(),
        ]);
    }

    /**
     * Mettre à jour une compétence
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

        $skillId = $params['id'] ?? null;
        $skill = $this->skillModel->getById($skillId);

        if (!$skill) {
            http_response_code(404);
            return;
        }

        $name = $_POST['name'] ?? '';
        $category = $_POST['category'] ?? '';
        $proficiency = $_POST['proficiency'] ?? 50;

        if (!$name || !$category) {
            $_SESSION['error'] = 'Nom et catégorie sont obligatoires';
            header('Location: ' . SYS_URL . 'admin/skills/edit/' . $skillId);
            exit;
        }

        $data = [
            'name' => $name,
            'category' => $category,
            'proficiency' => $proficiency,
        ];

        $this->skillModel->update($skillId, $data);
        logAdminAction($_SESSION['user_id'], 'update_skill', "Compétence ID: {$skillId}");

        $_SESSION['success'] = 'Compétence modifiée avec succès';
        header('Location: ' . SYS_URL . 'admin/skills');
        exit;
    }

    /**
     * Supprimer une compétence
     */
    public function delete($params)
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $skillId = $params['id'] ?? null;
        $skill = $this->skillModel->getById($skillId);

        if (!$skill) {
            http_response_code(404);
            return;
        }

        $this->skillModel->delete($skillId);
        logAdminAction($_SESSION['user_id'], 'delete_skill', "Compétence ID: {$skillId}");

        $_SESSION['success'] = 'Compétence supprimée avec succès';
        header('Location: ' . SYS_URL . 'admin/skills');
        exit;
    }
}
