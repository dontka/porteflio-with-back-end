<?php

/**
 * AdminAuth - Fonction de vérification du statut administrateur
 */

function isUserAdmin()
{
    return isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

function requireAdmin()
{
    if (!isUserAdmin()) {
        http_response_code(403);
        header('Location: ' . SYS_URL . 'login');
        exit;
    }
}

function getCurrentAdminUser()
{
    if (isUserAdmin()) {
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'email' => $_SESSION['email'] ?? null,
        ];
    }
    return null;
}

function logAdminAction($userId, $action, $details = '')
{
    try {
        global $db;
        if (!$db) {
            require_once CONFIG_DIR . 'config.php';
            require_once INCLUDES_DIR . 'Database.php';
            $database = new \Database();
            $db = $database->getConnection();
        }

        $query = "INSERT INTO admin_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $db->prepare($query);
        $stmt->execute([$userId, $action, $details]);
    } catch (\Exception $e) {
        // Silently fail if logging not available
    }
}
