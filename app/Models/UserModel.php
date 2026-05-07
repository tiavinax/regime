<?php 

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {

    protected $table = 'utilisateur';     
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'email', 'password', 'date_naissance', 'date_inscription']; 
    
    // Si vous voulez garder votre fonction personnalisée
    public function getUsers() {
        // Méthode 1: Query Builder
        return $this->findAll();
    }
}

?>