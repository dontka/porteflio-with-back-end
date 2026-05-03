<?php
/**
 * DEBUG - Router path extraction
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
echo "=== ROUTER DEBUG ===\n\n";

// Show REQUEST_URI info
echo "SERVER info:\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";
echo "PATH_INFO: " . ($_SERVER['PATH_INFO'] ?? 'NOT SET') . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'NOT SET') . "\n\n";

// Simulate getPath() logic
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$script_name = $_SERVER['SCRIPT_NAME'] ?? '/';
$path_info = $_SERVER['PATH_INFO'] ?? '';

echo "Raw extraction:\n";
echo "1. request_uri: $request_uri\n";
echo "2. script_name: $script_name\n";
echo "3. path_info: $path_info\n\n";

// Simulate getPath() exactly
if (!empty($path_info)) {
    $path = $path_info;
    echo "Using PATH_INFO\n";
} else {
    $base_path = dirname($script_name);
    echo "base_path from dirname(SCRIPT_NAME): $base_path\n";
    
    if ($base_path !== '/' && strpos($request_uri, $base_path) === 0) {
        $request_uri = substr($request_uri, strlen($base_path));
        echo "After stripping base_path: $request_uri\n";
    }
    
    if (strpos($request_uri, '/index.php/') === 0) {
        $request_uri = substr($request_uri, strlen('/index.php'));
        echo "After stripping /index.php: $request_uri\n";
    }
    
    $path = explode('?', $request_uri)[0];
}

$path = rtrim($path, '/') ?: '/';
echo "\nFinal extracted path: $path\n\n";

// Check if route exists
$router = new \App\Core\Router();

// List all registered routes
echo "Routes registered:\n";
$reflection = new ReflectionClass($router);
$property = $reflection->getProperty('routes');
$property->setAccessible(true);
$routes = $property->getValue($router);

foreach ($routes as $route) {
    echo "  " . $route['method'] . " " . $route['path'] . " → " . $route['controller'] . "::" . $route['action'] . "\n";
}

echo "\n";

// Try to match
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
echo "Trying to match: $method $path\n";

$matched = false;
foreach ($routes as $route) {
    if ($route['method'] === $method && preg_match($route['pattern'], $path, $matches)) {
        echo "✅ MATCHED: " . $route['controller'] . "::" . $route['action'] . "\n";
        $matched = true;
        break;
    }
}

if (!$matched) {
    echo "❌ NO MATCH\n";
}

echo "</pre>";
?>
