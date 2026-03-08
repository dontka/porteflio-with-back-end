<?php
require_once 'config.php';
session_start();
require_once 'includes/Database.php';
require_once 'includes/functions.php';

// Si l'utilisateur est déjà connecté, rediriger vers la page d'accueil
if (isUserLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $error = 'Token de sécurité invalide, veuillez réessayer';
    } elseif (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } else {
        $database = new Database();
        $db = $database->getConnection();
        
        try {
            $stmt = $db->prepare('SELECT id, username, password FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Support password_hash ET fallback SHA-1 (migration)
            $passwordValid = false;
            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $passwordValid = true;
                } elseif ($user['password'] === sha1($password)) {
                    // Migration: convertir SHA-1 vers bcrypt
                    $newHash = password_hash($password, PASSWORD_BCRYPT);
                    $updateStmt = $db->prepare('UPDATE users SET password = ? WHERE id = ?');
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
                $redirect = $_GET['redirect'] ?? 'index.php';
                $allowedPaths = ['index.php', 'project.php', 'blog.php'];
                $parsedPath = parse_url($redirect, PHP_URL_PATH);
                $baseName = basename($parsedPath ?? '');
                if (!in_array($baseName, $allowedPaths, true)) {
                    $redirect = 'index.php';
                }
                header('Location: ' . $redirect);
                exit;
            } else {
                $error = 'Email ou mot de passe incorrect';
            }
        } catch (PDOException $e) {
            if (isDebugMode()) {
                $error = 'Erreur de base de données : ' . $e->getMessage();
            } else {
                $error = 'Une erreur est survenue';
            }
        }
    }
}

// Générer un token CSRF
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="<?php echo substr(getDefaultLocale(), 0, 2); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests; default-src * 'self' data:; script-src * 'self' 'unsafe-inline' 'unsafe-eval'; style-src * 'self' 'unsafe-inline'; font-src * 'self' data:; img-src * 'self' data:; connect-src * 'self'; object-src 'none'">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — Donatien KANANE Portfolio</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.min.css">
</head>
<body class="login-page">
    
    <div class="login-wrapper">
        <!-- Background shapes -->
        <div class="login-bg-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
        
        <div class="login-container">
            <!-- Left branding panel -->
            <div class="login-brand-panel">
                <div class="login-brand-content">
                    <a href="index.php" class="login-brand-logo">DK<span>.</span></a>
                    <h2>Bienvenue</h2>
                    <p>Connectez-vous pour interagir avec les projets et laisser vos commentaires.</p>
                    <div class="login-brand-decoration">
                        <div class="brand-ring"></div>
                        <div class="brand-dots"></div>
                    </div>
                </div>
            </div>
            
            <!-- Right form panel -->
            <div class="login-form-panel">
                <div class="login-form-header">
                    <h3>Connexion</h3>
                    <p>Entrez vos identifiants pour continuer</p>
                </div>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <div class="form-floating-custom mb-3">
                        <label for="email">Email</label>
                        <div class="input-icon-wrapper">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Votre adresse email" autocomplete="email" required>
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                    <div class="form-floating-custom mb-3">
                        <label for="password">Mot de passe</label>
                        <div class="input-icon-wrapper">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe" autocomplete="current-password" required>
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary-custom btn-login w-100">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </button>
                </form>
                
                <div class="login-footer">
                    <a href="register.php"><i class="fas fa-user-plus"></i> Pas encore de compte ? S'inscrire</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/dark-mode-switch/dark-mode-switch.min.js"></script>
</body>
</html> 