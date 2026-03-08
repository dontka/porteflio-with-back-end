<?php

namespace App\Core;

/**
 * View - Gère le rendu des templates
 */
class View
{
    private $templateDir;
    private $data = [];

    public function __construct($templateDir = null)
    {
        $this->templateDir = $templateDir ?? APP_DIR . 'Views' . DIRECTORY_SEPARATOR;
    }

    /**
     * Assigner des variables à la vue
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

    /**
     * Rendre un template
     */
    public function render($template, $data = [])
    {
        $data = array_merge($this->data, $data);
        $file = $this->templateDir . $template . '.php';

        if (!file_exists($file)) {
            throw new \Exception("Template not found: {$file}");
        }

        // Rendre l'instance View disponible dans les templates
        $view = $this;
        extract($data);
        ob_start();
        require $file;
        return ob_get_clean();
    }

    /**
     * Afficher directement un template
     */
    public function display($template, $data = [])
    {
        echo $this->render($template, $data);
    }

    /**
     * Inclure un template partiel avec toutes les données disponibles
     */
    public function include($template, $data = [])
    {
        // Fusionner les données globales avec les données spécifiques
        $mergedData = array_merge($this->data, $data);
        echo $this->render($template, $mergedData);
    }

    /**
     * Rendre un template avec un layout
     */
    public function renderWithLayout($layout, $template, $data = [])
    {
        $data = array_merge($this->data, $data);
        
        // Mettre à jour les données internes pour que les includes aient accès à toutes les variables
        $originalData = $this->data;
        $this->data = $data;

        // Rendre le contenu du template
        $file = $this->templateDir . $template . '.php';
        if (!file_exists($file)) {
            throw new \Exception("Template not found: {$file}");
        }

        extract($data);
        $view = $this;
        ob_start();
        require $file;
        $content = ob_get_clean();

        // Rendre le layout avec le contenu
        // NOTE: Keep $this->data = $data so layout's includes have access to all variables
        $layoutData = $data;
        $layoutData['content'] = $content;
        
        $layoutFile = $this->templateDir . $layout . '.php';
        if (!file_exists($layoutFile)) {
            throw new \Exception("Layout not found: {$layoutFile}");
        }

        extract($layoutData);
        $view = $this;
        ob_start();
        require $layoutFile;
        $output = ob_get_clean();
        
        // Restaurer les données originales après rendu complet
        $this->data = $originalData;
        
        return $output;
    }

    /**
     * Afficher directement avec un layout
     */
    public function displayWithLayout($layout, $template, $data = [])
    {
        echo $this->renderWithLayout($layout, $template, $data);
    }
}
