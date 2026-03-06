<?php
session_start();
header('Content-Type: application/json');
require_once '../config.php';
require_once 'Database.php';
require_once 'functions.php';

// Vérifier si l'utilisateur est connecté
if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Vous devez être connecté pour commenter']);
    exit;
}

// Vérifier la méthode de la requête
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

// Récupérer les données
$data = json_decode(file_get_contents('php://input'), true);
$project_url = $data['project_url'] ?? '';
$blog_slug = $data['blog_slug'] ?? '';
$content = $data['content'] ?? '';
$parent_id = isset($data['parent_id']) ? (int)$data['parent_id'] : null;

// Valider les données
if ((empty($project_url) && empty($blog_slug)) || empty($content)) {
    http_response_code(400);
    echo json_encode(['error' => 'Données manquantes']);
    exit;
}

if (mb_strlen($content) > 1000) {
    http_response_code(400);
    echo json_encode(['error' => 'Commentaire trop long (max 1000 caractères)']);
    exit;
}

// Initialiser la connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Ajouter le commentaire
if (!empty($blog_slug)) {
    $success = addBlogComment($db, $blog_slug, $_SESSION['user_id'], $content, $parent_id);
} else {
    $success = addProjectComment($db, $project_url, $_SESSION['user_id'], $content, $parent_id);
}

if ($success) {
    // Récupérer le nouveau commentaire
    $stmt = $db->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.id = :id");
    $stmt->execute([':id' => $db->lastInsertId()]);
    $newComment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'comment' => [
            'id' => $newComment['id'],
            'parent_id' => $newComment['parent_id'],
            'content' => $newComment['content'],
            'username' => $newComment['username'],
            'created_at' => $newComment['created_at'],
            'likes_count' => 0,
            'user_liked' => false
        ]
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de l\'ajout du commentaire']);
} 