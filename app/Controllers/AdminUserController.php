<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProfilPhysiqueModel;
use App\Models\ObjectifModel;

class AdminUserController extends BaseController
{
    protected $userModel;
    protected $profilModel;
    protected $objectifModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->profilModel = new ProfilPhysiqueModel();
        $this->objectifModel = new ObjectifModel();
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
            'users' => $this->userModel->orderBy('date_inscription', 'DESC')->findAll(),
            'admin_nom' => session()->get('admin_nom')
        ];
        return view('admin/users/index', $data);
    }

    public function view($id)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Utilisateur non trouvé');
        }

        $profil = $this->profilModel->getLastProfilByUser($id);
        $objectif = $this->objectifModel->getObjectifActif($id);

        $data = [
            'user' => $user,
            'profil' => $profil,
            'objectif' => $objectif,
            'admin_nom' => session()->get('admin_nom')
        ];
        return view('admin/users/view', $data);
    }

    public function edit($id)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Utilisateur non trouvé');
        }

        $data = [
            'user' => $user,
            'admin_nom' => session()->get('admin_nom')
        ];
        return view('admin/users/edit', $data);
    }

    public function update($id)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $rules = [
            'nom' => 'required|min_length[2]',
            'email' => 'required|valid_email',
            'genre' => 'required|in_list[homme,femme]',
            'role' => 'required|in_list[user,admin]',
            'is_gold' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'nom' => $this->request->getPost('nom'),
            'email' => $this->request->getPost('email'),
            'genre' => $this->request->getPost('genre'),
            'role' => $this->request->getPost('role'),
            'is_gold' => $this->request->getPost('is_gold') ? 1 : 0
        ];

        // Mettre à jour mot de passe si fourni
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $updateData['password'] = $password;
        }

        $this->userModel->update($id, $updateData);
        return redirect()->to('/admin/users')->with('success', 'Utilisateur mis à jour');
    }

    public function delete($id)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        // Ne pas supprimer son propre compte
        if ($id == session()->get('admin_id')) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }

        $this->userModel->delete($id);
        return redirect()->to('/admin/users')->with('success', 'Utilisateur supprimé');
    }

    public function toggleGold($id)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $user = $this->userModel->find($id);
        if ($user) {
            $newGold = $user['is_gold'] ? 0 : 1;
            $this->userModel->update($id, ['is_gold' => $newGold]);
            $status = $newGold ? 'Membre Gold activé' : 'Membre Gold désactivé';
            return redirect()->back()->with('success', $status);
        }
        return redirect()->back()->with('error', 'Utilisateur non trouvé');
    }
}