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

    // ==================== DASHBOARD ====================

    /**
     * Affiche le dashboard admin avec statistiques
     * URL: /admin ou /admin/dashboard
     */
    public function index()
    {
        // Vérifier l'accès admin
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;

        // Données pour le dashboard
        $data = [
            'admin_nom' => session()->get('user_nom'),
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

    // ==================== CRUD RÉGIMES ====================
    
    public function regimes()
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $data['regimes'] = $this->regimeModel->findAll();
        return view('admin/regimes/index', $data);
    }
    
    public function createRegime()
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        return view('admin/regimes/create');
    }
    
    public function storeRegime()
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $this->regimeModel->insert($this->request->getPost());
        return redirect()->to('/admin/regimes')->with('success', 'Régime créé');
    }
    
    public function editRegime($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $data['regime'] = $this->regimeModel->find($id);
        return view('admin/regimes/edit', $data);
    }
    
    public function updateRegime($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $this->regimeModel->update($id, $this->request->getPost());
        return redirect()->to('/admin/regimes')->with('success', 'Régime modifié');
    }
    
    public function deleteRegime($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $this->regimeModel->delete($id);
        return redirect()->to('/admin/regimes')->with('success', 'Régime supprimé');
    }

    // ==================== CRUD ACTIVITÉS ====================
    
    public function activites()
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $data['activites'] = $this->activiteModel->findAll();
        return view('admin/activites/index', $data);
    }
    
    public function createActivite()
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        return view('admin/activites/create');
    }
    
    public function storeActivite()
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $this->activiteModel->insert($this->request->getPost());
        return redirect()->to('/admin/activites')->with('success', 'Activité créée');
    }
    
    public function editActivite($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $data['activite'] = $this->activiteModel->find($id);
        return view('admin/activites/edit', $data);
    }
    
    public function updateActivite($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $this->activiteModel->update($id, $this->request->getPost());
        return redirect()->to('/admin/activites')->with('success', 'Activité modifiée');
    }
    
    public function deleteActivite($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $this->activiteModel->delete($id);
        return redirect()->to('/admin/activites')->with('success', 'Activité supprimée');
    }

    // ==================== CRUD UTILISATEURS ====================
    
    public function users()
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $data['users'] = $this->userModel->findAll();
        return view('admin/users/index', $data);
    }
    
    public function viewUser($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $data['user'] = $this->userModel->find($id);
        $data['profil'] = $this->profilModel->getLastProfilByUser($id);
        $data['objectif'] = $this->objectifModel->getObjectifActif($id);
        return view('admin/users/view', $data);
    }
    
    public function editUser($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $data['user'] = $this->userModel->find($id);
        return view('admin/users/edit', $data);
    }
    
    public function updateUser($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $this->userModel->update($id, $this->request->getPost());
        return redirect()->to('/admin/users')->with('success', 'Utilisateur modifié');
    }
    
    public function toggleGold($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $user = $this->userModel->find($id);
        $this->userModel->update($id, ['is_gold' => $user['is_gold'] ? 0 : 1]);
        return redirect()->back()->with('success', 'Statut Gold modifié');
    }

    // ==================== GESTION DES CODES ====================
    
    public function codes()
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $db = \Config\Database::connect();
        $data['codes'] = $db->table('code_promo')->get()->getResultArray();
        return view('admin/codes/index', $data);
    }
    
    public function createCode()
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        return view('admin/codes/create');
    }
    
    public function storeCode()
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $db = \Config\Database::connect();
        $db->table('code_promo')->insert([
            'code' => strtoupper($this->request->getPost('code')),
            'valeur' => $this->request->getPost('valeur'),
            'est_utilise' => 0
        ]);
        
        return redirect()->to('/admin/codes')->with('success', 'Code promo créé');
    }
    
    public function deleteCode($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        $db = \Config\Database::connect();
        $db->table('code_promo')->delete(['id' => $id]);
        
        return redirect()->to('/admin/codes')->with('success', 'Code promo supprimé');
    }

    // ==================== PARAMÈTRES ====================
    
    public function parametres()
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;
        
        return view('admin/parametres/index');
    }

    // ==================== STATISTIQUES (méthodes privées) ====================
    
    private function getTotalUsers(): int
    {
        return $this->userModel->countAll();
    }

    private function getTotalGold(): int
    {
        return $this->userModel->where('is_gold', 1)->countAllResults();
    }

    private function getTotalObjectifs(): int
    {
        return $this->objectifModel->where('status', 'en_cours')->countAllResults();
    }

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

    private function getRecentUsers(): array
    {
        return $this->userModel
            ->select('id, nom, email, genre, date_inscription, is_gold, role')
            ->orderBy('date_inscription', 'DESC')
            ->limit(10)
            ->findAll();
    }

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