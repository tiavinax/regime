<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilPhysiqueModel extends Model
{
    protected $table = 'profil_physique';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_utilisateur', 
        'poids_kg', 
        'taille_cm', 
        'niveau_activite', 
        'date_mesure'
    ];

    // Récupère le dernier profil d'un utilisateur
    public function getLastProfil($id_utilisateur)
    {
        return $this->where('id_utilisateur', $id_utilisateur)
                    ->orderBy('date_mesure', 'DESC')
                    ->first();
    }
}