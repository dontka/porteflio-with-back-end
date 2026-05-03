<?php
/**
 * TEST - Verify index.php can be accessed directly via PHP
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simulate being called as: php index.php
// Set up environment
$_SERVER['REQUEST_URI'] = '/afiazone/admin';
$_SERVER['SCRIPT_NAME'] = '/afiazone/index.php';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['PATH_INFO'] = '/admin';
$_SERVER['HTTP_HOST'] = 'localhost';

echo "<h1>Testing index.php directly</h1>";
echo "<pre>";

// Try to include and run actual index.php
try {
    echo "Including real index.php...\n\n";
    ob_start();
    include __DIR__ . '/index.php';
    $output = ob_get_clean();
    
    if ($output) {
        echo "✅ Output received: " . strlen($output) . " bytes\n\n";
        echo htmlspecialchars(substr($output, 0, 1000)) . "\n";
    } else {
        echo "❌ No output\n";
    }
} catch (Throwable $e) {
    echo "❌ Error:\n";
    echo $e->getMessage() . "\n";
}

echo "</pre>";
?>
