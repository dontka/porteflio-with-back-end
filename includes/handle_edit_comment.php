<?php
session_start();
header('Content-Type: application/json');
require_once '../config.php';
require_once 'Database.php';
require_once 'functions.php';

if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Vous devez être connecté']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$comment_id = isset($data['comment_id']) ? (int)$data['comment_id'] : 0;
$content = isset($data['content']) ? trim($data['content']) : '';

if (!$comment_id || empty($content)) {
    http_response_code(400);
    echo json_encode(['error' => 'Données manquantes']);
    exit;
}

if (mb_strlen($content) > 1000) {
    http_response_code(400);
    echo json_encode(['error' => 'Commentaire trop long (max 1000 caractères)']);
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Verify ownership
$stmt = $db->prepare("SELECT user_id FROM comments WHERE id = :id");
$stmt->execute([':id' => $comment_id]);
$comment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comment) {
    http_response_code(404);
    echo json_encode(['error' => 'Commentaire non trouvé']);
    exit;
}

if ($comment['user_id'] != $_SESSION['user_id']) {
    http_response_code(403);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

$success = editComment($db, $comment_id, $_SESSION['user_id'], $content);

if ($success) {
    echo json_encode([
        'success' => true,
        'content' => $content,
        'updated_at' => date('Y-m-d H:i:s')
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la modification']);
}
