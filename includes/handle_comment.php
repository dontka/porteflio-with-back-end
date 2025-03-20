<?php
session_start();
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
$content = $data['content'] ?? '';

// Valider les données
if (empty($project_url) || empty($content)) {
    http_response_code(400);
    echo json_encode(['error' => 'Données manquantes']);
    exit;
}

// Initialiser la connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Ajouter le commentaire
$success = addProjectComment($db, $project_url, $_SESSION['user_id'], $content);

if ($success) {
    // Récupérer le nouveau commentaire
    $comments = getProjectComments($db, $project_url);
    $newComment = $comments[0]; // Le plus récent
    
    echo json_encode([
        'success' => true,
        'comment' => [
            'id' => $newComment['id'],
            'content' => htmlspecialchars($newComment['content']),
            'username' => htmlspecialchars($newComment['username']),
            'created_at' => date('d/m/Y H:i', strtotime($newComment['created_at']))
        ]
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de l\'ajout du commentaire']);
} 