<?php
/**
 * ============================================================
 * FRONT CONTROLLER - Point d'entrée unique de l'application
 * ============================================================
 * 
 * Cette fichier est le seul point d'entrée pour toutes
 * les requêtes (grâce à .htaccess). Elle initialise 
 * l'application et délègue au router.
 */

// Définir les constantes de chemins
define('ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR);
define('APP_DIR', ROOT_DIR . 'app' . DIRECTORY_SEPARATOR);
define('CONFIG_DIR', ROOT_DIR);
define('INCLUDES_DIR', ROOT_DIR . 'includes' . DIRECTORY_SEPARATOR);
define('ASSETS_DIR', ROOT_DIR . 'assets' . DIRECTORY_SEPARATOR);

// Charger la configuration
require_once CONFIG_DIR . 'config.php';

// Charger l'autoloader
require_once APP_DIR . 'Core' . DIRECTORY_SEPARATOR . 'Autoloader.php';
Autoloader::register();

// Démarrer la session
session_start();

// Créer le router
$router = new \App\Core\Router();

// ============================================================
// DÉFINIR LES ROUTES
// ============================================================

// Route d'accueil
$router->add('GET', '/', 'HomeController', 'index');

// Routes des projets
$router->add('GET', '/projet/{slug}', 'ProjectController', 'show');
$router->add('GET', '/project/{slug}', 'ProjectController', 'show'); // Backward compatibility

// Routes du blog
$router->add('GET', '/blog/{slug}', 'BlogController', 'show');

// Routes d'authentification
$router->add('GET', '/login', 'LoginController', 'showForm');
$router->add('POST', '/login', 'LoginController', 'handleLogin');
$router->add('GET', '/register', 'RegisterController', 'showForm');
$router->add('POST', '/register', 'RegisterController', 'handleRegister');
$router->add('GET', '/logout', 'LogoutController', 'logout');

// ============================================================
// ROUTES D'ADMINISTRATION
// ============================================================

// Dashboard administrateur
$router->add('GET', '/admin', 'AdminController', 'dashboard');

// Gestion des articles de blog
$router->add('GET', '/admin/blog', 'AdminBlogController', 'index');
$router->add('GET', '/admin/blog/create', 'AdminBlogController', 'create');
$router->add('POST', '/admin/blog/store', 'AdminBlogController', 'store');
$router->add('GET', '/admin/blog/edit/{id}', 'AdminBlogController', 'edit');
$router->add('POST', '/admin/blog/update/{id}', 'AdminBlogController', 'update');
$router->add('POST', '/admin/blog/delete/{id}', 'AdminBlogController', 'delete');

// Gestion des projets
$router->add('GET', '/admin/projects', 'AdminProjectController', 'index');
$router->add('GET', '/admin/projects/create', 'AdminProjectController', 'create');
$router->add('POST', '/admin/projects/store', 'AdminProjectController', 'store');
$router->add('GET', '/admin/projects/edit/{id}', 'AdminProjectController', 'edit');
$router->add('POST', '/admin/projects/update/{id}', 'AdminProjectController', 'update');
$router->add('POST', '/admin/projects/delete/{id}', 'AdminProjectController', 'delete');

// Gestion des compétences
$router->add('GET', '/admin/skills', 'AdminSkillController', 'index');
$router->add('GET', '/admin/skills/create', 'AdminSkillController', 'create');
$router->add('POST', '/admin/skills/store', 'AdminSkillController', 'store');
$router->add('GET', '/admin/skills/edit/{id}', 'AdminSkillController', 'edit');
$router->add('POST', '/admin/skills/update/{id}', 'AdminSkillController', 'update');
$router->add('POST', '/admin/skills/delete/{id}', 'AdminSkillController', 'delete');

// Gestion des expériences
$router->add('GET', '/admin/experience', 'AdminExperienceController', 'index');
$router->add('GET', '/admin/experience/create', 'AdminExperienceController', 'create');
$router->add('POST', '/admin/experience/store', 'AdminExperienceController', 'store');
$router->add('GET', '/admin/experience/edit/{id}', 'AdminExperienceController', 'edit');
$router->add('POST', '/admin/experience/update/{id}', 'AdminExperienceController', 'update');
$router->add('POST', '/admin/experience/delete/{id}', 'AdminExperienceController', 'delete');

// Gestion des utilisateurs et logs
$router->add('GET', '/admin/users', 'AdminController', 'users');
$router->add('POST', '/admin/promote-user', 'AdminController', 'promoteUser');
$router->add('POST', '/admin/revoke-user', 'AdminController', 'revokeUser');
$router->add('GET', '/admin/logs', 'AdminController', 'logs');
$router->add('GET', '/admin/settings', 'AdminController', 'settings');

// Routes API
$router->add('POST', '/api', 'APIController', 'route');

// ============================================================
// DISPATCHER LA REQUÊTE
// ============================================================

try {
    echo $router->dispatch();
} catch (\Exception $e) {
    http_response_code(500);
    if (DEBUGGING) {
        echo "<h1>Erreur</h1>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    } else {
        echo "<h1>Erreur serveur</h1>";
        echo "<p>Une erreur est survenue. Veuillez réessayer plus tard.</p>";
    }
}
