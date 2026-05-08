<?php

namespace App\Models;

use CodeIgniter\Model;

class SuggestionModel extends Model
{
    protected $table = 'suggestion';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_besoin',
        'id_regime',
        'id_activite',
        'duree_recommandee_semaines',
        'message_personnalise',
        'date_suggestion'
    ];
    
    protected $useTimestamps = false;
    protected $createdField = 'date_suggestion';
    protected $updatedField = false;
    
    /**
     * Récupère la suggestion la plus récente pour un utilisateur
     * @param int $userId
     * @return object|null
     */
    public function getSuggestionRecenteByUser(int $userId)
    {
        return $this->select('suggestion.*, regime.nom_regime, regime.description as regime_description, 
                              regime.type_cible, regime.apport_calorique_reference,
                              activite_sportive.nom_activite, activite_sportive.description as activite_description')
                    ->join('besoin_calorique', 'besoin_calorique.id = suggestion.id_besoin')
                    ->join('objectif', 'objectif.id = besoin_calorique.id_objectif')
                    ->join('regime', 'regime.id = suggestion.id_regime')
                    ->join('activite_sportive', 'activite_sportive.id = suggestion.id_activite')
                    ->where('objectif.id_utilisateur', $userId)
                    ->orderBy('suggestion.date_suggestion', 'DESC')
                    ->first();
    }
    
    /**
     * Récupère toutes les suggestions d'un utilisateur
     * @param int $userId
     * @return array
     */
    public function getAllSuggestionsByUser(int $userId)
    {
        return $this->select('suggestion.*, regime.nom_regime, activite_sportive.nom_activite')
                    ->join('besoin_calorique', 'besoin_calorique.id = suggestion.id_besoin')
                    ->join('objectif', 'objectif.id = besoin_calorique.id_objectif')
                    ->join('regime', 'regime.id = suggestion.id_regime')
                    ->join('activite_sportive', 'activite_sportive.id = suggestion.id_activite')
                    ->where('objectif.id_utilisateur', $userId)
                    ->orderBy('suggestion.date_suggestion', 'DESC')
                    ->findAll();
    }
    
    /**
     * Crée une suggestion complète
     * @param int $besoinId
     * @param int $regimeId
     * @param int $activiteId
     * @param int $dureeSemaines
     * @param string $message
     * @return bool|int
     */
    public function creerSuggestion(int $besoinId, int $regimeId, int $activiteId, int $dureeSemaines, string $message)
    {
        return $this->insert([
            'id_besoin' => $besoinId,
            'id_regime' => $regimeId,
            'id_activite' => $activiteId,
            'duree_recommandee_semaines' => $dureeSemaines,
            'message_personnalise' => $message,
            'date_suggestion' => date('Y-m-d H:i:s')
        ]);
    }
}