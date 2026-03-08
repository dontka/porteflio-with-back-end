<?php
/**
 * Autoloader PSR-4 pour le projet
 * Charge automatiquement les classes depuis les namespaces App\Models, App\Controllers, etc.
 */

class Autoloader
{
    private static $prefixes = [];

    /**
     * Enregistre un préfixe de namespace avec son répertoire racine
     */
    public static function addNamespace($prefix, $base_dir)
    {
        self::$prefixes[$prefix] = $base_dir;
    }

    /**
     * Charge une classe basée sur son nom pleinement qualifié
     */
    public static function load($class)
    {
        foreach (self::$prefixes as $prefix => $base_dir) {
            // Si le nom de classe commence par le préfixe enregistré
            if (strpos($class, $prefix) === 0) {
                // Récupérer la portion relative du nom de classe
                $relative_class = substr($class, strlen($prefix));

                // Convertir le namespace en chemin de fichier
                $file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

                // Si le fichier existe, le charger
                if (file_exists($file)) {
                    require_once $file;
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Enregistrer l'autoloader
     */
    public static function register()
    {
        spl_autoload_register([self::class, 'load']);

        // Enregistrer les namespaces
        self::addNamespace('App\\', APP_DIR);
    }
}
