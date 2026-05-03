<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\AdminUser;
use App\Models\Blog;
use App\Models\Project;

class AdminController extends BaseController
{
    protected $adminUserModel;
    protected $blogModel;
    protected $projectModel;

    public function __construct()
    {
        parent::__construct();
        require_once INCLUDES_DIR . 'AdminAuth.php';
        
        $this->adminUserModel = new AdminUser($this->db);
        $this->blogModel = new Blog($this->db);
        $this->projectModel = new Project($this->db);
    }

    /**
     * Afficher le dashboard admin
     */
    public function dashboard()
    {
        requireAdmin();

        $stats = [
            'totalBlogPosts' => $this->blogModel->count(),
            'totalProjects' => $this->projectModel->count(),
            'totalAdmins' => $this->adminUserModel->countAdmins(),
            'totalUsers' => $this->adminUserModel->countUsers(),
        ];

        $recentBlogPosts = $this->blogModel->getRecent(5);
        $recentProjects = $this->projectModel->getFeatured();

        return $this->renderWithLayout('layouts/admin', 'admin/dashboard', [
            'pageTitle' => 'Tableau de Bord — Administration',
            'pageDescription' => 'Gestion du contenu et des paramètres',
            'stats' => $stats,
            'recentBlogPosts' => $recentBlogPosts,
            'recentProjects' => $recentProjects,
            'currentUser' => getCurrentAdminUser(),
        ]);
    }

    /**
     * Page de gestion des utilisateurs
     */
    public function users()
    {
        requireAdmin();

        $users = $this->adminUserModel->getAll();
        $admins = $this->adminUserModel->getAllAdmins();

        return $this->renderWithLayout('layouts/admin', 'admin/users', [
            'pageTitle' => 'Gestion des Utilisateurs',
            'pageDescription' => 'Voir et gérer les utilisateurs',
            'users' => $users,
            'admins' => $admins,
            'currentUser' => getCurrentAdminUser(),
        ]);
    }

    /**
     * Promouvoir un utilisateur en admin
     */
    public function promoteUser()
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $userId = $_POST['user_id'] ?? null;

        if (!$userId) {
            http_response_code(400);
            return;
        }

        $this->adminUserModel->promoteToAdmin($userId);
        logAdminAction($_SESSION['user_id'], 'promote_user', "Utilisateur ID: {$userId}");

        header('Location: ' . SYS_URL . 'admin/users');
        exit;
    }

    /**
     * Retirer les droits admin d'un utilisateur
     */
    public function revokeUser()
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $userId = $_POST['user_id'] ?? null;
        $currentUserId = $_SESSION['user_id'] ?? null;

        // Impossible de retirer ses propres droits admin
        if (!$userId || $userId == $currentUserId) {
            http_response_code(400);
            return;
        }

        $this->adminUserModel->revokeAdmin($userId);
        logAdminAction($_SESSION['user_id'], 'revoke_admin', "Utilisateur ID: {$userId}");

        header('Location: ' . SYS_URL . 'admin/users');
        exit;
    }

    /**
     * Afficher les logs d'administration
     */
    public function logs()
    {
        requireAdmin();

        $query = "SELECT al.*, u.username FROM admin_logs al 
                  LEFT JOIN users u ON al.user_id = u.id 
                  ORDER BY al.created_at DESC LIMIT 100";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $logs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $this->renderWithLayout('layouts/admin', 'admin/logs', [
            'pageTitle' => 'Logs d\'Administration',
            'pageDescription' => 'Historique des actions administrateur',
            'logs' => $logs,
            'currentUser' => getCurrentAdminUser(),
        ]);
    }

    /**
     * Page des paramètres admin
     */
    public function settings()
    {
        requireAdmin();

        return $this->renderWithLayout('layouts/admin', 'admin/settings', [
            'pageTitle' => 'Paramètres — Administration',
            'pageDescription' => 'Configuration du site',
            'currentUser' => getCurrentAdminUser(),
        ]);
    }
}
