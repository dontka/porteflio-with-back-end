<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Blog;

class AdminBlogController extends BaseController
{
    protected $blogModel;

    public function __construct()
    {
        parent::__construct();
        require_once INCLUDES_DIR . 'AdminAuth.php';
        $this->blogModel = new Blog($this->db);
    }

    /**
     * Lister tous les articles de blog
     */
    public function index()
    {
        requireAdmin();

        $posts = $this->blogModel->getAll();

        return $this->renderWithLayout('layouts/admin', 'admin/blog/index', [
            'pageTitle' => 'Gestion des Articles',
            'pageDescription' => 'Voir tous les articles de blog',
            'posts' => $posts,
            'currentUser' => getCurrentAdminUser(),
        ]);
    }

    /**
     * Afficher le formulaire de création d'article
     */
    public function create()
    {
        requireAdmin();

        // Générer un token CSRF
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $this->renderWithLayout('layouts/admin', 'admin/blog/form', [
            'pageTitle' => 'Créer un Article',
            'pageDescription' => 'Créer un nouvel article de blog',
            'post' => null,
            'isEdit' => false,
            'csrfToken' => $_SESSION['csrf_token'],
            'currentUser' => getCurrentAdminUser(),
        ]);
    }

    /**
     * Sauvegarder un nouvel article
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
        $content = $_POST['content'] ?? '';
        $excerpt = $_POST['excerpt'] ?? '';

        if (!$title || !$slug || !$content) {
            $_SESSION['error'] = 'Tous les champs obligatoires doivent être remplis';
            header('Location: ' . SYS_URL . 'admin/blog/create');
            exit;
        }

        // Nettoyer le slug
        $slug = preg_replace('/[^a-z0-9_-]/', '', strtolower($slug));

        $data = [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->blogModel->create($data);
        logAdminAction($_SESSION['user_id'], 'create_blog', "Article: {$title}");

        $_SESSION['success'] = 'Article créé avec succès';
        header('Location: ' . SYS_URL . 'admin/blog');
        exit;
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($params)
    {
        requireAdmin();

        $postId = $params['id'] ?? null;
        $post = $this->blogModel->getById($postId);

        if (!$post) {
            http_response_code(404);
            return $this->render('errors/404');
        }

        // Générer un token CSRF
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $this->renderWithLayout('layouts/admin', 'admin/blog/form', [
            'pageTitle' => 'Éditer un Article',
            'pageDescription' => 'Modifier un article existant',
            'post' => $post,
            'isEdit' => true,
            'csrfToken' => $_SESSION['csrf_token'],
            'currentUser' => getCurrentAdminUser(),
        ]);
    }

    /**
     * Mettre à jour un article
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

        $postId = $params['id'] ?? null;
        $post = $this->blogModel->getById($postId);

        if (!$post) {
            http_response_code(404);
            return;
        }

        $title = $_POST['title'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $content = $_POST['content'] ?? '';
        $excerpt = $_POST['excerpt'] ?? '';

        if (!$title || !$slug || !$content) {
            $_SESSION['error'] = 'Tous les champs obligatoires doivent être remplis';
            header('Location: ' . SYS_URL . 'admin/blog/edit/' . $postId);
            exit;
        }

        // Nettoyer le slug
        $slug = preg_replace('/[^a-z0-9_-]/', '', strtolower($slug));

        $data = [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt,
        ];

        $this->blogModel->update($postId, $data);
        logAdminAction($_SESSION['user_id'], 'update_blog', "Article ID: {$postId}");

        $_SESSION['success'] = 'Article modifié avec succès';
        header('Location: ' . SYS_URL . 'admin/blog');
        exit;
    }

    /**
     * Supprimer un article
     */
    public function delete($params)
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $postId = $params['id'] ?? null;
        $post = $this->blogModel->getById($postId);

        if (!$post) {
            http_response_code(404);
            return;
        }

        $this->blogModel->delete($postId);
        logAdminAction($_SESSION['user_id'], 'delete_blog', "Article ID: {$postId}");

        $_SESSION['success'] = 'Article supprimé avec succès';
        header('Location: ' . SYS_URL . 'admin/blog');
        exit;
    }
}
