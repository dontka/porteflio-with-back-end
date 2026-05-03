<?php
/**
 * DEBUG - Simulate exact index.php flow for /admin
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simulate accessing /admin
$_SERVER['REQUEST_URI'] = '/afiazone/admin';
$_SERVER['SCRIPT_NAME'] = '/afiazone/index.php';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['PATH_INFO'] = '';

define('ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR);
define('APP_DIR', ROOT_DIR . 'app' . DIRECTORY_SEPARATOR);
define('CONFIG_DIR', ROOT_DIR);
define('INCLUDES_DIR', ROOT_DIR . 'includes' . DIRECTORY_SEPARATOR);
define('ASSETS_DIR', ROOT_DIR . 'assets' . DIRECTORY_SEPARATOR);

echo "<pre>";
echo "=== SIMULATING INDEX.PHP FLOW ===\n\n";

echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n\n";

// Load config
require_once CONFIG_DIR . 'config.php';
echo "SYS_URL constant: " . SYS_URL . "\n";
echo "DEBUGGING constant: " . (DEBUGGING ? 'ON' : 'OFF') . "\n\n";

// Load autoloader
require_once APP_DIR . 'Core' . DIRECTORY_SEPARATOR . 'Autoloader.php';
Autoloader::register();

// Start session
session_start();

// Create router
$router = new \App\Core\Router();

// Add routes
$router->add('GET', '/admin', 'AdminController', 'dashboard');
$router->add('GET', '/admin/blog', 'AdminBlogController', 'index');
// ... autres routes

// Try to dispatch
echo "Attempting to dispatch...\n\n";

try {
    $result = $router->dispatch();
    
    if ($result) {
        echo "✅ Dispatch returned output\n";
        echo "Output type: " . gettype($result) . "\n";
        
        if (is_string($result)) {
            echo "Output length: " . strlen($result) . " bytes\n";
            echo "\nFirst 500 chars:\n";
            echo htmlspecialchars(substr($result, 0, 500)) . "\n";
        } else {
            echo "Output: " . print_r($result, true) . "\n";
        }
    } else {
        echo "❌ Dispatch returned nothing\n";
    }
} catch (Throwable $e) {
    echo "❌ Exception caught:\n";
    echo $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "</pre>";
?>
