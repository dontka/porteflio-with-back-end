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
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            $database = new Database();
            $db = $database->getConnection();

            try {
                $stmt = $db->prepare('SELECT id FROM users WHERE email = ? OR username = ?');
                $stmt->execute([$email, $username]);

                if ($stmt->fetch()) {
                    $error = 'Cet email ou nom d\'utilisateur est déjà utilisé';
                } else {
                    // Hacher le mot de passe avec bcrypt
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                    $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                    $stmt->execute([$username, $email, $hashedPassword]);

                    // Régénérer l'ID de session
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $db->lastInsertId();
                    $_SESSION['username'] = $username;

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
                }
            } catch (PDOException $e) {
                if (isDebugMode()) {
                    $error = 'Erreur de base de données : ' . $e->getMessage();
                } else {
                    $error = 'Une erreur est survenue, veuillez réessayer';
                }
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription — Donatien KANANE Portfolio</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">

    <!-- CSS -->
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
                    <h2>Rejoignez-nous</h2>
                    <p>Créez votre compte pour interagir avec les projets et partager vos commentaires.</p>
                    <div class="login-brand-decoration">
                        <div class="brand-ring"></div>
                        <div class="brand-dots"></div>
                    </div>
                </div>
            </div>

            <!-- Right form panel -->
            <div class="login-form-panel">
                <div class="login-form-header">
                    <h3>Inscription</h3>
                    <p>Créez votre compte en quelques secondes</p>
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
                        <label for="username">Nom d'utilisateur</label>
                        <div class="input-icon-wrapper">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Votre nom d'utilisateur" value="<?php echo htmlspecialchars($username ?? ''); ?>" autocomplete="username" required>
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div class="form-floating-custom mb-3">
                        <label for="email">Email</label>
                        <div class="input-icon-wrapper">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Votre adresse email" value="<?php echo htmlspecialchars($email ?? ''); ?>" autocomplete="email" required>
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                    <div class="form-floating-custom mb-3">
                        <label for="password">Mot de passe</label>
                        <div class="input-icon-wrapper">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Au moins 6 caractères" autocomplete="new-password" required>
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                    <div class="form-floating-custom mb-4">
                        <label for="password_confirm">Confirmer le mot de passe</label>
                        <div class="input-icon-wrapper">
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Répétez votre mot de passe" autocomplete="new-password" required>
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary-custom btn-login w-100">
                        <i class="fas fa-user-plus"></i> Créer mon compte
                    </button>
                </form>

                <div class="login-footer">
                    <a href="login.php"><i class="fas fa-sign-in-alt"></i> Déjà un compte ? Se connecter</a>
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
