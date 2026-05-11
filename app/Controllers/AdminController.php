<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ObjectifModel;
use App\Models\ProfilPhysiqueModel;
use App\Models\RegimeModel;
use App\Models\ActiviteSportiveModel;
use App\Models\SuggestionModel;

class AdminController extends BaseController
{
    protected $userModel;
    protected $objectifModel;
    protected $profilModel;
    protected $regimeModel;
    protected $activiteModel;
    protected $suggestionModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->objectifModel = new ObjectifModel();
        $this->profilModel = new ProfilPhysiqueModel();
        $this->regimeModel = new RegimeModel();
        $this->activiteModel = new ActiviteSportiveModel();
        $this->suggestionModel = new SuggestionModel();
        helper('form');
        
        // Vérifier si l'utilisateur connecté est admin via la session existante
        $this->checkAdminSession();
    }
    
    /**
     * Vérifie si l'utilisateur connecté est admin
     * Utilise la session existante de AuthController
     */
    private function checkAdminSession()
    {
        // Si l'utilisateur est connecté via AuthController et qu'il est admin
        if (session()->has('user_id') && session()->has('role')) {
            if (session()->get('role') === 'admin') {
                // Créer la session admin si elle n'existe pas
                if (!session()->has('isAdminLoggedIn')) {
                    session()->set([
                        'admin_id' => session()->get('user_id'),
                        'admin_nom' => session()->get('user_nom'),
                        'admin_email' => session()->get('user_email'),
                        'isAdminLoggedIn' => true
                    ]);
                }
            }
        }
        
        // Alternative: Vérifier directement dans la base de données
        if (session()->has('user_id') && !session()->has('isAdminLoggedIn')) {
            $user = $this->userModel->find(session()->get('user_id'));
            if ($user && $user['role'] === 'admin') {
                session()->set([
                    'admin_id' => $user['id'],
                    'admin_nom' => $user['nom'],
                    'admin_email' => $user['email'],
                    'isAdminLoggedIn' => true
                ]);
            }
        }
    }

    // ==================== AUTHENTIFICATION ====================
    
    /**
     * Affiche le formulaire de connexion admin
     * URL: /admin/login
     */
    public function login()
    {
        // Vérifier si déjà admin via la session existante
        if (session()->has('isAdminLoggedIn') || 
            (session()->has('user_id') && $this->isUserAdmin(session()->get('user_id')))) {
            return redirect()->to('/admin');
        }
        
        return view('admin/login');
    }
    
    /**
     * Vérifie si un utilisateur est admin
     */
    private function isUserAdmin($userId): bool
    {
        $user = $this->userModel->find($userId);
        return $user && isset($user['role']) && $user['role'] === 'admin';
    }

    /**
     * Traite la connexion admin
     * URL: POST /admin/doLogin
     */
    public function doLogin()
{
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    if (empty($email) || empty($password)) {
        return redirect()->back()->withInput()->with('error', 'Veuillez remplir tous les champs');
    }

    // Récupérer l'utilisateur
    $user = $this->userModel->where('email', $email)->first();

    if (!$user) {
        return redirect()->back()->withInput()->with('error', 'Email non trouvé');
    }

    // Vérification SIMPLE sans hash (mot de passe en clair)
    if ($password !== $user['password']) {
        return redirect()->back()->withInput()->with('error', 'Mot de passe incorrect');
    }

    // Vérifier le rôle admin
    if ($user['role'] !== 'admin') {
        return redirect()->back()->withInput()->with('error', 'Accès réservé aux administrateurs');
    }

    // Connexion réussie - Manambatra ny session admin sy user
    session()->set([
        // Session admin
        'admin_id' => $user['id'],
        'admin_nom' => $user['nom'],
        'admin_email' => $user['email'],
        'isAdminLoggedIn' => true,
        // Session user (mba hisehoan'ny rohy admin)
        'user_id' => $user['id'],
        'user_nom' => $user['nom'],
        'user_email' => $user['email'],
        'role' => $user['role'],
        'is_logged_in' => true
    ]);

    return redirect()->to('/admin')->with('success', 'Bienvenue ' . $user['nom']);
}
    /**
     * Déconnexion admin
     * URL: /admin/logout
     */
    public function logout()
    {
        session()->remove(['admin_id', 'admin_nom', 'admin_email', 'isAdminLoggedIn']);
        return redirect()->to('/admin/login')->with('success', 'Déconnecté de l\'espace admin');
    }

    // ==================== DASHBOARD ====================

    /**
     * Affiche le dashboard admin avec statistiques
     * URL: /admin ou /admin/dashboard
     */
    public function index()
    {
        // Vérifier si l'admin est connecté
        if (!session()->get('isAdminLoggedIn')) {
            // Vérifier si l'utilisateur connecté via AuthController est admin
            if (session()->has('user_id') && $this->isUserAdmin(session()->get('user_id'))) {
                session()->set([
                    'admin_id' => session()->get('user_id'),
                    'admin_nom' => session()->get('user_nom'),
                    'admin_email' => session()->get('user_email'),
                    'isAdminLoggedIn' => true
                ]);
            } else {
                return redirect()->to('/admin/login')->with('error', 'Veuillez vous connecter');
            }
        }

        // Données pour le dashboard
        $data = [
            'admin_nom' => session()->get('admin_nom'),
            'total_users' => $this->getTotalUsers(),
            'total_gold' => $this->getTotalGold(),
            'total_regimes' => $this->regimeModel->countAll(),
            'total_activites' => $this->activiteModel->countAll(),
            'total_objectifs' => $this->getTotalObjectifs(),
            'objectifs_stats' => $this->getObjectifsStats(),
            'repartition_imc' => $this->getRepartitionIMC(),
            'top_regimes' => $this->getTopRegimes(),
            'recent_users' => $this->getRecentUsers(),
            'evolution_inscriptions' => $this->getEvolutionInscriptions()
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Nombre total d'utilisateurs
     */
    private function getTotalUsers(): int
    {
        return $this->userModel->countAll();
    }

    /**
     * Nombre d'utilisateurs Gold
     */
    private function getTotalGold(): int
    {
        return $this->userModel->where('is_gold', 1)->countAllResults();
    }

    /**
     * Nombre total d'objectifs en cours
     */
    private function getTotalObjectifs(): int
    {
        return $this->objectifModel->where('status', 'en_cours')->countAllResults();
    }

    /**
     * Statistiques des objectifs en cours par type
     */
    private function getObjectifsStats(): array
    {
        $db = \Config\Database::connect();
        
        $results = $db->table('objectif')
            ->select('type_objectif, COUNT(*) as count')
            ->where('status', 'en_cours')
            ->groupBy('type_objectif')
            ->get()
            ->getResultArray();

        $stats = [
            'augmenter_poids' => 0,
            'reduire_poids' => 0,
            'imc_ideal' => 0
        ];

        foreach ($results as $row) {
            if (isset($stats[$row['type_objectif']])) {
                $stats[$row['type_objectif']] = (int) $row['count'];
            }
        }

        return $stats;
    }

    /**
     * Répartition IMC des utilisateurs
     */
    private function getRepartitionIMC(): array
    {
        $profils = $this->profilModel->findAll();
        
        $categories = [
            'denutrition' => 0,
            'maigreur' => 0,
            'normal' => 0,
            'surpoids' => 0,
            'obesite_moderee' => 0,
            'obesite_severe' => 0,
            'obesite_massive' => 0
        ];

        foreach ($profils as $profil) {
            $imc = $this->profilModel->calculerIMC(
                (float) $profil['poids_kg'],
                (float) $profil['taille_cm']
            );
            
            if ($imc < 16.5) $categories['denutrition']++;
            elseif ($imc < 18.5) $categories['maigreur']++;
            elseif ($imc < 25) $categories['normal']++;
            elseif ($imc < 30) $categories['surpoids']++;
            elseif ($imc < 35) $categories['obesite_moderee']++;
            elseif ($imc < 40) $categories['obesite_severe']++;
            else $categories['obesite_massive']++;
        }

        return $categories;
    }

    /**
     * Top 3 régimes les plus suggérés
     */
    private function getTopRegimes(): array
    {
        $db = \Config\Database::connect();
        
        return $db->table('suggestion')
            ->select('regime.id, regime.nom_regime, COUNT(suggestion.id_regime) as count')
            ->join('regime', 'regime.id = suggestion.id_regime')
            ->groupBy('suggestion.id_regime, regime.id, regime.nom_regime')
            ->orderBy('count', 'DESC')
            ->limit(3)
            ->get()
            ->getResultArray();
    }

    /**
     * Derniers utilisateurs inscrits
     */
    private function getRecentUsers(): array
    {
        return $this->userModel
            ->select('id, nom, email, genre, date_inscription, is_gold, role')
            ->orderBy('date_inscription', 'DESC')
            ->limit(5)
            ->findAll();
    }

    /**
     * Évolution des inscriptions (6 derniers mois)
     */
    private function getEvolutionInscriptions(): array
    {
        $db = \Config\Database::connect();
        
        $results = $db->table('utilisateur')
            ->select("DATE_FORMAT(date_inscription, '%Y-%m') as mois, COUNT(*) as count")
            ->where('date_inscription >= DATE_SUB(NOW(), INTERVAL 6 MONTH)')
            ->groupBy("DATE_FORMAT(date_inscription, '%Y-%m')")
            ->orderBy('mois', 'ASC')
            ->get()
            ->getResultArray();

        $mois = [];
        $counts = [];
        
        foreach ($results as $row) {
            $mois[] = $row['mois'];
            $counts[] = (int) $row['count'];
        }

        return ['mois' => $mois, 'counts' => $counts];
    }
}