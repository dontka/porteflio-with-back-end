<?php

namespace App\Models;

class Project extends BaseModel
{
    protected $table = 'projects';

    /**
     * Récupérer tous les projets
     */
    public function getProjects($limit = null)
    {
        return $this->getAll($limit);
    }

    /**
     * Récupérer un projet par slug
     */
    public function getBySlug($slug)
    {
        $slug = preg_replace('/[^a-zA-Z0-9_-]/', '', $slug);
        return $this->findBy('slug', $slug);
    }

    /**
     * Récupérer les projets en vedette
     */
    public function getFeatured()
    {
        $query = "SELECT * FROM {$this->table} WHERE is_featured = 1 ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
