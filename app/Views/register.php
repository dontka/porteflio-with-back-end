<?php
/**
 * REGISTER.PHP - Page d'inscription
 * Rendue par RegisterController
 * Variables disponibles: $error, $systemUrl
 */
?>
<!DOCTYPE html>
<html lang="<?php echo substr(getDefaultLocale(), 0, 2); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests; default-src * 'self' data:; script-src * 'self' 'unsafe-inline' 'unsafe-eval'; style-src * 'self' 'unsafe-inline'; font-src * 'self' data:; img-src * 'self' data:; connect-src * 'self'; object-src 'none'">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription — Donatien KANANE Portfolio</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/fontawesome/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/css/styles.min.css">
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
                    <a href="<?php echo $systemUrl; ?>" class="login-brand-logo">DK<span>.</span></a>
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

                <form method="POST" action="<?php echo $systemUrl; ?>register">
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
                    <a href="<?php echo $systemUrl; ?>login"><i class="fas fa-sign-in-alt"></i> Déjà un compte ? Se connecter</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?php echo $systemUrl; ?>assets/plugins/popper.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo $systemUrl; ?>assets/plugins/dark-mode-switch/dark-mode-switch.min.js"></script>
</body>
</html>
