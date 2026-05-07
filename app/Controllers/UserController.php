<?php

namespace App\Controllers;

use App\Models\UserModel;

class LoginController extends BaseController {


    // nom VARCHAR(100) NOT NULL,
    // email VARCHAR(150) UNIQUE NOT NULL,
    // password VARCHAR(255) NOT NULL,
    // genre ENUM('homme', 'femme') NOT NULL,
    // date_naissance DATE NOT NULL,
    // date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
    
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
