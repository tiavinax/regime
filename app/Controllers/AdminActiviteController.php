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

    private function checkAdmin()
    {
        if (!session()->get('isAdminLoggedIn')) {
            return redirect()->to('/admin/login')->with('error', 'Veuillez vous connecter');
        }
        return null;
    }

    public function index()
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $data = [
            'activites' => $this->activiteModel->findAll(),
            'admin_nom' => session()->get('admin_nom')
        ];
        return view('admin/activites/index', $data);
    }

    public function create()
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        return view('admin/activites/create', ['admin_nom' => session()->get('admin_nom')]);
    }

    public function store()
    {
        $check = $this->checkAdmin();
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
        $check = $this->checkAdmin();
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
        $check = $this->checkAdmin();
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
        $check = $this->checkAdmin();
        if ($check) return $check;

        $this->activiteModel->delete($id);
        return redirect()->to('/admin/activites')->with('success', 'Activité supprimée');
    }
}