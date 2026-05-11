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
        'date_naissance' => 'required|valid_date'
    ];

     /**
     * Vérifie si un utilisateur est administrateur
     * @param int $userId
     * @return bool
     */
    public function isAdmin(int $userId): bool
    {
        $user = $this->find($userId);
        return $user && isset($user['role']) && $user['role'] === 'admin';
    }

    //    /**
    //  * Vérifie les identifiants d'un utilisateur
    //  * @param string $email
    //  * @param string $password
    //  * @return array|null
    //  */
    // public function verifyCredentials(string $email, string $password): ?array
    // {
    //     $user = $this->where('email', $email)->first();

    //     if ($user && password_verify($password, $user['password'])) {
    //         return $user;
    //     }

    //     return null;
    // }
}
