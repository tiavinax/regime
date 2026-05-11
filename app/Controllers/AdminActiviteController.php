<?php

namespace App\Controllers;

use App\Models\ActiviteSportiveModel;

class AdminActiviteController extends BaseController
{
    protected $activiteModel;

    public function __construct()
    {
        $this->activiteModel = new ActiviteSportiveModel();
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

    public function index()
    {
        $check = $this->requireAdmin();
        if ($check) return $check;

        $data = [
            'activites' => $this->activiteModel->findAll(),
            'admin_nom' => session()->get('admin_nom')
        ];
        return view('admin/activites/index', $data);
    }

    public function create()
    {
        $check = $this->requireAdmin();
        if ($check) return $check;

        return view('admin/activites/create', ['admin_nom' => session()->get('admin_nom')]);
    }

    public function store()
    {
        $check = $this->requireAdmin();
        if ($check) return $check;

        $rules = [
            'nom_activite' => 'required|min_length[3]',
            'description' => 'required',
            'type_cible' => 'required|in_list[augmenter,reduire,maintenir]',
            'depense_calorique_estimee' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->activiteModel->insert([
            'nom_activite' => $this->request->getPost('nom_activite'),
            'description' => $this->request->getPost('description'),
            'type_cible' => $this->request->getPost('type_cible'),
            'depense_calorique_estimee' => $this->request->getPost('depense_calorique_estimee')
        ]);

        return redirect()->to('/admin/activites')->with('success', 'Activité créée');
    }

    public function edit($id)
    {
        $check = $this->requireAdmin();
        if ($check) return $check;

        $activite = $this->activiteModel->find($id);
        if (!$activite) {
            return redirect()->to('/admin/activites')->with('error', 'Activité non trouvée');
        }

        $data = [
            'activite' => $activite,
            'admin_nom' => session()->get('admin_nom')
        ];
        return view('admin/activites/edit', $data);
    }

    public function update($id)
    {
        $check = $this->requireAdmin();
        if ($check) return $check;

        $rules = [
            'nom_activite' => 'required|min_length[3]',
            'description' => 'required',
            'type_cible' => 'required|in_list[augmenter,reduire,maintenir]',
            'depense_calorique_estimee' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->activiteModel->update($id, [
            'nom_activite' => $this->request->getPost('nom_activite'),
            'description' => $this->request->getPost('description'),
            'type_cible' => $this->request->getPost('type_cible'),
            'depense_calorique_estimee' => $this->request->getPost('depense_calorique_estimee')
        ]);

        return redirect()->to('/admin/activites')->with('success', 'Activité mise à jour');
    }

    public function delete($id)
    {
        $check = $this->requireAdmin();
        if ($check) return $check;

        $this->activiteModel->delete($id);
        return redirect()->to('/admin/activites')->with('success', 'Activité supprimée');
    }
}