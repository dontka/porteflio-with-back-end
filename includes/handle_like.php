<?php
require_once '../config.php';
session_start();
header('Content-Type: application/json');
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

if (!$comment_id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID commentaire manquant']);
    exit;
}

$database = new Database();
$db = $database->getConnection();

try {
    $result = toggleCommentLike($db, $comment_id, $_SESSION['user_id']);
    echo json_encode(['success' => true, 'liked' => $result['liked'], 'count' => $result['count']]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur']);
}
