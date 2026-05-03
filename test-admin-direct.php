<?php
/**
 * DEBUG - Simulate /admin routing via .htaccess
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// SIMULATE WHAT .htaccess SENDS WHEN ACCESSING /admin
// When user visits /afiazone/admin, .htaccess rewrites to /afiazone/index.php/admin
// PHP then receives:

$_SERVER['REQUEST_URI'] = '/afiazone/admin';           // What user requested
$_SERVER['SCRIPT_NAME'] = '/afiazone/index.php';       // The rewritten file
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/index.php';  // Full path to index.php
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['PATH_INFO'] = '/admin';                      // The path after index.php
$_SERVER['HTTP_HOST'] = 'localhost';

define('ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR);
define('APP_DIR', ROOT_DIR . 'app' . DIRECTORY_SEPARATOR);
define('CONFIG_DIR', ROOT_DIR);
define('INCLUDES_DIR', ROOT_DIR . 'includes' . DIRECTORY_SEPARATOR);
define('ASSETS_DIR', ROOT_DIR . 'assets' . DIRECTORY_SEPARATOR);

echo "<pre>";
echo "=== SIMULATING /admin REQUEST (WITH .htaccess) ===\n\n";

echo "What .htaccess sends:\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PATH_INFO: " . $_SERVER['PATH_INFO'] . "\n";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n\n";

// Load config
require_once CONFIG_DIR . 'config.php';

// Load autoloader
require_once APP_DIR . 'Core' . DIRECTORY_SEPARATOR . 'Autoloader.php';
Autoloader::register();

// Start session
session_start();

// Load AdminAuth
require_once INCLUDES_DIR . 'AdminAuth.php';

echo "Session:\n";
echo "user_id: " . ($_SESSION['user_id'] ?? 'NOT SET') . "\n";
echo "is_admin: " . ($_SESSION['is_admin'] ?? 'NOT SET') . "\n";
echo "isUserAdmin(): " . (isUserAdmin() ? 'TRUE ✅' : 'FALSE ❌') . "\n\n";

if (!isUserAdmin()) {
    echo "❌ NOT ADMIN - Would redirect to login\n";
    echo "</pre>";
    exit;
}

// Create router and register routes
$router = new \App\Core\Router();

// Register all routes (like index.php does)
$router->add('GET', '/', 'HomeController', 'index');
$router->add('GET', '/admin', 'AdminController', 'dashboard');
$router->add('GET', '/admin/blog', 'AdminBlogController', 'index');
$router->add('GET', '/login', 'LoginController', 'showForm');
$router->add('POST', '/login', 'LoginController', 'handleLogin');

echo "Routes registered: 5+\n\n";

// Try dispatch
echo "Attempting dispatch()...\n\n";

try {
    ob_start();
    $result = $router->dispatch();
    $output = ob_get_clean();
    
    if ($output) {
        echo "✅ Output from dispatch:\n";
        echo strlen($output) . " bytes\n\n";
        echo htmlspecialchars(substr($output, 0, 800)) . "\n";
    } elseif ($result) {
        echo "✅ Result returned:\n";
        if (is_string($result)) {
            echo strlen($result) . " bytes\n\n";
            echo htmlspecialchars(substr($result, 0, 800)) . "\n";
        } else {
            echo gettype($result) . "\n";
        }
    } else {
        echo "❌ No output returned\n";
    }
} catch (Throwable $e) {
    echo "❌ Exception:\n";
    echo $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "</pre>";
?>
