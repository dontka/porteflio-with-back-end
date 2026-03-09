<?php

namespace App\Controllers;

use App\Core\BaseController;

/**
 * RegisterController - Gère l'inscription des utilisateurs
 */
class RegisterController extends BaseController
{
    /**
     * Afficher la page d'inscription
     */
    public function showForm()
    {
        // Si l'utilisateur est déjà connecté, rediriger vers l'accueil
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . SYS_URL, true, 302);
            exit;
        }

        require_once INCLUDES_DIR . 'functions.php';

        $error = '';
        $systemUrl = SYS_URL;

        // Générer un token CSRF
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        echo $this->render('register', [
            'error' => $error,
            'systemUrl' => $systemUrl,
            'pageTitle' => 'Inscription — Donatien KANANE',
            'pageDescription' => 'Créez votre compte pour interagir',
            'ogType' => 'website'
        ]);
    }

    /**
     * Traiter la soumission du formulaire d'inscription
     */
    public function handleRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . SYS_URL . 'register', true, 302);
            exit;
        }

        // Vérification du token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $error = 'Token de sécurité invalide, veuillez réessayer';
        } else {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';

            if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
                $error = 'Veuillez remplir tous les champs';
            } elseif (mb_strlen($username) < 3 || mb_strlen($username) > 50) {
                $error = 'Le nom d\'utilisateur doit contenir entre 3 et 50 caractères';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Veuillez entrer une adresse email valide';
            } elseif (mb_strlen($password) < 6) {
                $error = 'Le mot de passe doit contenir au moins 6 caractères';
            } elseif ($password !== $password_confirm) {
                $error = 'Les mots de passe ne correspondent pas';
            } else {
                try {
                    $stmt = $this->db->prepare('SELECT id FROM users WHERE email = ? OR username = ?');
                    $stmt->execute([$email, $username]);

                    if ($stmt->fetch()) {
                        $error = 'Cet email ou nom d\'utilisateur est déjà utilisé';
                    } else {
                        // Hacher le mot de passe avec bcrypt
                        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                        $stmt = $this->db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                        $stmt->execute([$username, $email, $hashedPassword]);

                        // Régénérer l'ID de session
                        session_regenerate_id(true);
                        $_SESSION['user_id'] = $this->db->lastInsertId();
                        $_SESSION['username'] = $username;

                        // Redirection sécurisée
                        $redirect = $_GET['redirect'] ?? SYS_URL;
                        $this->redirectSecure($redirect);
                        exit;
                    }
                } catch (\PDOException $e) {
                    $error = 'Une erreur est survenue, veuillez réessayer';
                }
            }
        }

        require_once INCLUDES_DIR . 'functions.php';
        
        $systemUrl = SYS_URL;

        echo $this->render('register', [
            'error' => $error ?? '',
            'systemUrl' => $systemUrl,
            'pageTitle' => 'Inscription — Donatien KANANE',
            'pageDescription' => 'Créez votre compte pour interagir',
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
