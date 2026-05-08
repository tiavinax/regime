<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{

    protected $table = 'utilisateur';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'email', 'password', 'date_naissance', 'date_inscription'];

    protected $validationRules = [
        'name' => 'required|min_length[3]',
        'email' => 'required|valid_email',
        'password' => 'required|min_length[8]',
    ];

    protected $validationMessages = [
        'email' => ['required' => 'Email obligatoire'],
    ];

    // Si vous voulez garder votre fonction personnalisée
    public function getUsers()
    {
        // Méthode 1: Query Builder
        return $this->findAll();
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
}
