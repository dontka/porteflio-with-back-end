<?php
/**
 * DEBUG FILE 3 - Simple error check
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Définir les constantes de chemins
define('ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR);
define('APP_DIR', ROOT_DIR . 'app' . DIRECTORY_SEPARATOR);
define('CONFIG_DIR', ROOT_DIR);
define('INCLUDES_DIR', ROOT_DIR . 'includes' . DIRECTORY_SEPARATOR);
define('ASSETS_DIR', ROOT_DIR . 'assets' . DIRECTORY_SEPARATOR);

echo "<h1>🔍 DEBUG 3 - File Check</h1>";
echo "<pre>";

// Check if files exist
$files = [
    'AdminController' => APP_DIR . 'Controllers' . DIRECTORY_SEPARATOR . 'AdminController.php',
    'admin/dashboard view' => APP_DIR . 'Views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'dashboard.php',
    'admin layout' => APP_DIR . 'Views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'admin.php',
    'admin.css' => ASSETS_DIR . 'css' . DIRECTORY_SEPARATOR . 'admin.min.css',
];

foreach ($files as $name => $path) {
    $exists = file_exists($path);
    echo $exists ? "✅ " : "❌ ";
    echo $name . "\n";
    echo "   Path: " . $path . "\n\n";
}

// Now try to load config and autoloader
echo "=== Loading Core Files ===\n";

try {
    require_once CONFIG_DIR . 'config.php';
    echo "✅ config.php loaded\n";
} catch (Throwable $e) {
    echo "❌ config.php error: " . $e->getMessage() . "\n";
}

try {
    require_once APP_DIR . 'Core' . DIRECTORY_SEPARATOR . 'Autoloader.php';
    Autoloader::register();
    echo "✅ Autoloader registered\n";
} catch (Throwable $e) {
    echo "❌ Autoloader error: " . $e->getMessage() . "\n";
}

// Start session
session_start();

echo "\n=== Session & Auth ===\n";
echo "User ID: " . ($_SESSION['user_id'] ?? 'NOT SET') . "\n";
echo "is_admin: " . ($_SESSION['is_admin'] ?? 'NOT SET') . "\n";

// Load AdminAuth
try {
    require_once INCLUDES_DIR . 'AdminAuth.php';
    echo "✅ AdminAuth.php loaded\n";
    echo "isUserAdmin(): " . (isUserAdmin() ? 'TRUE' : 'FALSE') . "\n";
} catch (Throwable $e) {
    echo "❌ AdminAuth.php error: " . $e->getMessage() . "\n";
}

echo "\n=== Trying to instantiate AdminController ===\n";
try {
    $controller = new \App\Controllers\AdminController();
    echo "✅ AdminController instantiated\n";
    
    // Try dashboard method
    echo "\n=== Calling dashboard() ===\n";
    ob_start();
    $result = $controller->dashboard();
    $output = ob_get_clean();
    
    if ($result) {
        echo "✅ dashboard() returned output\n";
        echo "Output length: " . strlen($result) . " chars\n";
        echo "\nFirst 500 characters:\n";
        echo htmlspecialchars(substr($result, 0, 500)) . "\n";
    } else {
        echo "❌ dashboard() returned nothing\n";
    }
    
} catch (Throwable $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nFull trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "</pre>";
?>
