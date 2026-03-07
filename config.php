<?php  
    define("DB_NAME", 'portfolio');
    define("DB_USER", 'root');
    define("DB_PASSWORD", '');
    define("DB_HOST", 'localhost');
    define("DB_PORT", '3306');
    
    // Détecte automatiquement le domaine (localhost vs production)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $basePath = dirname($_SERVER['SCRIPT_NAME']);
    if ($basePath === '\\' || $basePath === '/') {
        $basePath = '/';
    } else {
        $basePath = rtrim($basePath, '/') . '/';
    }
    define("SYS_URL", $protocol . '://' . $host . $basePath);
    
    define("DEBUGGING", false);
    define("DEFAULT_LOCALE", 'fr_FR');
    define("LICENCE_KEY", 'Dontka243');

    // Session cookie security
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.use_strict_mode', 1);
    
    ?>