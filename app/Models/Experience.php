<?php

namespace App\Models;

class Experience extends BaseModel
{
    protected $table = 'experience';

    /**
     * Récupérer toutes les expériences
     */
    public function getExperience()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY start_date DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
