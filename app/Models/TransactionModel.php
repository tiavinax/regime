<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transaction';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_utilisateur',
        'montant',
        'type',
        'description',
        'date_transaction'
    ];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;
    
    /**
     * Enregistre une transaction
     */
    public function enregistrer(int $userId, float $montant, string $type, string $description = ''): bool
    {
        return $this->insert([
            'id_utilisateur' => $userId,
            'montant' => $montant,
            'type' => $type,
            'description' => $description,
            'date_transaction' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Récupère l'historique des transactions d'un utilisateur
     */
    public function getHistorique(int $userId, int $limit = 10): array
    {
        return $this->where('id_utilisateur', $userId)
                    ->orderBy('date_transaction', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}