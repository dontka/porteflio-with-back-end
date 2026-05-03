<?php
/**
 * DEBUG FILE - Check admin session state
 * Access: http://localhost/afiazone/debug-admin.php
 * DELETE THIS FILE WHEN DONE
 */

// Définir les constantes de chemins
define('ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR);
define('APP_DIR', ROOT_DIR . 'app' . DIRECTORY_SEPARATOR);
define('CONFIG_DIR', ROOT_DIR);
define('INCLUDES_DIR', ROOT_DIR . 'includes' . DIRECTORY_SEPARATOR);

// Charger la configuration
require_once CONFIG_DIR . 'config.php';

// Démarrer la session
session_start();

// Charger AdminAuth
require_once INCLUDES_DIR . 'AdminAuth.php';

// Charger la base de données
require_once INCLUDES_DIR . 'Database.php';
$database = new Database();
$db = $database->getConnection();

// Debug Output
echo "<h1>🔍 DEBUG - Admin Session State</h1>";
echo "<pre style='background: #f5f5f5; padding: 20px; border-radius: 5px;'>";

// 1. Session State
echo "=== SESSION DATA ===\n";
echo "Session ID: " . session_id() . "\n";
echo "User ID: " . ($_SESSION['user_id'] ?? 'NOT SET') . "\n";
echo "Username: " . ($_SESSION['username'] ?? 'NOT SET') . "\n";
echo "Email: " . ($_SESSION['email'] ?? 'NOT SET') . "\n";
echo "Is Admin: " . ($_SESSION['is_admin'] ?? 'NOT SET') . "\n";
echo "\n";

// 2. Test isUserAdmin function
echo "=== FUNCTION TEST ===\n";
echo "isUserAdmin() returns: " . (isUserAdmin() ? 'TRUE ✅' : 'FALSE ❌') . "\n";
echo "\n";

// 3. Check Database
if ($_SESSION['user_id'] ?? null) {
    echo "=== DATABASE CHECK ===\n";
    try {
        $stmt = $db->prepare('SELECT id, username, email, password, is_admin FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $dbUser = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($dbUser) {
            echo "User found in DB:\n";
            echo "  - ID: " . $dbUser['id'] . "\n";
            echo "  - Username: " . $dbUser['username'] . "\n";
            echo "  - Email: " . $dbUser['email'] . "\n";
            echo "  - is_admin in DB: " . $dbUser['is_admin'] . "\n";
            echo "  - Matches session is_admin? " . (($dbUser['is_admin'] == $_SESSION['is_admin']) ? 'YES ✅' : 'NO ❌') . "\n";
        } else {
            echo "ERROR: User NOT found in database!\n";
        }
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

// 4. All SESSION vars
echo "=== FULL SESSION ARRAY ===\n";
print_r($_SESSION);
echo "\n";

// 5. Instructions
echo "=== INSTRUCTIONS ===\n";
echo "If is_admin is NOT SET:\n";
echo "  1. You must LOGOUT completely\n";
echo "  2. Clear browser cookies for localhost\n";
echo "  3. Login again\n";
echo "\n";
echo "If is_admin IS SET but is 0:\n";
echo "  UPDATE users SET is_admin = 1 WHERE id = " . ($_SESSION['user_id'] ?? 'N/A') . "\n";
echo "</pre>";

echo "<hr>";
echo "<a href='" . SYS_URL . "'>← Back to home</a>";
echo " | ";
echo "<a href='" . SYS_URL . "logout'>Logout</a>";
?>
