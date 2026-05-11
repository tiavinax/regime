<?php

namespace App\Controllers;

use App\Models\WalletModel;
use App\Models\UserModel;

class GoldController extends BaseController
{
    protected $walletModel;
    protected $userModel;

    public function __construct()
    {
        $this->walletModel = new WalletModel();
        $this->userModel = new UserModel();
    }

    /**
     * Affiche la page d'achat Gold
     */
    public function index()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter');
        }

        $userId = session()->get('user_id');

        // Recharger l'utilisateur depuis la BDD
        $user = $this->userModel->find($userId);
        $isGold = $user['is_gold'] ?? 0;

        if ($isGold) {
            session()->set('is_gold', $isGold);
            return redirect()->to('/dashboard')->with('info', 'Vous êtes déjà membre Gold ! 💎');
        }

        $solde = $this->walletModel->getSolde($userId);
        $prixGold = 49.99;

        return view('gold/index', [
            'solde' => $solde,
            'prix_gold' => $prixGold,
            'solde_suffisant' => $solde >= $prixGold
        ]);
    }

    /**
     * Traite l'achat de l'option Gold
     */
    public function acheter()
    {
        // Vérifier la méthode HTTP (insensible à la casse)
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to('/gold');
        }

        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter');
        }

        $userId = session()->get('user_id');

        // Recharger l'utilisateur depuis la BDD
        $user = $this->userModel->find($userId);
        $isGold = $user['is_gold'] ?? 0;

        // Si déjà Gold
        if ($isGold) {
            return redirect()->to('/dashboard')->with('info', 'Vous êtes déjà membre Gold');
        }

        $prixGold = 49.99;
        $solde = $this->walletModel->getSolde($userId);

        // Vérifier le solde
        if ($solde < $prixGold) {
            return redirect()->back()->with('error', 'Solde insuffisant. Il vous manque ' . number_format($prixGold - $solde, 2) . ' €');
        }

        // 1. Débiter le wallet
        $success = $this->walletModel->debiter($userId, $prixGold, 'Achat option Gold (remise à vie -15%)');

        if (!$success) {
            return redirect()->back()->with('error', 'Erreur lors du débit du porte-monnaie');
        }

        // 2. Mettre à jour is_gold
        $updateResult = $this->userModel->setGoldStatus($userId, 1);

        if (!$updateResult) {
            // Si erreur, recréditer le wallet
            $this->walletModel->crediter($userId, $prixGold, 'Annulation achat Gold - remboursement');
            return redirect()->back()->with('error', 'Erreur lors de l\'activation du compte Gold');
        }

        // 3. Mettre à jour la session
        session()->set('is_gold', 1);

        $nouveauSolde = $this->walletModel->getSolde($userId);

        return redirect()->to('/dashboard')->with(
            'success',
            '🎉 Félicitations ! Vous êtes maintenant membre Gold !<br>' .
                '💎 15% de réduction sur tous les régimes.<br>' .
                '💰 Solde restant : ' . number_format($nouveauSolde, 2) . ' €'
        );
    }

    /**
     * Test direct - Méthode debug
     * URL: /gold/test-update
     */

    public function testUpdate()
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        $userId = 6;

        echo "<h1>Test mise à jour is_gold</h1>";

        $user = $this->userModel->find($userId);
        echo "<p>État actuel: is_gold = " . ($user['is_gold'] ?? 'null') . "</p>";

        $this->userModel->setValidationRules([]);
        $result = $this->userModel->setGoldStatus($userId, 1);

        echo "<p>Résultat update: " . ($result ? 'true' : 'false') . "</p>";

        if (!$result) {
            $errors = $this->userModel->errors();
            echo "<pre>Erreurs: ";
            print_r($errors);
            echo "</pre>";
        }

        $user2 = $this->userModel->find($userId);
        echo "<p>Après update: is_gold = " . ($user2['is_gold'] ?? 'null') . "</p>";

        $db = \Config\Database::connect();
        $sql = "SELECT is_gold FROM utilisateur WHERE id = ?";
        $query = $db->query($sql, [$userId]);
        $result = $query->getRow();
        echo "<p>SQL direct: is_gold = " . ($result->is_gold ?? 'null') . "</p>";
    }

    public function testAcheter()
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        echo "<h1>=== DEBUG COMPLET ACHAT GOLD ===</h1>";

        // Simuler l'utilisateur connecté
        $userId = 4; // Utilisateur de test
        echo "<p>1. User ID: " . $userId . "</p>";

        // Vérifier l'état actuel
        $user = $this->userModel->find($userId);
        echo "<p>2. État actuel is_gold: " . ($user['is_gold'] ?? 'null') . "</p>";
        echo "<p>2b. Role: " . ($user['role'] ?? 'null') . "</p>";

        // Vérifier le wallet
        $solde = $this->walletModel->getSolde($userId);
        echo "<p>3. Solde wallet: " . $solde . " €</p>";

        $prixGold = 49.99;
        echo "<p>4. Prix Gold: " . $prixGold . " €</p>";

        // Vérifier si solde suffisant
        $soldeSuffisant = $solde >= $prixGold;
        echo "<p>5. Solde suffisant: " . ($soldeSuffisant ? 'OUI' : 'NON') . "</p>";

        if (!$soldeSuffisant) {
            echo "<p style='color:red'>❌ Solde insuffisant pour acheter Gold</p>";
            die();
        }

        // 1. Débiter le wallet
        echo "<p>6. Tentative de débit...<br>";
        $success = $this->walletModel->debiter($userId, $prixGold, 'Test achat Gold');
        echo "Résultat débit: " . ($success ? '✓ SUCCÈS' : '✗ ÉCHEC') . "</p>";

        if (!$success) {
            echo "<p style='color:red'>❌ Échec du débit</p>";
            die();
        }

        // Vérifier nouveau solde
        $nouveauSolde = $this->walletModel->getSolde($userId);
        echo "<p>7. Nouveau solde après débit: " . $nouveauSolde . " €</p>";

        // 2. Mettre à jour is_gold
        echo "<p>8. Tentative mise à jour is_gold...<br>";
        $updateResult = $this->userModel->setGoldStatus($userId, 1);
        echo "Résultat update: " . ($updateResult ? '✓ SUCCÈS' : '✗ ÉCHEC') . "</p>";

        // 3. Vérifier après mise à jour
        $userUpdated = $this->userModel->find($userId);
        echo "<p>9. is_gold après update: " . ($userUpdated['is_gold'] ?? 'null') . "</p>";

        // 4. Vérifier la session
        session()->set('is_gold', 1);
        echo "<p>10. Session is_gold: " . session()->get('is_gold') . "</p>";

        if ($userUpdated['is_gold'] == 1) {
            echo "<p style='color:green'>✅ SUCCÈS TOTAL ! L'utilisateur est maintenant Gold</p>";
        } else {
            echo "<p style='color:red'>❌ ÉCHEC : is_gold n'a pas été mis à jour</p>";
        }

        // die();

        // Rediriger avec message de succès
        return redirect()->to('/dashboard')->with(
            'success',
            '🎉 Félicitations ! Vous êtes maintenant membre Gold !<br>' .
                '💎 15% de réduction sur tous les régimes.<br>' .
                '💰 Solde restant : ' . number_format($nouveauSolde, 2) . ' €'
        );
    }

    public function testDebiter()
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        $userId = 4;
        $montant = 10;

        echo "<h1>Test débit wallet</h1>";

        // Solde avant
        $soldeAvant = $this->walletModel->getSolde($userId);
        echo "<p>Solde avant: " . $soldeAvant . " €</p>";

        // Débiter
        $result = $this->walletModel->debiter($userId, $montant, 'Test débit');
        echo "<p>Résultat débit: " . ($result ? 'SUCCÈS' : 'ÉCHEC') . "</p>";

        // Solde après
        $soldeApres = $this->walletModel->getSolde($userId);
        echo "<p>Solde après: " . $soldeApres . " €</p>";

        // Vérifier la transaction
        $db = \Config\Database::connect();
        $transactions = $db->table('transaction')
            ->where('id_utilisateur', $userId)
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        echo "<p>Dernière transaction:</p>";
        echo "<pre>";
        print_r($transactions);
        echo "</pre>";

        die();
    }

    public function testSetGold()
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        $userId = 4;

        echo "<h1>Test setGoldStatus</h1>";

        // État avant
        $userAvant = $this->userModel->find($userId);
        echo "<p>is_gold avant: " . ($userAvant['is_gold'] ?? 'null') . "</p>";

        // Mettre à True
        $resultTrue = $this->userModel->setGoldStatus($userId, 1);
        echo "<p>Set à 1: " . ($resultTrue ? 'SUCCÈS' : 'ÉCHEC') . "</p>";

        $userApres = $this->userModel->find($userId);
        echo "<p>is_gold après set 1: " . ($userApres['is_gold'] ?? 'null') . "</p>";

        // Remettre à False
        $resultFalse = $this->userModel->setGoldStatus($userId, 0);
        echo "<p>Set à 0: " . ($resultFalse ? 'SUCCÈS' : 'ÉCHEC') . "</p>";

        $userFinal = $this->userModel->find($userId);
        echo "<p>is_gold final: " . ($userFinal['is_gold'] ?? 'null') . "</p>";

        die();
    }
}
