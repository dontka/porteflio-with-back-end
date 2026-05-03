<?php
/**
 * DEBUG - Ultra simple - Check if is_admin is 1
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR);
define('CONFIG_DIR', ROOT_DIR);
define('INCLUDES_DIR', ROOT_DIR . 'includes' . DIRECTORY_SEPARATOR);

echo "STARTING DEBUG<br>";
flush();

session_start();
require_once CONFIG_DIR . 'config.php';
require_once INCLUDES_DIR . 'Database.php';
require_once INCLUDES_DIR . 'AdminAuth.php';

echo "Session is_admin: " . ($_SESSION['is_admin'] ?? 'NOT SET') . "<br>";
echo "User ID: " . ($_SESSION['user_id'] ?? 'NOT SET') . "<br>";
flush();

if (!isset($_SESSION['user_id'])) {
    echo "❌ NOT LOGGED IN - Go to <a href='" . SYS_URL . "login'>login</a><br>";
    exit;
}

// Check database
try {
    $db = (new Database())->getConnection();
    $stmt = $db->prepare('SELECT is_admin FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    
    echo "DB is_admin value: " . ($result['is_admin'] ?? 'NOT FOUND') . "<br>";
    
    if ($result['is_admin'] != 1) {
        echo "❌ NOT ADMIN IN DATABASE! Must run: UPDATE users SET is_admin = 1 WHERE id = " . $_SESSION['user_id'] . "<br>";
        exit;
    }
} catch (Exception $e) {
    echo "❌ DB Error: " . $e->getMessage() . "<br>";
    exit;
}

echo "✅ READY - is_admin should be 1<br>";
echo "Try accessing: <a href='" . SYS_URL . "admin'>admin</a>";
?>
