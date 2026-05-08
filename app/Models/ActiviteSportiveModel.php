<?php

namespace App\Models;

use CodeIgniter\Model;

class ActiviteSportiveModel extends Model
{
    protected $table = 'activite_sportive';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nom_activite',
        'description',
        'type_cible',
        'depense_calorique_estimee'
    ];
    
    /**
     * Recommande une activité selon l'objectif
     * @param string $typeObjectif
     * @return object|null
     */
    public function recommanderActivite(string $typeObjectif)
    {
        switch($typeObjectif) {
            case 'reduire_poids':
                // Activités cardio pour brûler des calories
                return $this->where('type_cible', 'reduire')
                            ->orderBy('id', 'ASC')
                            ->first();
            case 'augmenter_poids':
                // Activités musculation pour prendre du muscle
                return $this->where('type_cible', 'augmenter')
                            ->orderBy('id', 'ASC')
                            ->first();
            case 'imc_ideal':
            default:
                // Activités équilibrées
                return $this->where('type_cible', 'maintenir')
                            ->orderBy('id', 'ASC')
                            ->first();
        }
    }
    
    /**
     * Calcule les calories brûlées pour une durée donnée
     * @param int $activiteId
     * @param int $dureeMinutes
     * @return int
     */
    public function calculerCaloriesBrûlees(int $activiteId, int $dureeMinutes): int
    {
        $activite = $this->find($activiteId);
        if(!$activite || !isset($activite['depense_calorique_estimee'])) {
            return 0;
        }
        
        // Calorie par heure * (minutes / 60)
        return (int) round($activite['depense_calorique_estimee'] * ($dureeMinutes / 60));
    }
}