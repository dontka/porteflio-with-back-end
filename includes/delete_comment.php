<?php
session_start();
require_once '../config.php';
require_once 'Database.php';
require_once 'functions.php';

// Vérifier si l'utilisateur est connecté
if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Vous devez être connecté pour supprimer un commentaire']);
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
$comment_id = $data['comment_id'] ?? 0;

// Valider les données
if (empty($comment_id)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de commentaire manquant']);
    exit;
}

// Initialiser la connexion à la base de données
$database = new Database();
$db = $database->getConnection();

try {
    // Vérifier que l'utilisateur est bien l'auteur du commentaire
    $stmt = $db->prepare('SELECT user_id FROM comments WHERE id = ?');
    $stmt->execute([$comment_id]);
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$comment) {
        http_response_code(404);
        echo json_encode(['error' => 'Commentaire non trouvé']);
        exit;
    }
    
    if ($comment['user_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Vous n\'êtes pas autorisé à supprimer ce commentaire']);
        exit;
    }
    
    // Supprimer le commentaire
    $stmt = $db->prepare('DELETE FROM comments WHERE id = ?');
    $success = $stmt->execute([$comment_id]);
    
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la suppression du commentaire']);
    }
} catch (PDOException $e) {
    if (isDebugMode()) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur de base de données : ' . $e->getMessage()]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Une erreur est survenue']);
    }
} 