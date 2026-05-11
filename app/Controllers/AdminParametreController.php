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
            'parametres' => $this->parametreModel->findAll(),
            'admin_nom' => session()->get('admin_nom')
        ];
        return view('admin/parametres/index', $data);
    }

    public function update($id)
    {
        $check = $this->requireAdmin();
        if ($check) return $check;

        $valeur = $this->request->getPost('valeur');
        $this->parametreModel->update($id, ['valeur' => $valeur]);

        return redirect()->to('/admin/parametres')->with('success', 'Paramètre mis à jour');
    }
}