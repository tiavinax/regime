<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProfilPhysiqueModel;
use App\Models\ObjectifModel;
use App\Models\BesoinCaloriqueModel;
use App\Models\SuggestionModel;
use App\Models\RegimeModel;
use App\Models\ActiviteSportiveModel;

class DashboardController extends BaseController
{
    protected $userModel;
    protected $profilModel;
    protected $objectifModel;
    protected $besoinModel;
    protected $suggestionModel;
    protected $regimeModel;
    protected $activiteModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->profilModel = new ProfilPhysiqueModel();
        $this->objectifModel = new ObjectifModel();
        $this->besoinModel = new BesoinCaloriqueModel();
        $this->suggestionModel = new SuggestionModel();
        $this->regimeModel = new RegimeModel();
        $this->activiteModel = new ActiviteSportiveModel();
    }

    public function index()
    {
        // Vérifier si l'utilisateur est connecté
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter');
        }

        $userId = session()->get('user_id');

        // Récupérer les données utilisateur
        $user = $this->userModel->find($userId);
        $profil = $this->profilModel->getLastProfilByUser($userId);
        $objectif = $this->objectifModel->getObjectifActif($userId);

        // Si pas d'objectif actif, rediriger vers choix objectif
        if (!$objectif) {
            return redirect()->to('/objectif/choisir')->with('warning', 'Veuillez définir un objectif');
        }

        // Calculer l'IMC
        $imc = 0;
        $interpretationImc = '';
        if ($profil) {
            $imc = $this->profilModel->calculerIMC($profil['poids_kg'], $profil['taille_cm']);
            $interpretationImc = $this->profilModel->interpreterIMC($imc);
        }

        // Récupérer le besoin calorique
        $besoin = $this->besoinModel->getBesoinCompletByUser($userId);

        // Récupérer la suggestion
        $suggestion = $this->suggestionModel->getSuggestionRecenteByUser($userId);

        // Récupérer le régime et l'activité recommandés
        $regime = null;
        $activite = null;
        if ($suggestion) {
            $regime = $this->regimeModel->getRegimeAvecComposition($suggestion['id_regime']);
            $activite = $this->activiteModel->find($suggestion['id_activite']);
        }

        // Calculer la progression de l'objectif
        $progression = 0;
        $tempsRestant = null;
        if ($objectif) {
            $progression = $this->objectifModel->getProgressionEstimee(
                $objectif['duree_souhaitee_semaines'],
                $objectif['date_debut']
            );
            $tempsRestant = $this->objectifModel->getTempsRestant(
                $objectif['duree_souhaitee_semaines'],
                $objectif['date_debut']
            );
        }

        return view('dashboard/index', [
            'user' => $user,
            'profil' => $profil,
            'objectif' => $objectif,
            'imc' => $imc,
            'interpretationImc' => $interpretationImc,
            'besoin' => $besoin,
            'suggestion' => $suggestion,
            'regime' => $regime,
            'activite' => $activite,
            'progression' => $progression,
            'tempsRestant' => $tempsRestant,
            'isGold' => session()->get('is_gold') ?? false
        ]);
    }
    
    public function refreshSuggestion()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }
        
        // Rediriger vers l'objectif pour régénérer
        return redirect()->to('/objectif/choisir')->with('info', 'Redéfinissez votre objectif pour une nouvelle suggestion');
    }
    
    public function exportPdf()
    {
        return redirect()->to('/dashboard')->with('info', 'Fonctionnalité PDF à venir');
    }
}