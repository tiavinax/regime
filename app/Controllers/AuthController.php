<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProfilPhysiqueModel;
use App\Models\ObjectifModel;
use App\Models\BesoinCaloriqueModel;

class AuthController extends BaseController
{
    // ==================== AFFICHAGE DES VUES ====================

    public function index()
    {
        return view('index');
    }

    public function template()
    {
        return view('page');
    }

    public function login()
    {
        // Si déjà connecté, rediriger vers dashboard
        if (session()->has('user_id')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function registerStep1()
    {
        // Si déjà connecté, rediriger
        if (session()->has('user_id')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/register_step1');
    }

    public function registerStep2()
    {
        // Vérifier que l'étape 1 a été validée
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
        // Validation des données
        $rules = [
            'nom' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[utilisateur.email]',
            'genre' => 'required|in_list[homme,femme]',
            'date_naissance' => 'required|valid_date',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
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

        // Marquer l'étape 1 comme validée
        session()->set('register_step1_validated', true);

        return redirect()->to('/register/step2');
    }

    /**
     * Traite l'étape 2 : informations santé et objectif
     */
    public function doRegisterStep2()
    {
        // Vérifier que l'étape 1 existe
        if (!session()->has('register_data_step1')) {
            return redirect()->to('/register/step1')
                ->with('error', 'Session expirée, veuillez recommencer');
        }

        // Validation des données santé
        $rules = [
            'taille_cm' => 'required|numeric|greater_than[50]|less_than[250]',
            'poids_kg' => 'required|numeric|greater_than[10]|less_than[300]',
            'niveau_activite' => 'required|in_list[sedentaire,leger,modere,actif,extreme]',
            'type_objectif' => 'required|in_list[augmenter_poids,reduire_poids,imc_ideal]',
            'duree_souhaitee_semaines' => 'permit_empty|numeric|greater_than[0]|less_than[52]'
        ];

        // Validation conditionnelle : poids cible requis si objectif = IMC idéal
        if ($this->request->getPost('type_objectif') === 'imc_ideal') {
            $rules['poids_cible_kg'] = 'required|numeric|greater_than[10]|less_than[300]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Récupérer les données étape 1
        $userData = session()->get('register_data_step1');

        // 1. Créer l'utilisateur
        $userModel = new UserModel();
        $userId = $userModel->insert([
            'nom' => $userData['nom'],
            'email' => $userData['email'],
            'password' => $userData['password'],
            'genre' => $userData['genre'],
            'date_naissance' => $userData['date_naissance'],
            'is_gold' => 0  // Par défaut, pas gold
        ]);

        if (!$userId) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création du compte');
        }

        // 2. Créer le profil physique
        $profilModel = new ProfilPhysiqueModel();
        $profilId = $profilModel->insert([
            'id_utilisateur' => $userId,
            'poids_kg' => $this->request->getPost('poids_kg'),
            'taille_cm' => $this->request->getPost('taille_cm'),
            'niveau_activite' => $this->request->getPost('niveau_activite'),
            'date_mesure' => date('Y-m-d H:i:s')
        ]);

        // 3. Créer l'objectif
        $objectifModel = new ObjectifModel();
        $objectifData = [
            'id_utilisateur' => $userId,
            'type_objectif' => $this->request->getPost('type_objectif'),
            'date_debut' => date('Y-m-d'),
            'status' => 'en_cours',
            'duree_souhaitee_semaines' => $this->request->getPost('duree_souhaitee_semaines')
        ];

        // Ajouter poids cible si objectif = imc_ideal
        if ($this->request->getPost('type_objectif') === 'imc_ideal') {
            $objectifData['poids_cible_kg'] = $this->request->getPost('poids_cible_kg');
        }

        $objectifModel->insert($objectifData);

        // Optionnel : Calculer et stocker le besoin calorique
        $besoinModel = new BesoinCaloriqueModel();

        // Récupérer les données pour calcul
        $userData = session()->get('register_data_step1');
        $tailleCm = $this->request->getPost('taille_cm');
        $poidsKg = $this->request->getPost('poids_kg');
        $niveauActivite = $this->request->getPost('niveau_activite');
        $typeObjectif = $this->request->getPost('type_objectif');

        $age = $profilModel->calculerAge($userData['date_naissance']);
        $mb = $profilModel->calculerMetabolismeBase($userData['genre'], $poidsKg, $tailleCm, $age);
        $besoinMaintien = $profilModel->calculerBesoinMaintien($mb, $niveauActivite);
        $besoinObjectif = $profilModel->calculerBesoinObjectif(
            $besoinMaintien,
            $typeObjectif,
            $this->request->getPost('poids_cible_kg'),
            $poidsKg
        );

        $besoinModel->insert([
            'id_objectif' => $objectifModel->getInsertID(),
            'id_profil' => $profilId,
            'metabolisme_base_kcal' => $mb,
            'besoin_maintien_kcal' => $besoinMaintien,
            'besoin_objectif_kcal' => $besoinObjectif
        ]);

        // 4. Connecter l'utilisateur automatiquement
        session()->set([
            'user_id' => $userId,
            'user_nom' => $userData['nom'],
            'user_email' => $userData['email'],
            'user_genre' => $userData['genre'],
            'is_gold' => 0,
            'is_logged_in' => true
        ]);

        // 5. Nettoyer la session temporaire
        session()->remove(['register_data_step1', 'register_step1_validated']);

        // Rediriger vers le tableau de bord pour afficher l'IMC et les suggestions
        return redirect()->to('/dashboard')
            ->with('success', 'Bienvenue ' . $userData['nom'] . ' ! Votre compte a été créé avec succès.');
    }

    /**
     * Traite la connexion
     */
    public function doLogin()
    {
        // Validation
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

        // Vérifier les identifiants
        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email ou mot de passe incorrect');
        }

        // Stocker en session
        session()->set([
            'user_id' => $user['id'],
            'user_nom' => $user['nom'],
            'user_email' => $user['email'],
            'user_genre' => $user['genre'],
            'is_gold' => $user['is_gold'] ?? 0,
            'is_logged_in' => true
        ]);

        // Rediriger selon le rôle
        if (isset($user['role']) && $user['role'] === 'admin') {
            return redirect()->to('/admin');
        }

        return redirect()->to('/dashboard')
            ->with('success', 'Bonjour ' . $user['nom'] . ' !');
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')
            ->with('success', 'Vous êtes déconnecté');
    }
}
