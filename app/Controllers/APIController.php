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
        
        session_start();

        // Récupérer l'action
        $action = $_GET['action'] ?? null;

        if (!$action) {
            $this->json(['error' => 'Missing action'], 400);
        }

        // Le fichier API.php s'attend à avoir $db et les functions disponibles
        require_once INCLUDES_DIR . 'functions.php';

        // Action mapping
        switch ($action) {
            case 'comment':
                return $this->handleCommentAction();
            case 'like':
                return $this->handleLikeAction();
            case 'edit_comment':
                return $this->handleEditCommentAction();
            case 'delete_comment':
                return $this->handleDeleteCommentAction();
            default:
                $this->json(['error' => 'Unknown action'], 400);
        }
    }

    private function handleCommentAction()
    {
        require_once INCLUDES_DIR . 'handle_comment.php';
    }

    private function handleLikeAction()
    {
        require_once INCLUDES_DIR . 'handle_like.php';
    }

    private function handleEditCommentAction()
    {
        require_once INCLUDES_DIR . 'handle_edit_comment.php';
    }

    private function handleDeleteCommentAction()
    {
        require_once INCLUDES_DIR . 'delete_comment.php';
    }
}
