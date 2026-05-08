<?php

namespace App\Controllers;

use App\Models\ObjectifModel;
use App\Models\ProfilPhysiqueModel;

class ObjectifController extends BaseController
{
    protected $objectifModel;
    protected $profilPhysiqueModel;

    public function __construct()
    {
        $this->objectifModel = new ObjectifModel();
        $this->profilPhysiqueModel = new ProfilPhysiqueModel();
        
        helper('imc');
        
        // Vérifier connexion (à décommenter quand l'auth est prête)
        // if (!session()->get('isLoggedIn')) {
        //     redirect()->to('/login')->send();
        //     exit();
        // }
        
        // Session de test (à supprimer après)
        if (!session()->get('isLoggedIn')) {
            session()->set([
                'id_utilisateur' => 1,
                'nom' => 'fyh',
                'email' => 'fyh@gmail.com',
                'genre' => 'homme',
                'isLoggedIn' => TRUE
            ]);
        }
    }

    
    // Afficher la page de choix d'objectif
    public function choisir()
    {
        $id_utilisateur = session()->get('id_utilisateur');
        
        $data = [
            'title' => 'Choisir mon objectif',
            'objectifActif' => $this->objectifModel->getObjectifActif($id_utilisateur),
            'historique' => $this->objectifModel->getObjectifsByUser($id_utilisateur),
            'profil' => $this->profilPhysiqueModel->getLastProfil($id_utilisateur)
        ];
        
        // Calculer l'IMC et le poids idéal si profil existe
        if ($data['profil']) {
            $data['imc'] = calculer_imc($data['profil']['poids_kg'], $data['profil']['taille_cm']);
            $data['poids_ideal'] = calculer_poids_ideal($data['profil']['taille_cm']);
            $data['interpretationImc'] = interpreter_imc($data['imc']);
        }
        
        return view('objectif/choisir', $data);
    }

    // Enregistrer un nouvel objectif
    public function enregistrer()
{
    $id_utilisateur = session()->get('id_utilisateur');
    $type = $this->request->getPost('type_objectif');
    $poids = $this->request->getPost('poids_cible_kg');
    $duree = $this->request->getPost('duree_semaines');

    $data = [
        'id_utilisateur' => $id_utilisateur,
        'type_objectif' => $type,
        'poids_cible_kg' => $poids,
        'date_debut' => date('Y-m-d'),
        'duree_souhaitee_semaines' => $duree ? (int)$duree : null,
        'status' => 'en_cours'
    ];

    if ($this->objectifModel->insert($data)) {
        return redirect()->to('/dashboard')->with('success', 'Objectif enregistré');
    } else {
        return redirect()->back()->with('error', 'Erreur insertion');
    }
}

    // Modifier un objectif existant
    public function modifier($id)
    {
        $id_utilisateur = session()->get('id_utilisateur');
        
        $type_objectif = $this->request->getPost('type_objectif');
        $poids_cible_kg = $this->request->getPost('poids_cible_kg');
        $duree_semaines = $this->request->getPost('duree_semaines');
        
        $data = [];
        if ($type_objectif) $data['type_objectif'] = $type_objectif;
        if ($poids_cible_kg) $data['poids_cible_kg'] = $poids_cible_kg;
        if ($duree_semaines) $data['duree_souhaitee_semaines'] = $duree_semaines;
        
        if ($this->objectifModel->modifierObjectif($id, $id_utilisateur, $data)) {
            return redirect()->to('/objectif/choisir')->with('success', 'Objectif modifié');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la modification');
        }
    }

    // Supprimer un objectif
    public function supprimer($id)
    {
        $id_utilisateur = session()->get('id_utilisateur');
        
        if ($this->objectifModel->supprimerObjectif($id, $id_utilisateur)) {
            return redirect()->to('/objectif/choisir')->with('success', 'Objectif supprimé');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la suppression');
        }
        
        return redirect()->to('/objectif/choisir');
    }

    // Terminer un objectif (marquer comme atteint)
    public function terminer($id)
    {
        $id_utilisateur = session()->get('id_utilisateur');
        
        if ($this->objectifModel->terminerObjectif($id, $id_utilisateur)) {
            return redirect()->to('/objectif/choisir')->with('success', 'Objectif terminé, félicitations !');
        } else {
            return redirect()->back()->with('error', 'Erreur');
        }
    }

    
}