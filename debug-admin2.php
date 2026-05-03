<?php
/**
 * DEBUG FILE 2 - Test Admin Controller directly
 * Access: http://localhost/afiazone/debug-admin2.php
 * DELETE THIS FILE WHEN DONE
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

// Charger AdminAuth
require_once INCLUDES_DIR . 'AdminAuth.php';

echo "<h1>🔍 DEBUG 2 - Admin Controller Test</h1>";
echo "<pre style='background: #f5f5f5; padding: 20px; border-radius: 5px;'>";

// Check isUserAdmin
echo "=== STEP 1: Check isUserAdmin() ===\n";
echo "isUserAdmin(): " . (isUserAdmin() ? 'TRUE ✅' : 'FALSE ❌') . "\n\n";

if (!isUserAdmin()) {
    echo "❌ NOT ADMIN! Cannot proceed.\n";
    echo "is_admin value: " . ($_SESSION['is_admin'] ?? 'NOT SET') . "\n";
    echo "\nYou need to run: UPDATE users SET is_admin = 1 WHERE id = " . ($_SESSION['user_id'] ?? 'N/A') . "\n";
    echo "</pre>";
    exit;
}

// Try to instantiate AdminController
echo "=== STEP 2: Instantiate AdminController ===\n";
try {
    $controller = new \App\Controllers\AdminController();
    echo "✅ AdminController instantiated\n\n";
} catch (Throwable $e) {
    echo "❌ Error instantiating AdminController:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    echo "</pre>";
    exit;
}

// Try to call dashboard method
echo "=== STEP 3: Call dashboard() method ===\n";
try {
    $result = $controller->dashboard();
    echo "✅ dashboard() executed\n";
    echo "Result type: " . gettype($result) . "\n";
    echo "Result length: " . strlen($result) . " characters\n";
    
    if ($result) {
        echo "First 200 chars:\n";
        echo htmlspecialchars(substr($result, 0, 200)) . "\n";
    } else {
        echo "⚠️  Result is empty!\n";
    }
} catch (Throwable $e) {
    echo "❌ Error calling dashboard():\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";
echo "<hr>";
echo "<a href='" . SYS_URL . "admin'>← Try /admin directly</a>";
?>
