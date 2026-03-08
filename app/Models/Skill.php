<?php

namespace App\Models;

class Skill extends BaseModel
{
    protected $table = 'skills';

    /**
     * Récupérer toutes les compétences
     */
    public function getSkills()
    {
        return $this->getAll();
    }

    /**
     * Récupérer les compétences par catégorie
     */
    public function getByCategory($category)
    {
        $query = "SELECT * FROM {$this->table} WHERE category = ? ORDER BY name";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$category]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Grouper les compétences par catégorie
     */
    public function groupByCategory()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY category, name";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $skills = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $grouped = [];
        foreach ($skills as $skill) {
            $cat = $skill['category'] ?? 'Autres';
            if (!isset($grouped[$cat])) {
                $grouped[$cat] = [];
            }
            $grouped[$cat][] = $skill;
        }

        return $grouped;
    }

    /**
     * Calculer le niveau moyen
     */
    public function getAverageLevel()
    {
        $query = "SELECT AVG(level) as avg_level FROM {$this->table}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return round($result['avg_level'] ?? 0);
    }
}
