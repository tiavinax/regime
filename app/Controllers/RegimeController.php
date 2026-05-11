<?php

namespace App\Controllers;

use App\Models\RegimeModel;
use App\Models\RegimeCompositionModel;

class RegimeController extends BaseController
{
    protected $regimeModel;
    protected $compositionModel;

    public function __construct()
    {
        $this->regimeModel = new RegimeModel();
        $this->compositionModel = new RegimeCompositionModel();
    }

    /**
     * Affiche la liste des régimes
     */
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Récupérer tous les régimes avec leur composition
        $regimes = $db->table('regime')
            ->select('regime.*, regime_composition.pourcentage_viande, regime_composition.pourcentage_poisson, regime_composition.pourcentage_volaille')
            ->join('regime_composition', 'regime_composition.id_regime = regime.id', 'left')
            ->get()
            ->getResultArray();
        
        $isGold = session()->has('is_gold') ? session()->get('is_gold') : false;
        
        return view('regimes/index', [
            'regimes' => $regimes,
            'isGold' => $isGold
        ]);
    }

    /**
     * Affiche les détails d'un régime
     */
    public function show($id)
    {
        $db = \Config\Database::connect();
        
        $regime = $db->table('regime')
            ->select('regime.*, regime_composition.pourcentage_viande, regime_composition.pourcentage_poisson, regime_composition.pourcentage_volaille')
            ->join('regime_composition', 'regime_composition.id_regime = regime.id', 'left')
            ->where('regime.id', $id)
            ->get()
            ->getRowArray();
        
        if (!$regime) {
            return redirect()->to('/regimes')->with('error', 'Régime non trouvé');
        }
        
        $isGold = session()->has('is_gold') ? session()->get('is_gold') : false;
        
        return view('regimes/show', [
            'regime' => $regime,
            'isGold' => $isGold
        ]);
    }
}