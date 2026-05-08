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

    /**
     * Affiche le tableau de bord utilisateur
     */
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

        // Calculer les différences caloriques
        $difference = null;
        if ($besoin) {
            $difference = $this->besoinModel->calculerDifferenceCalorique(
                $besoin['besoin_maintien_kcal'],
                $besoin['besoin_objectif_kcal']
            );
        }

        // Récupérer ou générer une suggestion
        $suggestion = $this->suggestionModel->getSuggestionRecenteByUser($userId);

        // Si pas de suggestion, en générer une nouvelle
        if (!$suggestion && $besoin) {
            $suggestion = $this->genererSuggestion($besoin);
        }

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
            'difference' => $difference,
            'suggestion' => $suggestion,
            'regime' => $regime,
            'activite' => $activite,
            'progression' => $progression,
            'tempsRestant' => $tempsRestant,
            'isGold' => session()->get('is_gold') ?? false
        ]);
    }
    
    // Dans DashboardController - méthode genererSuggestion()

    /**
     * Génère une nouvelle suggestion de régime et activité
     * @param array|object $besoin
     * @return object|null
     */
    private function genererSuggestion($besoin)
    {
        // S'assurer que $besoin est un array
        $besoinArray = (array) $besoin;

        // Récupérer l'objectif
        $objectifModel = new ObjectifModel();
        $objectif = $objectifModel->find($besoinArray['id_objectif']);

        if (!$objectif) return null;

        // CAST explicite des valeurs
        $typeObjectif = (string) $objectif['type_objectif'];
        $besoinMaintien = (int) $besoinArray['besoin_maintien_kcal'];
        $besoinObjectif = (int) $besoinArray['besoin_objectif_kcal'];

        // Recommander un régime
        $regime = $this->regimeModel->recommanderRegime(
            $typeObjectif,
            $besoinMaintien,
            $besoinObjectif
        );

        // Recommander une activité
        $activite = $this->activiteModel->recommanderActivite($typeObjectif);

        if (!$regime || !$activite) return null;

        // Durée recommandée (cast en int)
        $duree = (int) ($objectif['duree_souhaitee_semaines'] ?? 12);

        // Message personnalisé
        $message = $this->genererMessagePersonnalise(
            $typeObjectif,
            (string) $regime['nom_regime'],
            (string) $activite['nom_activite'],
            $duree
        );

        // Créer la suggestion (cast explicite)
        $suggestionId = $this->suggestionModel->creerSuggestion(
            (int) $besoinArray['id'],
            (int) $regime['id'],
            (int) $activite['id'],
            $duree,
            $message
        );

        return $this->suggestionModel->getSuggestionRecenteByUser((int) session()->get('user_id'));
    }

    /**
     * Génère un message personnalisé pour l'utilisateur
     */
    private function genererMessagePersonnalise(string $typeObjectif, string $nomRegime, string $nomActivite, int $duree): string
    {
        $messages = [
            'reduire_poids' => "🎯 Super ! Nous vous recommandons le régime \"{$nomRegime}\" associé à l'activité \"{$nomActivite}\". Suivez ce programme pendant {$duree} semaines pour atteindre votre objectif de perte de poids.",
            'augmenter_poids' => "💪 Génial ! Pour prendre du poids, le régime \"{$nomRegime}\" avec l'activité \"{$nomActivite}\" est parfait. Programme sur {$duree} semaines pour des résultats visibles.",
            'imc_ideal' => "⚖️ Parfait ! Pour atteindre votre IMC idéal, suivez le régime \"{$nomRegime}\" et pratiquez \"{$nomActivite}\". Durée recommandée : {$duree} semaines."
        ];

        return $messages[$typeObjectif] ?? "Programme personnalisé : régime {$nomRegime} + activité {$nomActivite} sur {$duree} semaines.";
    }

    /**
     * Rafraîchir la suggestion (regénérer)
     */
    public function refreshSuggestion()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $besoin = $this->besoinModel->getBesoinCompletByUser($userId);

        if ($besoin) {
            $this->genererSuggestion($besoin);
            return redirect()->to('/dashboard')->with('success', 'Votre suggestion a été actualisée !');
        }

        return redirect()->to('/dashboard')->with('error', 'Impossible de générer une suggestion');
    }

    /**
     * Exporte la suggestion en PDF
     */
    public function exportPdf()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $suggestion = $this->suggestionModel->getSuggestionRecenteByUser($userId);

        if (!$suggestion) {
            return redirect()->to('/dashboard')->with('error', 'Aucune suggestion à exporter');
        }

        // Charger les données pour le PDF
        $regime = $this->regimeModel->getRegimeAvecComposition($suggestion['id_regime']);
        $activite = $this->activiteModel->find($suggestion['id_activite']);
        $user = $this->userModel->find($userId);
        $profil = $this->profilModel->getLastProfilByUser($userId);

        // Calculer l'IMC
        $imc = $this->profilModel->calculerIMC($profil['poids_kg'], $profil['taille_cm']);

        // Ici tu peux générer un PDF avec une librairie comme Dompdf
        // Pour l'instant, on va juste afficher une vue spéciale
        return view('dashboard/export_pdf', [
            'suggestion' => $suggestion,
            'regime' => $regime,
            'activite' => $activite,
            'user' => $user,
            'profil' => $profil,
            'imc' => $imc
        ]);
    }
}
