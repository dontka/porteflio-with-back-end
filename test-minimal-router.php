<?php
/**
 * DEBUG - Ultra minimal router test
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR);
define('APP_DIR', ROOT_DIR . 'app' . DIRECTORY_SEPARATOR);
define('CONFIG_DIR', ROOT_DIR);
define('INCLUDES_DIR', ROOT_DIR . 'includes' . DIRECTORY_SEPARATOR);
define('ASSETS_DIR', ROOT_DIR . 'assets' . DIRECTORY_SEPARATOR);

require_once CONFIG_DIR . 'config.php';
require_once APP_DIR . 'Core' . DIRECTORY_SEPARATOR . 'Autoloader.php';
Autoloader::register();

session_start();
require_once INCLUDES_DIR . 'AdminAuth.php';

echo "<pre>";
echo "=== ULTRA MINIMAL ROUTER TEST ===\n\n";

// Check admin status
echo "Admin check:\n";
echo "isUserAdmin(): " . (isUserAdmin() ? 'TRUE ✅' : 'FALSE ❌') . "\n\n";

if (!isUserAdmin()) {
    echo "NOT ADMIN\n";
    echo "</pre>";
    exit;
}

// Create router manually
$router = new \App\Core\Router();

// Add ONE route
$router->add('GET', '/admin', 'AdminController', 'dashboard');

echo "Routes added: 1\n";
echo "Route pattern: GET /admin\n\n";

// Manually test dispatch with /admin path
echo "Manually calling dispatch('GET', '/admin')...\n\n";

try {
    $result = $router->dispatch('GET', '/admin');
    
    if ($result) {
        echo "✅ SUCCESS\n";
        echo "Result length: " . strlen($result) . "\n";
        echo "First 500 chars:\n";
        echo htmlspecialchars(substr($result, 0, 500)) . "\n";
    } else {
        echo "❌ FAILED - Result is empty\n";
    }
} catch (Throwable $e) {
    echo "❌ EXCEPTION:\n";
    echo $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "</pre>";
?>
