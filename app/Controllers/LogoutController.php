<?php

namespace App\Controllers;

use App\Core\BaseController;

/**
 * LogoutController - Gère la déconnexion des utilisateurs
 */
class LogoutController extends BaseController
{
    /**
     * Déconnecter l'utilisateur
     */
    public function logout()
    {
        // Détruire la session
        session_destroy();
        
        // Rediriger vers l'accueil
        header('Location: ' . SYS_URL, true, 302);
        exit;
    }
}
