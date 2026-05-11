<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'utilisateur';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nom',
        'email',
        'password',
        'genre',
        'date_naissance',
        'is_gold',
        'role'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'date_inscription';
    protected $updatedField = false;

    protected $validationRules = [
        'nom' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|is_unique[utilisateur.email,id,{id}]',
        'password' => 'required|min_length[4]',
        'genre' => 'required|in_list[homme,femme]',
        'date_naissance' => 'required|valid_date',
    ];

    /**
     * Met à jour le statut Gold d'un utilisateur
     * @param int $userId
     * @param int $isGold (0 ou 1)
     * @return bool
     */
    public function setGoldStatus(int $userId, int $isGold): bool
    {
        // Requête directe pour contourner la validation
        $db = \Config\Database::connect();
        return $db->table('utilisateur')
            ->where('id', $userId)
            ->update(['is_gold' => $isGold]);
    }
}
