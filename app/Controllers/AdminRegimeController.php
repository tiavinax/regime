<?php

namespace App\Controllers;

use App\Models\RegimeModel;

class AdminRegimeController extends BaseController
{
    protected $regimeModel;

    public function __construct()
    {
        $this->regimeModel = new RegimeModel();
        helper('form');
    }

     /**
     * Vérifie si l'utilisateur connecté est admin
     * Utilise la session existante de AuthController
     */
    private function checkAdminAccess()
    {
        // Vérifier si l'utilisateur est connecté via AuthController
        if (!session()->has('user_id') || !session()->has('is_logged_in')) {
            return false;
        }
        
        // Vérifier si l'utilisateur a le rôle admin
        if (session()->get('role') !== 'admin') {
            return false;
        }
        
        return true;
    }
    
    /**
     * Redirige si non admin
     */
    private function requireAdmin()
    {
        if (!$this->checkAdminAccess()) {
            return redirect()->to('/login')->with('error', 'Accès réservé aux administrateurs');
        }
        return null;
    }

    // Liste des régimes
    public function index()
    {
        $check = $this->requireAdmin();
        if ($check) return $check;

        $data = [
            'regimes' => $this->regimeModel->findAll(),
            'admin_nom' => session()->get('admin_nom')
        ];
        return view('admin/regimes/index', $data);
    }

    // Formulaire création
    public function create()
    {
        $check = $this->requireAdmin();
        if ($check) return $check;

        return view('admin/regimes/create', ['admin_nom' => session()->get('admin_nom')]);
    }

    // Sauvegarder nouveau régime
    public function store()
    {
        $check = $this->requireAdmin();
        if ($check) return $check;

        $rules = [
            'nom_regime' => 'required|min_length[3]',
            'description' => 'required',
            'type_cible' => 'required|in_list[augmenter,reduire,maintenir]',
            'apport_calorique_reference' => 'permit_empty|numeric',
            'prix_journalier' => 'permit_empty|numeric',
            'variation_poids_semaine' => 'permit_empty|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->regimeModel->insert([
            'nom_regime' => $this->request->getPost('nom_regime'),
            'description' => $this->request->getPost('description'),
            'type_cible' => $this->request->getPost('type_cible'),
            'apport_calorique_reference' => $this->request->getPost('apport_calorique_reference'),
            'prix_journalier' => $this->request->getPost('prix_journalier'),
            'variation_poids_semaine' => $this->request->getPost('variation_poids_semaine')
        ]);

        return redirect()->to('/admin/regimes')->with('success', 'Régime créé avec succès');
    }

    // Formulaire modification
    public function edit($id)
    {
        $check = $this->requireAdmin();
        if ($check) return $check;

        $regime = $this->regimeModel->find($id);
        if (!$regime) {
            return redirect()->to('/admin/regimes')->with('error', 'Régime non trouvé');
        }

        $data = [
            'regime' => $regime,
            'admin_nom' => session()->get('admin_nom')
        ];
        return view('admin/regimes/edit', $data);
    }

    // Mettre à jour
    public function update($id)
    {
        $check = $this->requireAdmin();
        if ($check) return $check;

        $rules = [
            'nom_regime' => 'required|min_length[3]',
            'description' => 'required',
            'type_cible' => 'required|in_list[augmenter,reduire,maintenir]',
            'apport_calorique_reference' => 'permit_empty|numeric',
            'prix_journalier' => 'permit_empty|numeric',
            'variation_poids_semaine' => 'permit_empty|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->regimeModel->update($id, [
            'nom_regime' => $this->request->getPost('nom_regime'),
            'description' => $this->request->getPost('description'),
            'type_cible' => $this->request->getPost('type_cible'),
            'apport_calorique_reference' => $this->request->getPost('apport_calorique_reference'),
            'prix_journalier' => $this->request->getPost('prix_journalier'),
            'variation_poids_semaine' => $this->request->getPost('variation_poids_semaine')
        ]);

        return redirect()->to('/admin/regimes')->with('success', 'Régime mis à jour');
    }

    // Supprimer
    public function delete($id)
    {
        $check = $this->requireAdmin();
        if ($check) return $check;

        $this->regimeModel->delete($id);
        return redirect()->to('/admin/regimes')->with('success', 'Régime supprimé');
    }
}