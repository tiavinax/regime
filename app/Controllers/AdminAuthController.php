<?php

namespace App\Controllers;

use App\Models\UserModel;

class AdminAuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper('form');
    }

    // Afficher formulaire login admin
    public function login()
    {
        // Si déjà connecté en tant qu'admin
        if (session()->get('isAdminLoggedIn')) {
            return redirect()->to('/admin');
        }

        return view('admin/login');
    }

    // Traiter la connexion admin
    public function doLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Veuillez remplir tous les champs');
        }

        // Vérifier les identifiants
        $user = $this->userModel->verifyCredentials($email, $password);

        if (!$user) {
            return redirect()->back()->with('error', 'Email ou mot de passe incorrect');
        }

        // Vérifier le rôle admin
        if ($user['role'] !== 'admin') {
            return redirect()->back()->with('error', 'Accès réservé aux administrateurs');
        }

        // Créer session admin
        session()->set([
            'admin_id'        => $user['id'],
            'admin_nom'       => $user['nom'],
            'admin_email'     => $user['email'],
            'isAdminLoggedIn' => true
        ]);

        return redirect()->to('/admin')->with('success', 'Bienvenue ' . $user['nom']);
    }

    // Déconnexion admin
    public function logout()
    {
        session()->remove(['admin_id', 'admin_nom', 'admin_email', 'isAdminLoggedIn']);
        return redirect()->to('/admin/login')->with('success', 'Déconnecté');
    }
}