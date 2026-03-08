<?php

namespace App\Models;

class Profile extends BaseModel
{
    protected $table = 'profile';

    /**
     * Récupérer les données du profil
     */
    public function getProfile()
    {
        return $this->getAll(1)[0] ?? null;
    }
}
