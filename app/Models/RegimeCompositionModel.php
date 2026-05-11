<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeCompositionModel extends Model
{
    protected $table = 'regime_composition';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_regime',
        'pourcentage_viande',
        'pourcentage_poisson',
        'pourcentage_volaille'
    ];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;
    
    protected $validationRules = [
        'id_regime' => 'required|integer|is_not_unique[regime.id]',
        'pourcentage_viande' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        'pourcentage_poisson' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        'pourcentage_volaille' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]'
    ];
    
    protected $validationMessages = [
        'pourcentage_viande' => [
            'greater_than_equal_to' => 'Le pourcentage doit être entre 0 et 100',
            'less_than_equal_to' => 'Le pourcentage doit être entre 0 et 100'
        ]
    ];
    
    /**
     * Récupère la composition d'un régime
     * @param int $regimeId
     * @return object|null
     */
    public function getCompositionByRegime(int $regimeId)
    {
        return $this->where('id_regime', $regimeId)->first();
    }
    
    /**
     * Met à jour ou crée la composition d'un régime
     * @param int $regimeId
     * @param array $data
     * @return bool|int
     */
    public function setComposition(int $regimeId, array $data)
    {
        $existing = $this->where('id_regime', $regimeId)->first();
        
        $compositionData = [
            'id_regime' => $regimeId,
            'pourcentage_viande' => $data['pourcentage_viande'] ?? 0,
            'pourcentage_poisson' => $data['pourcentage_poisson'] ?? 0,
            'pourcentage_volaille' => $data['pourcentage_volaille'] ?? 0
        ];
        
        if ($existing) {
            return $this->update($existing['id'], $compositionData);
        } else {
            return $this->insert($compositionData);
        }
    }
    
    /**
     * Vérifie que la somme des pourcentages est cohérente (ne dépasse pas 100)
     * @param array $data
     * @return bool
     */
    public function verifierSomme(array $data): bool
    {
        $total = ($data['pourcentage_viande'] ?? 0) + 
                 ($data['pourcentage_poisson'] ?? 0) + 
                 ($data['pourcentage_volaille'] ?? 0);
        
        return $total <= 100;
    }
    
    /**
     * Récupère un régime avec sa composition complète
     * @param int $regimeId
     * @return array|null
     */
    public function getRegimeAvecCompositionComplete(int $regimeId)
    {
        $db = \Config\Database::connect();
        
        return $db->table('regime')
            ->select('regime.*, regime_composition.pourcentage_viande, regime_composition.pourcentage_poisson, regime_composition.pourcentage_volaille')
            ->join('regime_composition', 'regime_composition.id_regime = regime.id', 'left')
            ->where('regime.id', $regimeId)
            ->get()
            ->getRowArray();
    }
}