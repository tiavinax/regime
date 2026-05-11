<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProfilPhysiqueModel;
use App\Models\ObjectifModel;
use App\Models\BesoinCaloriqueModel;
use App\Models\SuggestionModel;
use App\Models\RegimeModel;
use App\Models\ActiviteSportiveModel;

class ObjectifController extends BaseController
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

    public function choisir()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter');
        }
        
        return view('objectif/choisir');
    }
    
    public function doChoisir()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login');
        }
        
        $userId = session()->get('user_id');
        
        // Validation
        $rules = [
            'type_objectif' => 'required|in_list[augmenter_poids,reduire_poids,imc_ideal]',
            'duree_souhaitee_semaines' => 'permit_empty|numeric|greater_than[0]|less_than[52]'
        ];
        
        if ($this->request->getPost('type_objectif') === 'imc_ideal') {
            $rules['poids_cible_kg'] = 'required|numeric|greater_than[10]|less_than[300]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        $db = \Config\Database::connect();
        
        // 1. Récupérer le dernier profil physique
        $profil = $db->table('profil_physique')
            ->where('id_utilisateur', $userId)
            ->orderBy('date_mesure', 'DESC')
            ->get()
            ->getRowArray();
        
        if (!$profil) {
            return redirect()->back()->with('error', 'Profil physique introuvable');
        }
        
        // 2. Récupérer l'utilisateur pour l'âge et le genre
        $user = $db->table('utilisateur')
            ->where('id', $userId)
            ->get()
            ->getRowArray();
        
        // 3. Créer l'objectif
        $objectifData = [
            'id_utilisateur' => $userId,
            'type_objectif' => $this->request->getPost('type_objectif'),
            'date_debut' => date('Y-m-d'),
            'status' => 'en_cours',
            'duree_souhaitee_semaines' => $this->request->getPost('duree_souhaitee_semaines') ?: null
        ];
        
        if ($this->request->getPost('type_objectif') === 'imc_ideal') {
            $objectifData['poids_cible_kg'] = $this->request->getPost('poids_cible_kg');
        }
        
        $builder = $db->table('objectif');
        $builder->insert($objectifData);
        $objectifId = $db->insertID();
        
        if (!$objectifId) {
            return redirect()->back()->with('error', 'Erreur lors de la création de l\'objectif');
        }
        
        // 4. Calculer le besoin calorique
        $age = $this->calculerAge($user['date_naissance']);
        $tailleCm = $profil['taille_cm'];
        $poidsKg = $profil['poids_kg'];
        $genre = $user['genre'];
        $niveauActivite = $profil['niveau_activite'];
        $typeObjectif = $this->request->getPost('type_objectif');
        $poidsCible = $this->request->getPost('poids_cible_kg') ?? null;
        
        // Métabolisme de base (Harris & Benedict)
        if ($genre === 'homme') {
            $mb = 88.362 + (13.397 * $poidsKg) + (4.799 * $tailleCm) - (5.677 * $age);
        } else {
            $mb = 447.593 + (9.247 * $poidsKg) + (3.098 * $tailleCm) - (4.330 * $age);
        }
        $mb = (int) round($mb);
        
        // Facteur d'activité
        $facteurs = [
            'sedentaire' => 1.2, 'leger' => 1.375, 'modere' => 1.55,
            'actif' => 1.725, 'extreme' => 1.9
        ];
        $facteur = $facteurs[$niveauActivite] ?? 1.2;
        $besoinMaintien = (int) round($mb * $facteur);
        
        // Besoin selon objectif
        switch($typeObjectif) {
            case 'reduire_poids':
                $besoinObjectif = $besoinMaintien - 500;
                break;
            case 'augmenter_poids':
                $besoinObjectif = $besoinMaintien + 500;
                break;
            case 'imc_ideal':
            default:
                $besoinObjectif = $besoinMaintien;
                break;
        }
        
        // 5. Insérer le besoin calorique
        $besoinData = [
            'id_objectif' => $objectifId,
            'id_profil' => $profil['id'],
            'metabolisme_base_kcal' => $mb,
            'besoin_maintien_kcal' => $besoinMaintien,
            'besoin_objectif_kcal' => $besoinObjectif,
            'date_calcul' => date('Y-m-d H:i:s')
        ];
        
        $builder2 = $db->table('besoin_calorique');
        $builder2->insert($besoinData);
        $besoinId = $db->insertID();
        
        // 6. Créer une suggestion (régime + activité)
        // Récupérer un régime recommandé
        $regime = $db->table('regime')
            ->where('type_cible', $typeObjectif === 'reduire_poids' ? 'reduire' : 
                    ($typeObjectif === 'augmenter_poids' ? 'augmenter' : 'maintenir'))
            ->get()
            ->getFirstRow();
        
        // Récupérer une activité recommandée
        $activite = $db->table('activite_sportive')
            ->where('type_cible', $typeObjectif === 'reduire_poids' ? 'reduire' : 
                    ($typeObjectif === 'augmenter_poids' ? 'augmenter' : 'maintenir'))
            ->get()
            ->getFirstRow();
        
        // Si pas de régime ou activité, utiliser les premiers disponibles
        if (!$regime) {
            $regime = $db->table('regime')->get()->getFirstRow();
        }
        if (!$activite) {
            $activite = $db->table('activite_sportive')->get()->getFirstRow();
        }
        
        $duree = $this->request->getPost('duree_souhaitee_semaines') ?: 12;
        
        $message = $this->genererMessage(
            $typeObjectif, 
            $regime->nom_regime ?? 'personnalisé', 
            $activite->nom_activite ?? 'adaptée', 
            $duree
        );
        
        $suggestionData = [
            'id_besoin' => $besoinId,
            'id_regime' => $regime->id ?? 1,
            'id_activite' => $activite->id ?? 1,
            'duree_recommandee_semaines' => $duree,
            'message_personnalise' => $message,
            'date_suggestion' => date('Y-m-d H:i:s')
        ];
        
        $db->table('suggestion')->insert($suggestionData);
        
        // 7. Rediriger vers le dashboard
        return redirect()->to('/dashboard')->with('success', 'Objectif défini avec succès ! Votre programme personnalisé est prêt.');
    }
    
    private function calculerAge($dateNaissance)
    {
        $naissance = new \DateTime($dateNaissance);
        $aujourdhui = new \DateTime();
        return $naissance->diff($aujourdhui)->y;
    }
    
    private function genererMessage($typeObjectif, $nomRegime, $nomActivite, $duree)
    {
        $messages = [
            'reduire_poids' => "🎯 Programme perte de poids : Suivez le régime \"{$nomRegime}\" et pratiquez \"{$nomActivite}\" pendant {$duree} semaines.",
            'augmenter_poids' => "💪 Programme prise de masse : Suivez le régime \"{$nomRegime}\" et pratiquez \"{$nomActivite}\" pendant {$duree} semaines.",
            'imc_ideal' => "⚖️ Programme équilibre : Suivez le régime \"{$nomRegime}\" et pratiquez \"{$nomActivite}\" pendant {$duree} semaines."
        ];
        return $messages[$typeObjectif] ?? "Programme personnalisé sur {$duree} semaines.";
    }
}