<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table = 'regime';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nom_regime',
        'description',
        'type_cible',
        'apport_calorique_reference',
        'prix_journalier',
        'variation_poids_semaine'
    ];
    
    protected $useTimestamps = false;
    
    // Ajoute ces colonnes dans ta table regime
    // ALTER TABLE regime ADD COLUMN prix_journalier DECIMAL(6,2) DEFAULT 0;
    // ALTER TABLE regime ADD COLUMN variation_poids_semaine DECIMAL(3,2) DEFAULT 0;
    
    /**
     * Récupère tous les régimes avec leurs compositions
     * @return array
     */
    public function getRegimesAvecComposition()
    {
        return $this->select('regime.*, regime_composition.pourcentage_viande, 
                              regime_composition.pourcentage_poisson, regime_composition.pourcentage_volaille')
                    ->join('regime_composition', 'regime_composition.id_regime = regime.id', 'left')
                    ->findAll();
    }
    
    /**
     * Récupère un régime avec sa composition
     * @param int $regimeId
     * @return object|null
     */
    public function getRegimeAvecComposition(int $regimeId)
    {
        return $this->select('regime.*, regime_composition.pourcentage_viande, 
                              regime_composition.pourcentage_poisson, regime_composition.pourcentage_volaille')
                    ->join('regime_composition', 'regime_composition.id_regime = regime.id', 'left')
                    ->where('regime.id', $regimeId)
                    ->first();
    }
    
    /**
     * Recommande un régime selon l'objectif et le besoin calorique
     * @param string $typeObjectif
     * @param int $besoinMaintien
     * @param int $besoinObjectif
     * @return object|null
     */
    public function recommanderRegime(string $typeObjectif, int $besoinMaintien, int $besoinObjectif)
    {
        $ecart = $besoinObjectif - $besoinMaintien;
        
        if($typeObjectif === 'reduire_poids' || ($typeObjectif === 'imc_ideal' && $ecart < 0)) {
            // Régimes pour perte de poids
            return $this->where('type_cible', 'reduire')
                        ->orderBy('id', 'ASC')
                        ->first();
        } 
        elseif($typeObjectif === 'augmenter_poids' || ($typeObjectif === 'imc_ideal' && $ecart > 0)) {
            // Régimes pour prise de poids
            return $this->where('type_cible', 'augmenter')
                        ->orderBy('id', 'ASC')
                        ->first();
        } 
        else {
            // Régime maintien
            return $this->where('type_cible', 'maintenir')
                        ->orderBy('id', 'ASC')
                        ->first();
        }
    }
    
    /**
     * Calcule le prix total pour une durée donnée
     * @param int $regimeId
     * @param int $dureeSemaines
     * @param bool $isGold
     * @return float
     */
    public function calculerPrix(int $regimeId, int $dureeSemaines, bool $isGold = false): float
    {
        $regime = $this->find($regimeId);
        if(!$regime || !isset($regime['prix_journalier'])) {
            return 0;
        }
        
        $prixTotal = $regime['prix_journalier'] * ($dureeSemaines * 7);
        
        if($isGold) {
            $prixTotal = $prixTotal * 0.85; // Réduction 15%
        }
        
        return round($prixTotal, 2);
    }
}