<?php

namespace App\Models;

/**
 * BaseModel - Classe de base pour tous les models
 */
abstract class BaseModel
{
    protected $db;
    protected $table;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Récupérer tous les enregistrements
     */
    public function getAll($limit = null)
    {
        $query = "SELECT * FROM {$this->table}";
        if ($limit) {
            $query .= " LIMIT {$limit}";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer par ID
     */
    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un enregistrement par colonne
     */
    public function findBy($column, $value)
    {
        $query = "SELECT * FROM {$this->table} WHERE {$column} = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$value]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Créer un enregistrement
     */
    public function create(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->db->prepare($query);
        return $stmt->execute(array_values($data));
    }

    /**
     * Mettre à jour un enregistrement
     */
    public function update($id, array $data)
    {
        $sets = implode(', ', array_map(fn($k) => "{$k} = ?", array_keys($data)));
        $query = "UPDATE {$this->table} SET {$sets} WHERE id = ?";

        $stmt = $this->db->prepare($query);
        $values = array_merge(array_values($data), [$id]);
        return $stmt->execute($values);
    }

    /**
     * Supprimer un enregistrement
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    /**
     * Compter les enregistrements
     */
    public function count()
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
}
