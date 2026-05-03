<?php

namespace App\Controllers;

use App\Core\BaseController;

/**
 * APIController - Gère les requêtes API (commentaires, likes, etc.)
 */
class APIController extends BaseController
{
    /**
     * Acheminer vers l'API appropriée
     */
    public function route($params = [])
    {
        $this->checkDatabase();
        
        require_once INCLUDES_DIR . 'functions.php';

        // Récupérer l'action
        $action = $_GET['action'] ?? null;

        if (!$action) {
            return $this->json(['success' => false, 'error' => 'Action manquante'], 400);
        }

        // Récupérer les données JSON si POST
        $data = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content_type = $_SERVER['CONTENT_TYPE'] ?? '';
            if (stripos($content_type, 'application/json') !== false) {
                $raw_input = file_get_contents('php://input');
                if (!empty($raw_input)) {
                    $data = json_decode($raw_input, true) ?? [];
                }
            }
        }

        // Action routing
        switch ($action) {
            case 'comment':
                return $this->handleCommentAction($data);
            case 'like':
                return $this->handleLikeAction($data);
            case 'edit_comment':
                return $this->handleEditCommentAction($data);
            case 'delete_comment':
                return $this->handleDeleteCommentAction($data);
            default:
                return $this->json(['success' => false, 'error' => 'Action inconnue'], 400);
        }
    }

    /**
     * Ajouter un commentaire
     */
    private function handleCommentAction($data)
    {
        if (!isUserLoggedIn()) {
            return $this->json(['success' => false, 'error' => 'Connectez-vous pour commenter'], 403);
        }

        $project_url = $data['project_url'] ?? null;
        $blog_slug = $data['blog_slug'] ?? null;
        $content = isset($data['content']) ? trim($data['content']) : '';
        $parent_id = $data['parent_id'] ?? null;

        if (empty($content)) {
            return $this->json(['success' => false, 'error' => 'Le commentaire est vide'], 400);
        }

        if (strlen($content) > 1000) {
            return $this->json(['success' => false, 'error' => 'Le commentaire dépasse 1000 caractères'], 400);
        }

        $user_id = $_SESSION['user_id'] ?? null;
        $username = $_SESSION['username'] ?? 'Utilisateur';

        try {
            if ($project_url) {
                addProjectComment($this->db, $project_url, $user_id, $content, $parent_id);
            } elseif ($blog_slug) {
                addBlogComment($this->db, $blog_slug, $user_id, $content, $parent_id);
            } else {
                return $this->json(['success' => false, 'error' => 'Contexte invalide'], 400);
            }

            $comment_id = $this->db->lastInsertId();
            
            return $this->json(['success' => true, 'comment' => [
                'id' => (int)$comment_id,
                'username' => $username,
                'content' => $content,
                'created_at' => date('Y-m-d H:i:s'),
                'user_liked' => false
            ]]);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Liker/Unliker un commentaire
     */
    private function handleLikeAction($data)
    {
        if (!isUserLoggedIn()) {
            return $this->json(['success' => false, 'error' => 'Connectez-vous pour liker'], 403);
        }

        $comment_id = $data['comment_id'] ?? null;
        if (!$comment_id) {
            return $this->json(['success' => false, 'error' => 'ID commentaire manquant'], 400);
        }

        try {
            $user_id = $_SESSION['user_id'];
            
            // Vérifier si l'utilisateur a déjà liké ce commentaire
            $stmt = $this->db->prepare("SELECT id FROM comment_likes WHERE comment_id = ? AND user_id = ?");
            $stmt->execute([$comment_id, $user_id]);
            $existing_like = $stmt->fetch();

            if ($existing_like) {
                // Retirer le like
                $stmt = $this->db->prepare("DELETE FROM comment_likes WHERE comment_id = ? AND user_id = ?");
                $stmt->execute([$comment_id, $user_id]);
                $liked = false;
            } else {
                // Ajouter le like
                $stmt = $this->db->prepare("INSERT INTO comment_likes (comment_id, user_id) VALUES (?, ?)");
                $stmt->execute([$comment_id, $user_id]);
                $liked = true;
            }

            // Compter les likes
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM comment_likes WHERE comment_id = ?");
            $stmt->execute([$comment_id]);
            $count = (int)$stmt->fetchColumn();

            return $this->json(['success' => true, 'liked' => $liked, 'count' => $count]);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Modifier un commentaire
     */
    private function handleEditCommentAction($data)
    {
        if (!isUserLoggedIn()) {
            return $this->json(['success' => false, 'error' => 'Connectez-vous pour modifier'], 403);
        }

        $comment_id = $data['comment_id'] ?? null;
        $content = isset($data['content']) ? trim($data['content']) : '';

        if (!$comment_id || empty($content)) {
            return $this->json(['success' => false, 'error' => 'Données manquantes'], 400);
        }

        if (strlen($content) > 1000) {
            return $this->json(['success' => false, 'error' => 'Le commentaire dépasse 1000 caractères'], 400);
        }

        try {
            $user_id = $_SESSION['user_id'];

            // Vérifier que c'est bien l'auteur
            $stmt = $this->db->prepare("SELECT user_id FROM comments WHERE id = ?");
            $stmt->execute([$comment_id]);
            $comment = $stmt->fetch();

            if (!$comment || $comment['user_id'] != $user_id) {
                return $this->json(['success' => false, 'error' => 'Vous ne pouvez modifier que vos propres commentaires'], 403);
            }

            // Modifier le commentaire
            $stmt = $this->db->prepare("UPDATE comments SET content = ? WHERE id = ?");
            $stmt->execute([$content, $comment_id]);

            return $this->json(['success' => true, 'content' => $content]);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Supprimer un commentaire
     */
    private function handleDeleteCommentAction($data)
    {
        if (!isUserLoggedIn()) {
            return $this->json(['success' => false, 'error' => 'Connectez-vous pour supprimer'], 403);
        }

        $comment_id = $data['comment_id'] ?? null;
        if (!$comment_id) {
            return $this->json(['success' => false, 'error' => 'ID commentaire manquant'], 400);
        }

        try {
            $user_id = $_SESSION['user_id'];

            // Vérifier que c'est bien l'auteur
            $stmt = $this->db->prepare("SELECT user_id FROM comments WHERE id = ?");
            $stmt->execute([$comment_id]);
            $comment = $stmt->fetch();

            if (!$comment || $comment['user_id'] != $user_id) {
                return $this->json(['success' => false, 'error' => 'Vous ne pouvez supprimer que vos propres commentaires'], 403);
            }

            // Supprimer les likes associés
            $stmt = $this->db->prepare("DELETE FROM comment_likes WHERE comment_id = ?");
            $stmt->execute([$comment_id]);

            // Supprimer les réponses
            $stmt = $this->db->prepare("DELETE FROM comments WHERE parent_id = ?");
            $stmt->execute([$comment_id]);

            // Supprimer le commentaire
            $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ?");
            $stmt->execute([$comment_id]);

            return $this->json(['success' => true, 'message' => 'Commentaire supprimé']);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
