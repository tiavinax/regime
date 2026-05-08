<?php

namespace App\Controllers;

use App\Models\UserModel;

class LoginController extends BaseController {

    
    public function save() {

        $model = new UserModel();

        $nom = $this->request->getPost('nom');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $genre = $this->request->getPost('genre');
        $date_naissance = $this->request->getPost('date_naissance');
      
        $model->save([
            'nom' => $nom,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'genre' => $genre,
            'date_naissance' => $date_naissance
        ]);

        return redirect()->to('/login');
    }
}
