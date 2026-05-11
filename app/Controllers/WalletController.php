<?php

namespace App\Controllers;

use App\Models\WalletModel;
use App\Models\TransactionModel;
use App\Models\CodePromoModel;
use App\Models\RegimeModel;

class WalletController extends BaseController
{
    protected $walletModel;
    protected $transactionModel;
    protected $codeModel;
    protected $regimeModel;

    public function __construct()
    {
        $this->walletModel = new WalletModel();
        $this->transactionModel = new TransactionModel();
        $this->codeModel = new CodePromoModel();
        $this->regimeModel = new RegimeModel();
    }

    /**
     * Affiche le porte-monnaie
     * URL: GET /wallet
     */
    public function index()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter');
        }

        $userId = session()->get('user_id');

        $data = [
            'solde' => $this->walletModel->getSolde($userId),
            'transactions' => $this->transactionModel->getHistorique($userId, 20),
            'isGold' => session()->get('is_gold') ?? false
        ];

        return view('wallet/index', $data);
    }

    /**
     * Applique un code promo
     * URL: POST /wallet/appliquer-code
     */
    public function appliquerCode()
    {
        // Activer l'affichage des erreurs pour debug
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        if (!session()->has('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Veuillez vous connecter']);
        }

        $code = trim($this->request->getPost('code'));

        if (empty($code)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Veuillez entrer un code']);
        }

        $userId = session()->get('user_id');

        // Vérifier le code dans la base
        $codeData = $this->codeModel->where('code', strtoupper($code))->first();

        if (!$codeData) {
            return $this->response->setJSON(['success' => false, 'message' => 'Code promo invalide']);
        }

        // Vérifier si actif
        if (isset($codeData['est_actif']) && $codeData['est_actif'] == 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Code promo désactivé']);
        }

        // Vérifier si déjà utilisé (usage unique)
        if (isset($codeData['est_utilise']) && $codeData['est_utilise'] == 1) {
            return $this->response->setJSON(['success' => false, 'message' => 'Code déjà utilisé']);
        }

        // Vérifier les utilisations max
        if (isset($codeData['utilisations_max']) && $codeData['utilisations_max'] !== null) {
            if ($codeData['utilisations_actuelles'] >= $codeData['utilisations_max']) {
                return $this->response->setJSON(['success' => false, 'message' => 'Code promo expiré (max utilisations atteint)']);
            }
        }

        // Vérifier date expiration
        if (isset($codeData['date_expiration']) && $codeData['date_expiration'] && strtotime($codeData['date_expiration']) < time()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Code promo expiré']);
        }

        // Calculer le montant à créditer
        $montant = (float) $codeData['valeur'];

        // Créditer le wallet
        $this->walletModel->crediter($userId, $montant, 'Code promo: ' . strtoupper($code));

        // Mettre à jour le code
        $updateData = [
            'utilisations_actuelles' => ($codeData['utilisations_actuelles'] ?? 0) + 1,
            'id_utilisateur' => $userId,
            'date_utilisation' => date('Y-m-d H:i:s')
        ];

        // Si c'est un code à usage unique (max_utilisations = 1)
        if (($codeData['utilisations_max'] ?? 0) == 1) {
            $updateData['est_utilise'] = 1;
        }

        $this->codeModel->update($codeData['id'], $updateData);

        $nouveauSolde = $this->walletModel->getSolde($userId);

        // Retourner une réponse JSON valide
        return $this->response
            ->setContentType('application/json')
            ->setBody(json_encode([
                'success' => true,
                'message' => $montant . ' € ajoutés à votre porte-monnaie !',
                'montant' => $montant,
                'nouveau_solde' => $nouveauSolde
            ]));
    }

    /**
     * Applique un code promo (version formulaire classique)
     */
    public function appliquerCodePost()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter');
        }

        $code = trim($this->request->getPost('code'));

        if (empty($code)) {
            return redirect()->back()->with('error', 'Veuillez entrer un code');
        }

        $userId = session()->get('user_id');

        // Vérifier le code dans la base
        $codeData = $this->codeModel->where('code', strtoupper($code))->first();

        if (!$codeData) {
            return redirect()->back()->with('error', 'Code promo invalide');
        }

        if ($codeData['est_actif'] == 0) {
            return redirect()->back()->with('error', 'Code promo désactivé');
        }

        if ($codeData['est_utilise'] == 1) {
            return redirect()->back()->with('error', 'Code déjà utilisé');
        }

        // Vérifier les utilisations max
        if ($codeData['utilisations_max'] !== null && $codeData['utilisations_actuelles'] >= $codeData['utilisations_max']) {
            return redirect()->back()->with('error', 'Code promo expiré');
        }

        // Vérifier date expiration
        if ($codeData['date_expiration'] && strtotime($codeData['date_expiration']) < time()) {
            return redirect()->back()->with('error', 'Code promo expiré');
        }

        $montant = (float) $codeData['valeur'];

        // Créditer le wallet
        $this->walletModel->crediter($userId, $montant, 'Code promo: ' . strtoupper($code));

        // Mettre à jour le code
        $updateData = [
            'utilisations_actuelles' => $codeData['utilisations_actuelles'] + 1,
            'id_utilisateur' => $userId,
            'date_utilisation' => date('Y-m-d H:i:s')
        ];

        if (($codeData['utilisations_max'] ?? 0) == 1) {
            $updateData['est_utilise'] = 1;
        }

        $this->codeModel->update($codeData['id'], $updateData);

        return redirect()->back()->with('success', $montant . ' € ajoutés à votre porte-monnaie !');
    }

    /**
     * Achète un régime
     * URL: POST /wallet/acheter-regime
     */
    public function acheterRegime()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter');
        }

        $regimeId = $this->request->getPost('regime_id');
        $dureeSemaines = (int) $this->request->getPost('duree_semaines');

        if (!$regimeId || !$dureeSemaines) {
            return redirect()->back()->with('error', 'Paramètres manquants');
        }

        $userId = session()->get('user_id');
        $isGold = session()->get('is_gold') ?? false;

        // Récupérer le régime
        $regime = $this->regimeModel->find($regimeId);

        if (!$regime) {
            return redirect()->back()->with('error', 'Régime non trouvé');
        }

        // Calculer le prix
        $prixJournalier = (float) ($regime['prix_journalier'] ?? 5.99);
        if ($isGold) {
            $prixJournalier = $prixJournalier * 0.85; // Réduction Gold 15%
        }

        $prixTotal = $prixJournalier * ($dureeSemaines * 7);

        // Vérifier le solde
        $solde = $this->walletModel->getSolde($userId);

        if ($solde < $prixTotal) {
            return redirect()->back()->with('error', 'Solde insuffisant. Solde actuel : ' . number_format($solde, 2) . ' €');
        }

        // Débiter le wallet
        $description = 'Achat régime "' . $regime['nom_regime'] . '" (' . $dureeSemaines . ' semaines)';
        if ($isGold) {
            $description .= ' (Prix Gold -15%)';
        }

        $success = $this->walletModel->debiter($userId, $prixTotal, $description);

        if (!$success) {
            return redirect()->back()->with('error', 'Erreur lors du paiement');
        }

        $nouveauSolde = $this->walletModel->getSolde($userId);

        // Rediriger vers la page du régime avec un message de succès
        return redirect()->to('/regimes/' . $regimeId)->with(
            'success',
            '✅ Achat effectué avec succès !<br>' .
                '📦 Régime: ' . $regime['nom_regime'] . '<br>' .
                '📅 Durée: ' . $dureeSemaines . ' semaines<br>' .
                '💰 Prix: ' . number_format($prixTotal, 2) . ' €<br>' .
                '💵 Solde restant: ' . number_format($nouveauSolde, 2) . ' €'
        );
    }

    /**
     * Ajoute de l'argent (simulation de paiement)
     * URL: POST /wallet/ajouter-argent
     */
    public function ajouterArgent()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter');
        }

        $montant = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide');
        }

        $userId = session()->get('user_id');
        $this->walletModel->crediter($userId, $montant, 'Ajout manuel');

        return redirect()->to('/wallet')->with('success', number_format($montant, 2) . ' € ajoutés à votre porte-monnaie');
    }
}
