<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\TransactionModel;

class WalletModel extends Model
{
    protected $table = 'wallet';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_utilisateur',
        'solde',
        'date_mise_a_jour'
    ];

    protected $useTimestamps = false;
    protected $useSoftDeletes = false;

    /**
     * Récupère le solde d'un utilisateur
     */
    public function getSolde(int $userId): float
    {
        $wallet = $this->where('id_utilisateur', $userId)->first();

        if (!$wallet) {
            $this->creerWallet($userId);
            return 0;
        }

        return (float) $wallet['solde'];
    }

    /**
     * Crée un wallet pour un utilisateur
     */
    public function creerWallet(int $userId): bool
    {
        if (!$this->where('id_utilisateur', $userId)->first()) {
            return $this->insert([
                'id_utilisateur' => $userId,
                'solde' => 0
            ]);
        }
        return true;
    }

    /**
     * Crédite le compte d'un utilisateur
     */
    public function crediter(int $userId, float $montant, string $description = ''): bool
    {
        $this->creerWallet($userId);

        $wallet = $this->where('id_utilisateur', $userId)->first();
        $nouveauSolde = $wallet['solde'] + $montant;

        $this->update($wallet['id'], ['solde' => $nouveauSolde]);

        // Enregistrer la transaction
        $transactionModel = new TransactionModel();
        $transactionModel->enregistrer($userId, $montant, 'credit', $description);

        return true;
    }

    /**
     * Débite le compte d'un utilisateur
     */
    public function debiter(int $userId, float $montant, string $description = ''): bool
    {
        $this->creerWallet($userId);

        $wallet = $this->where('id_utilisateur', $userId)->first();

        if (!$wallet || (float) $wallet['solde'] < $montant) {
            return false;
        }

        $nouveauSolde = (float) $wallet['solde'] - $montant;
        $this->update($wallet['id'], ['solde' => $nouveauSolde]);

        // Utiliser le modèle de transaction
        $transactionModel = new TransactionModel();
        $transactionModel->enregistrer($userId, $montant, 'debit', $description);

        return true;
    }
}
