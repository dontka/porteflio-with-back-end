<?php

namespace App\Models;

class AdminUser extends BaseModel
{
    protected $table = 'users';

    /**
     * Récupérer un utilisateur admin par email
     */
    public function getAdminByEmail($email)
    {
        $query = "SELECT * FROM {$this->table} WHERE email = ? AND is_admin = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer tous les admins
     */
    public function getAllAdmins()
    {
        $query = "SELECT id, username, email, created_at FROM {$this->table} WHERE is_admin = 1 ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Promouvoir un utilisateur en admin
     */
    public function promoteToAdmin($id)
    {
        $query = "UPDATE {$this->table} SET is_admin = 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    /**
     * Retirer les droits admin
     */
    public function revokeAdmin($id)
    {
        $query = "UPDATE {$this->table} SET is_admin = 0 WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    /**
     * Vérifier si un utilisateur est admin
     */
    public function isAdmin($userId)
    {
        $query = "SELECT is_admin FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result && $result['is_admin'] == 1;
    }

    /**
     * Compter les utilisateurs
     */
    public function countUsers()
    {
        return $this->count();
    }

    /**
     * Compter les admins
     */
    public function countAdmins()
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE is_admin = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
}
