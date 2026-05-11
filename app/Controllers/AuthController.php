<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProfilPhysiqueModel;

class AuthController extends BaseController
{
    // ==================== AFFICHAGE DES VUES ====================

    public function login()
    {
        if (session()->has('user_id')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function registerStep1()
    {
        if (session()->has('user_id')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/register_step1');
    }

    public function registerStep2()
    {
        if (!session()->has('register_step1_validated')) {
            return redirect()->to('/register/step1')
                ->with('error', 'Veuillez d\'abord compléter l\'étape 1');
        }
        return view('auth/register_step2');
    }

    public function home()
    {
        return view('pages/home');
    }
    
    // ==================== TRAITEMENT DES FORMULAIRES ====================

    /**
     * Traite l'étape 1 : informations personnelles
     */
    public function doRegisterStep1()
    {
        // Validation sans confirm_password
        $rules = [
            'nom' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[utilisateur.email]',
            'genre' => 'required|in_list[homme,femme]',
            'date_naissance' => 'required|valid_date',
            'password' => 'required|min_length[4]'  // Plus besoin de confirm_password
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Stocker les données en session temporaire
        session()->set('register_data_step1', [
            'nom' => $this->request->getPost('nom'),
            'email' => $this->request->getPost('email'),
            'genre' => $this->request->getPost('genre'),
            'date_naissance' => $this->request->getPost('date_naissance'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ]);

        session()->set('register_step1_validated', true);

        return redirect()->to('/register/step2');
    }

    /**
     * Traite l'étape 2 : informations santé
     */
    /**
     * Traite l'étape 2 : informations santé
     */
    public function doRegisterStep2()
    {
        // Vérifier si l'étape 1 existe
        if (!session()->has('register_data_step1')) {
            return redirect()->to('/register/step1')
                ->with('error', 'Session expirée, veuillez recommencer');
        }

        // Validation des données santé
        $rules = [
            'taille_cm' => 'required|numeric|greater_than[50]|less_than[250]',
            'poids_kg' => 'required|numeric|greater_than[10]|less_than[300]',
            'niveau_activite' => 'required|in_list[sedentaire,leger,modere,actif,extreme]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Récupérer les données étape 1
        $userData = session()->get('register_data_step1');

        // Connexion à la base de données
        $db = \Config\Database::connect();

        // 1. Insertion utilisateur
        $userDataToInsert = [
            'nom' => $userData['nom'],
            'email' => $userData['email'],
            'password' => $userData['password'],
            'genre' => $userData['genre'],
            'date_naissance' => $userData['date_naissance'],
            'is_gold' => 0,
            'role' => 'user'
        ];

        $builder = $db->table('utilisateur');
        $builder->insert($userDataToInsert);
        $userId = $db->insertID();

        if (!$userId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du compte');
        }

        // 2. Insertion profil physique
        $profilData = [
            'id_utilisateur' => $userId,
            'poids_kg' => floatval($this->request->getPost('poids_kg')),
            'taille_cm' => floatval($this->request->getPost('taille_cm')),
            'niveau_activite' => $this->request->getPost('niveau_activite'),
            'date_mesure' => date('Y-m-d H:i:s')
        ];

        $builder2 = $db->table('profil_physique');
        $builder2->insert($profilData);
        $profilId = $db->insertID();

        if (!$profilId) {
            // Si erreur profil, supprimer l'utilisateur créé
            $db->table('utilisateur')->where('id', $userId)->delete();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du profil santé');
        }

        // 3. Connecter l'utilisateur
        session()->set([
            'user_id' => $userId,
            'user_nom' => $userData['nom'],
            'user_email' => $userData['email'],
            'user_genre' => $userData['genre'],
            'is_gold' => 0,
            'is_logged_in' => true
        ]);

        // 4. Nettoyer la session temporaire
        session()->remove(['register_data_step1', 'register_step1_validated']);

        // 5. Rediriger vers le choix d'objectif
        return redirect()->to('/objectif/choisir')
            ->with('success', 'Bienvenue ' . $userData['nom'] . ' ! Votre compte a été créé. Définissez maintenant votre objectif.');
    }

    /**
     * Traite la connexion
     */
    public function doLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email ou mot de passe incorrect');
        }

        session()->set([
            'user_id' => $user['id'],
            'user_nom' => $user['nom'],
            'user_email' => $user['email'],
            'user_genre' => $user['genre'],
            'is_gold' => $user['is_gold'] ?? 0,
            'is_logged_in' => true
        ]);

        // Vérifier si l'utilisateur a déjà un objectif
        $objectifModel = new \App\Models\ObjectifModel();
        $objectifActif = $objectifModel->getObjectifActif($user['id']);

        if ($objectifActif) {
            return redirect()->to('/dashboard')->with('success', 'Bonjour ' . $user['nom'] . ' !');
        } else {
            return redirect()->to('/objectif/choisir')->with('info', 'Définissez votre objectif pour commencer');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Vous êtes déconnecté');
    }
}
