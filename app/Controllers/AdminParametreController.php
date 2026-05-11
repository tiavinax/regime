<?php

namespace App\Controllers;

use App\Models\ParametreModel;

class AdminParametreController extends BaseController
{
    protected $parametreModel;

    public function __construct()
    {
        $this->parametreModel = new ParametreModel();
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
            'parametres' => $this->parametreModel->findAll(),
            'admin_nom' => session()->get('admin_nom')
        ];
        return view('admin/parametres/index', $data);
    }

    public function update($id)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $valeur = $this->request->getPost('valeur');
        $this->parametreModel->update($id, ['valeur' => $valeur]);

        return redirect()->to('/admin/parametres')->with('success', 'Paramètre mis à jour');
    }
}