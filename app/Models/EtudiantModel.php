<?php 
namespace App\Models;

use CodeIgniter\Model;

class EtudiantModel extends Model {
    protected $table = 'etudiants';      // Nom de votre table
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'prenom']; // Adaptez selon votre table
    
    // Si vous voulez garder votre fonction personnalisée
    public function getEtudiants() {
        // Méthode 1: Query Builder
        return $this->findAll();
        
    }
}