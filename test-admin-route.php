<?php
/**
 * DEBUG - Simulate /admin route directly
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

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

echo "<pre>";
echo "=== DEBUG: Simulating /admin route ===\n\n";

// Check session
echo "Session user_id: " . ($_SESSION['user_id'] ?? 'NOT SET') . "\n";
echo "Session is_admin: " . ($_SESSION['is_admin'] ?? 'NOT SET') . "\n\n";

if (!isset($_SESSION['user_id'])) {
    echo "❌ NOT LOGGED IN\n";
    echo "</pre>";
    exit;
}

// Load AdminAuth and check
require_once INCLUDES_DIR . 'AdminAuth.php';
echo "isUserAdmin(): " . (isUserAdmin() ? 'TRUE ✅' : 'FALSE ❌') . "\n\n";

if (!isUserAdmin()) {
    echo "Checking database...\n";
    try {
        $db = (new \Database())->getConnection();
        $stmt = $db->prepare('SELECT id, is_admin FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "User found, is_admin in DB: " . $user['is_admin'] . "\n";
            echo "\nRun this SQL:\n";
            echo "UPDATE users SET is_admin = 1 WHERE id = " . $user['id'] . ";\n";
        } else {
            echo "User not found in DB\n";
        }
    } catch (Exception $e) {
        echo "DB Error: " . $e->getMessage() . "\n";
    }
    echo "</pre>";
    exit;
}

// Now try to instantiate and call AdminController
echo "Creating AdminController...\n";
try {
    $controller = new \App\Controllers\AdminController();
    echo "✅ AdminController created\n\n";
    
    // Call dashboard
    echo "Calling dashboard()...\n";
    $output = $controller->dashboard();
    echo "✅ Output generated: " . strlen($output) . " bytes\n\n";
    
    echo "OUTPUT (first 1000 chars):\n";
    echo htmlspecialchars(substr($output, 0, 1000)) . "\n";
    
} catch (Throwable $e) {
    echo "❌ ERROR:\n";
    echo $e->getMessage() . "\n\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";
?>
