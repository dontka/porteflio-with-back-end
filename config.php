<?php  
    // Déterminer le mode (local vs production)
    // Check multiple indicators: HTTP_HOST, SERVER_NAME, or check if in Laragon directory
    $isLocalhost = false;
    
    // Check HTTP_HOST for localhost indicators
    if (in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', 'localhost:80', 'localhost:8080', 'localhost:3000']) ||
        strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') === 0 ||
        strpos($_SERVER['SERVER_NAME'] ?? '', 'localhost') === 0) {
        $isLocalhost = true;
    }
    
    // If running from CLI or HTTP_HOST not available, check if we're in c:\laragon directory
    if (!$isLocalhost && (php_sapi_name() === 'cli' || empty($_SERVER['HTTP_HOST']))) {
        $currentDir = realpath(__DIR__);
        if (stripos($currentDir, 'laragon') !== false) {
            $isLocalhost = true;
        }
    }
    
    // Configuration de la base de données
    if ($isLocalhost) {
        // Configuration locale (Laragon)
        define("DB_NAME", 'portfolio');
        define("DB_USER", 'root');
        define("DB_PASSWORD", '');
        define("DB_HOST", 'localhost');
        define("DB_PORT", '3306');
    } else {
        // Configuration production (InfinityFree)
        define("DB_NAME", 'if0_41320885_dontka');
        define("DB_USER", 'if0_41320885');
        define("DB_PASSWORD", 'Afiatalk243');
        define("DB_HOST", 'sql100.infinityfree.com');
        define("DB_PORT", '3306');
    }
    
    // Détecte automatiquement le domaine (localhost vs production)
    if ($isLocalhost) {
        // Configuration locale: toujours /porteflio-with-back-end/
        $protocol = 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $basePath = '/porteflio-with-back-end/';
    } else {
        // Configuration production: détection automatique du chemin
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $basePath = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
        if ($basePath === '\\' || $basePath === '/' || $basePath === '.') {
            $basePath = '/';
        } else {
            $basePath = rtrim($basePath, '/') . '/';
        }
    }
    
    define("SYS_URL", $protocol . '://' . $host . $basePath);
    
    define("DEBUGGING", true);
    define("DEFAULT_LOCALE", 'fr_FR');
    define("LICENCE_KEY", 'Dontka243');

    // Session cookie security
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.use_strict_mode', 1);
    
    ?>