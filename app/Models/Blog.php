<?php

namespace App\Models;

class Blog extends BaseModel
{
    protected $table = 'blog_posts';

    /**
     * Récupérer tous les articles
     */
    public function getPosts($limit = null)
    {
        return $this->getAll($limit);
    }

    /**
     * Récupérer un article par slug
     */
    public function getBySlug($slug)
    {
        $slug = preg_replace('/[^a-zA-Z0-9_-]/', '', $slug);
        return $this->findBy('slug', $slug);
    }

    /**
     * Récupérer les articles récents
     */
    public function getRecent($limit = 6)
    {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT {$limit}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
