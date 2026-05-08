<?php

namespace App\Controllers;

class ObjectifController extends BaseController
{
    public function choisir()
    {
        // Vérifier si l'utilisateur est connecté
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter');
        }
        
        return view('objectif/choisir');
    }
    
    public function doChoisir()
    {
        // Vérifier si l'utilisateur est connecté
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
        
        // Connexion DB
        $db = \Config\Database::connect();
        
        // Récupérer le dernier profil
        $profil = $db->table('profil_physique')
            ->where('id_utilisateur', $userId)
            ->orderBy('date_mesure', 'DESC')
            ->get()
            ->getRowArray();
        
        if (!$profil) {
            return redirect()->back()->with('error', 'Profil physique introuvable');
        }
        
        // Créer l'objectif
        $objectifData = [
            'id_utilisateur' => $userId,
            'type_objectif' => $this->request->getPost('type_objectif'),
            'date_debut' => date('Y-m-d'),
            'status' => 'en_cours',
            'duree_souhaitee_semaines' => $this->request->getPost('duree_souhaitee_semaines')
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
        
        // Rediriger vers le dashboard
        return redirect()->to('/dashboard')->with('success', 'Objectif défini avec succès !');
    }
}