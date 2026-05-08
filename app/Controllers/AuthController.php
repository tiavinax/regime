<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index() {
        return view('index');
    }
    public function template() {
        return view('page');
    }

    public function form() {
        return view('auth/login');
    }
    public function login() {
        $model = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user = $model->where('email', $email)->first();
        if (!$user || !password_verify($password, $user['password'])) {
            return view('auth/login', [
                'erreur' => 'Email ou mot de passe incorrect'
            ]);
        }
        // Stocker uniquement les données non sensibles en session
        session()->set('utilisateur', [
            'id'
            => $user['id'],
            'nom'
            => $user['nom'],
            'email' => $user['email'],
            'role' => $user['role'],
            // 'admin' | 'visiteur' |
            'lecteur'
        ]);
        return redirect()->to('/admin');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
