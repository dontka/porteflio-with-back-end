<?php

namespace App\Controllers;

use App\Core\BaseController;

/**
 * LoginController - Gère l'authentification et la page de connexion
 */
class LoginController extends BaseController
{
    /**
     * Afficher la page de connexion
     */
    public function showForm()
    {
        // Si l'utilisateur est déjà connecté, rediriger vers l'accueil
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . SYS_URL, true, 302);
            exit;
        }

        // Récupérer les fonctions utiles
        require_once INCLUDES_DIR . 'functions.php';

        $error = '';
        $systemUrl = SYS_URL;

        // Générer un token CSRF
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        echo $this->render('login', [
            'error' => $error,
            'systemUrl' => $systemUrl,
            'pageTitle' => 'Connexion — Donatien KANANE',
            'pageDescription' => 'Connectez-vous pour interagir avec les projets',
            'ogType' => 'website'
        ]);
    }

    /**
     * Traiter la soumission du formulaire de connexion
     */
    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SYS_URL . 'login', true, 302);
            exit;
        }

        // Vérification du token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $error = 'Token de sécurité invalide, veuillez réessayer';
        } elseif (empty($_POST['email'] ?? '') || empty($_POST['password'] ?? '')) {
            $error = 'Veuillez remplir tous les champs';
        } else {
            $email = $_POST['email'];
            $password = $_POST['password'];

            try {
                $stmt = $this->db->prepare('SELECT id, username, password FROM users WHERE email = ?');
                $stmt->execute([$email]);
                $user = $stmt->fetch(\PDO::FETCH_ASSOC);

                $passwordValid = false;
                if ($user) {
                    if (password_verify($password, $user['password'])) {
                        $passwordValid = true;
                    } elseif ($user['password'] === sha1($password)) {
                        // Migration: convertir SHA-1 vers bcrypt
                        $newHash = password_hash($password, PASSWORD_BCRYPT);
                        $updateStmt = $this->db->prepare('UPDATE users SET password = ? WHERE id = ?');
                        $updateStmt->execute([$newHash, $user['id']]);
                        $passwordValid = true;
                    }
                }

                if ($passwordValid) {
                    // Régénérer l'ID de session
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];

                    // Redirection sécurisée
                    $redirect = $_GET['redirect'] ?? SYS_URL;
                    $this->redirectSecure($redirect);
                    exit;
                } else {
                    $error = 'Email ou mot de passe incorrect';
                }
            } catch (\PDOException $e) {
                $error = 'Une erreur est survenue';
            }
        }

        // Récupérer les fonctions utiles
        require_once INCLUDES_DIR . 'functions.php';
        
        $systemUrl = SYS_URL;

        echo $this->render('login', [
            'error' => $error ?? '',
            'systemUrl' => $systemUrl,
            'pageTitle' => 'Connexion — Donatien KANANE',
            'pageDescription' => 'Connectez-vous pour interagir avec les projets',
            'ogType' => 'website'
        ]);
    }

    /**
     * Redirection sécurisée
     */
    private function redirectSecure($redirect)
    {
        $allowedPaths = [
            SYS_URL,
            SYS_URL . 'projet/',
            SYS_URL . 'blog/',
        ];

        foreach ($allowedPaths as $allowed) {
            if (strpos($redirect, $allowed) === 0) {
                header('Location: ' . $redirect, true, 302);
                return;
            }
        }

        header('Location: ' . SYS_URL, true, 302);
    }
}
