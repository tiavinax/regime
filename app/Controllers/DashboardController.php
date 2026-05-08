<?php

namespace App\Controllers;

use App\Models\ProfilPhysiqueModel;
use App\Models\ObjectifModel;
use App\Models\BesoinCaloriqueModel;

class DashboardController extends BaseController
{
    protected $profilPhysiqueModel;
    protected $objectifModel;
    protected $besoinCaloriqueModel;

    
    public function __construct()
    {
        $this->profilPhysiqueModel = new ProfilPhysiqueModel();
        $this->objectifModel = new ObjectifModel();
        $this->besoinCaloriqueModel = new BesoinCaloriqueModel();
        
        helper('imc');
        
        // // Vérifier connexion
        // if (!session()->get('isLoggedIn')) {
        //     redirect()->to('/login')->send();
        //     exit();
        // }
    }

    public function index()
    {
            //  SESSION DE TEST (à supprimer après)
        if (!session()->get('isLoggedIn')) {
            session()->set([
                'id_utilisateur' => 1,  // L'ID de votre utilisateur 'fyh'
                'nom' => 'fyh',
                'email' => 'fyh@gmail.com',
                'genre' => 'homme',
                'isLoggedIn' => TRUE
            ]);
        }

        $id_utilisateur = session()->get('id_utilisateur');
        $genre = session()->get('genre');
        $nom = session()->get('nom');
        
        // Récupérer les données
        $profil = $this->profilPhysiqueModel->getLastProfil($id_utilisateur);
        $objectifActif = $this->objectifModel->getObjectifActif($id_utilisateur);
        
        // Récupérer le dernier calcul calorique depuis BesoinCaloriqueModel
        $dernierCalcul = null;
        if ($objectifActif) {
            $dernierCalcul = $this->besoinCaloriqueModel->getDernierCalcul($objectifActif['id']);
        }
        
        // Initialisation des variables
        $imc = 0;
        $interpretationImc = '';
        $metabolismeBase = 0;
        $besoinMaintien = 0;
        $besoinObjectif = 0;
        $poidsIdeal = 0;
        $progression = null;
        $age = 30;
        
        if ($profil) {
            // Calcul IMC
            $imc = calculer_imc($profil['poids_kg'], $profil['taille_cm']);
            $interpretationImc = interpreter_imc($imc);
            $poidsIdeal = calculer_poids_ideal($profil['taille_cm']);
            
            // Récupérer âge
            $db = \Config\Database::connect();
            $query = $db->table('utilisateur')
                        ->select('date_naissance')
                        ->where('id', $id_utilisateur)
                        ->get();
            $user = $query->getRowArray();
            
            if ($user && isset($user['date_naissance'])) {
                $naissance = new \DateTime($user['date_naissance']);
                $now = new \DateTime();
                $age = $now->diff($naissance)->y;
            }
            
            // Utiliser les valeurs du dernier calcul si disponible, sinon recalculer
            if ($dernierCalcul) {
                $metabolismeBase = $dernierCalcul['metabolisme_base_kcal'];
                $besoinMaintien = $dernierCalcul['besoin_maintien_kcal'];
                $besoinObjectif = $dernierCalcul['besoin_objectif_kcal'];
            } else {
                // Recalculer si pas de données
                $metabolismeBase = calculer_metabolisme_base(
                    $profil['poids_kg'],
                    $profil['taille_cm'],
                    $age,
                    $genre
                );
                
                $besoinMaintien = calculer_besoin_maintien(
                    $metabolismeBase,
                    $profil['niveau_activite']
                );
                
                if ($objectifActif) {
                    $besoinObjectif = calculer_besoin_objectif(
                        $besoinMaintien,
                        $objectifActif['type_objectif'],
                        $objectifActif['poids_cible_kg'],
                        $profil['poids_kg']
                    );
                    
                    // Enregistrer le calcul pour la prochaine fois
                    $this->besoinCaloriqueModel->enregistrerCalcul(
                        $objectifActif['id'],
                        $profil['id'],
                        $metabolismeBase,
                        $besoinMaintien,
                        $besoinObjectif
                    );
                }
            }
            
            // Calcul progression
            if ($objectifActif && $objectifActif['poids_cible_kg']) {
                $poidsCible = $objectifActif['poids_cible_kg'];
                $poidsActuel = $profil['poids_kg'];
                $differenceTotale = abs($poidsCible - $poidsActuel);
                
                if ($objectifActif['type_objectif'] == 'reduire_poids') {
                    // Besoin de perdre
                    $perdu = $poidsActuel - $poidsCible;
                    if ($perdu > 0) {
                        $progression = round(($perdu / $differenceTotale) * 100);
                        $progression = min(100, $progression);
                    } else {
                        $progression = 0;
                    }
                } elseif ($objectifActif['type_objectif'] == 'augmenter_poids') {
                    // Besoin de prendre
                    $gagne = $poidsCible - $poidsActuel;
                    if ($gagne > 0) {
                        $progression = round(($gagne / $differenceTotale) * 100);
                        $progression = min(100, $progression);
                    } else {
                        $progression = 0;
                    }
                }
            }
        }
        
        $data = [
            'title' => 'Tableau de bord',
            'nom' => $nom,
            'profil' => $profil,
            'objectifActif' => $objectifActif,
            'dernierCalcul' => $dernierCalcul,
            'imc' => $imc,
            'interpretationImc' => $interpretationImc,
            'poidsIdeal' => $poidsIdeal,
            'metabolismeBase' => $metabolismeBase,
            'besoinMaintien' => $besoinMaintien,
            'besoinObjectif' => $besoinObjectif,
            'progression' => $progression,
            'age' => $age
        ];
        
    
        return view('dashboard', $data);
    }
}