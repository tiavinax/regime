<?php

namespace App\Models;

use CodeIgniter\Model;

class BesoinCaloriqueModel extends Model
{
    protected $table = 'besoin_calorique';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_objectif',
        'id_profil',
        'metabolisme_base_kcal',
        'besoin_maintien_kcal',
        'besoin_objectif_kcal',
        'date_calcul'
    ];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;
    
    protected $createdField = 'date_calcul';
    protected $updatedField = false;
    
    // Validation rules
    protected $validationRules = [
        'id_objectif' => 'required|integer',
        'id_profil' => 'required|integer',
        'metabolisme_base_kcal' => 'required|numeric|greater_than[500]|less_than[5000]',
        'besoin_maintien_kcal' => 'required|numeric|greater_than[500]|less_than[7000]',
        'besoin_objectif_kcal' => 'required|numeric|greater_than[500]|less_than[7000]'
    ];
    
    /**
     * Récupère le dernier besoin calorique d'un objectif
     * @param int $objectifId
     * @return object|null
     */
    public function getByObjectif(int $objectifId)
    {
        return $this->where('id_objectif', $objectifId)
                    ->orderBy('date_calcul', 'DESC')
                    ->first();
    }
    
    /**
     * Récupère le besoin calorique avec les relations (objectif + profil)
     * @param int $userId
     * @return object|null
     */
    public function getBesoinCompletByUser(int $userId)
    {
        return $this->select('besoin_calorique.*, objectif.type_objectif, objectif.poids_cible_kg, 
                              profil_physique.poids_kg, profil_physique.taille_cm, profil_physique.niveau_activite')
                    ->join('objectif', 'objectif.id = besoin_calorique.id_objectif')
                    ->join('profil_physique', 'profil_physique.id = besoin_calorique.id_profil')
                    ->where('objectif.id_utilisateur', $userId)
                    ->where('objectif.status', 'en_cours')
                    ->orderBy('besoin_calorique.date_calcul', 'DESC')
                    ->first();
    }
    
    /**
     * Calcule la différence entre besoin maintien et besoin objectif
     * @param int $besoinMaintien
     * @param int $besoinObjectif
     * @return array
     */
    public function calculerDifferenceCalorique(int $besoinMaintien, int $besoinObjectif): array
    {
        $difference = $besoinObjectif - $besoinMaintien;
        
        if($difference > 0) {
            return [
                'type' => 'surplus',
                'valeur' => $difference,
                'message' => "Vous devez consommer {$difference} kcal supplémentaires par jour"
            ];
        } elseif($difference < 0) {
            return [
                'type' => 'deficit',
                'valeur' => abs($difference),
                'message' => "Vous devez réduire votre apport de " . abs($difference) . " kcal par jour"
            ];
        } else {
            return [
                'type' => 'maintien',
                'valeur' => 0,
                'message' => "Maintenez votre apport calorique actuel"
            ];
        }
    }
    
    /**
     * Recomande un type de régime basé sur l'objectif et le besoin calorique
     * @param string $typeObjectif
     * @param int $besoinMaintien
     * @param int $besoinObjectif
     * @return string
     */
    public function recommanderTypeRegime(string $typeObjectif, int $besoinMaintien, int $besoinObjectif): string
    {
        $ecart = $besoinObjectif - $besoinMaintien;
        
        switch($typeObjectif) {
            case 'reduire_poids':
                if($ecart < -500) return 'hypocalorique_severe';
                if($ecart < -200) return 'hypocalorique_modere';
                return 'equilibre';
                
            case 'augmenter_poids':
                if($ecart > 500) return 'hypercalorique_masse';
                if($ecart > 200) return 'hypercalorique_modere';
                return 'equilibre';
                
            case 'imc_ideal':
                if($ecart < -200) return 'hypocalorique_doux';
                if($ecart > 200) return 'hypercalorique_doux';
                return 'equilibre';
                
            default:
                return 'equilibre';
        }
    }
}