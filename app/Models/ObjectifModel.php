<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjectifModel extends Model
{
    protected $table = 'objectif';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_utilisateur',
        'type_objectif',
        'poids_cible_kg',
        'date_debut',
        'duree_souhaitee_semaines',
        'status'
    ];

    // Récupère l'objectif actif d'un utilisateur
    public function getObjectifActif($id_utilisateur)
    {
        return $this->where('id_utilisateur', $id_utilisateur)
                    ->where('status', 'en_cours')
                    ->orderBy('date_debut', 'DESC')
                    ->first();
    }

    // Tous les objectifs d'un utilisateur (alias)
    public function getObjectifByUser($id_utilisateur)
    {
        return $this->where('id_utilisateur', $id_utilisateur)
                    ->orderBy('date_debut', 'DESC')
                    ->findAll();
    }

    // Récupère un objectif par son Id
    public function getObjectifById($id, $id_utilisateur)
    {
        return $this->where('id', $id)
                    ->where('id_utilisateur', $id_utilisateur)
                    ->first();
    }

    // Crée un nouvel objectif (désactive l'ancien automatiquement)
    public function creerObjectif($data)
    {
        // Désactive l'ancien objectif en cours
        $this->where('id_utilisateur', $data['id_utilisateur'])
             ->where('status', 'en_cours')
             ->set(['status' => 'atteint'])
             ->update();

        // Insertion du nouvel objectif
        return $this->insert($data);
    }

    // Met à jour un objectif existant
    public function modifierObjectif($id, $id_utilisateur, $data)
    {
        return $this->where('id', $id)
                    ->where('id_utilisateur', $id_utilisateur)
                    ->set($data)
                    ->update();
    }

    // Supprime un objectif
    public function supprimerObjectif($id, $id_utilisateur)
    {
        return $this->where('id', $id)
                    ->where('id_utilisateur', $id_utilisateur)
                    ->delete();
    }

    // Termine un objectif (marque comme atteint)
    public function terminerObjectif($id, $id_utilisateur)
    {
        return $this->where('id', $id)
                    ->where('id_utilisateur', $id_utilisateur)
                    ->set(['status' => 'atteint'])
                    ->update();
    }

    // Récupère tous les objectifs d'un utilisateur
    public function getObjectifsByUser($id_utilisateur)
    {
        return $this->where('id_utilisateur', $id_utilisateur)
                    ->orderBy('date_debut', 'DESC')
                    ->findAll();
    }


    

    // Calcule la progression vers l'objectif
    public function calculerProgression($objectif, $poids_actuel)
    {
        if (!$objectif || !$objectif['poids_cible_kg'] || !$poids_actuel) {
            return null;
        }
        
        $poids_cible = $objectif['poids_cible_kg'];
        
        if ($objectif['type_objectif'] == 'reduire_poids') {
            if ($poids_actuel <= $poids_cible) return 100;
            $perte_necessaire = 10;
            $perte_actuelle = $poids_actuel - $poids_cible;
            $progression = round(($perte_actuelle / $perte_necessaire) * 100);
            return min(100, max(0, $progression));
        } elseif ($objectif['type_objectif'] == 'augmenter_poids') {
            if ($poids_actuel >= $poids_cible) return 100;
            $gain_necessaire = 10;
            $gain_actuel = $poids_cible - $poids_actuel;
            $progression = round(($gain_actuel / $gain_necessaire) * 100);
            return min(100, max(0, $progression));
        }
        
        return null;
    }
}