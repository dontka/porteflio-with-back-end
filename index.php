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

// Routes API
$router->add('POST', '/api', 'APIController', 'route');

// ============================================================
// DISPATCHER LA REQUÊTE
// ============================================================

try {
    $router->dispatch();
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
