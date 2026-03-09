<?php

namespace App\Core;

/**
 * Router - Gère le routage des URLs vers les controllers
 */
class Router
{
    private $routes = [];
    private $notFoundCallback;

    /**
     * Enregistrer une route
     */
    public function add($method, $path, $controller, $action)
    {
        $pattern = $this->pathToRegex($path);
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'pattern' => $pattern,
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * Convertir un chemin en regex
     * /projet/{slug} => /^\/projet\/([a-zA-Z0-9_-]+)$/
     */
    private function pathToRegex($path)
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $path);
        return '/^' . str_replace('/', '\/', $pattern) . '$/';
    }

    /**
     * Dispatcher la requête vers le bon controller/action
     */
    public function dispatch($method = null, $path = null)
    {
        $method = $method ?? $_SERVER['REQUEST_METHOD'];
        $path = $path ?? $this->getPath();

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $path, $matches)) {
                // Extraire les paramètres nommés
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Instancier le controller
                $controllerClass = 'App\\Controllers\\' . $route['controller'];
                $controller = new $controllerClass();

                // Exécuter l'action
                $action = $route['action'];
                return $controller->$action($params);
            }
        }

        // Route non trouvée
        http_response_code(404);
        return $this->handleNotFound();
    }

    /**
     * Définir le callback pour 404
     */
    public function setNotFoundCallback($callback)
    {
        $this->notFoundCallback = $callback;
    }

    /**
     * Gérer les erreurs 404
     */
    private function handleNotFound()
    {
        if ($this->notFoundCallback) {
            return call_user_func($this->notFoundCallback);
        }

        return new \stdClass(); // Ou afficher une page 404
    }

    /**
     * Extraire le chemin de la requête
     * Supporte à la fois /projet/slug et /index.php/projet/slug
     */
    private function getPath()
    {
        // Priorité 1: Utiliser PATH_INFO s'il est disponible (quand accès direct à index.php)
        if (!empty($_SERVER['PATH_INFO'])) {
            $path = $_SERVER['PATH_INFO'];
        } else {
            // Priorité 2: Extraire du REQUEST_URI
            $request_uri = $_SERVER['REQUEST_URI'] ?? '/';
            
            // Retirer la base du chemin (ex: /porteflio-with-back-end)
            $base_path = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
            if ($base_path !== '/' && strpos($request_uri, $base_path) === 0) {
                $request_uri = substr($request_uri, strlen($base_path));
            }
            
            // Retirer /index.php si présent au début
            if (strpos($request_uri, '/index.php/') === 0) {
                $request_uri = substr($request_uri, strlen('/index.php'));
            }
            
            $path = explode('?', $request_uri)[0];
        }
        
        $path = rtrim($path, '/') ?: '/';
        return $path;
    }

    /**
     * Générer une URL pour une route
     */
    public function url($path, $params = [])
    {
        $url = $path;
        foreach ($params as $key => $value) {
            $url = str_replace("{$key}", $value, $url);
        }
        return SYS_URL . ltrim($url, '/');
    }
}
