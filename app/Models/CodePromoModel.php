<?php

namespace App\Models;

use CodeIgniter\Model;

class CodePromoModel extends Model
{
    protected $table = 'code_promo';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'code',
        'valeur',
        'type',
        'est_utilise',
        'est_actif',
        'utilisations_max',
        'utilisations_actuelles',
        'id_utilisateur',
        'date_expiration',
        'date_creation',
        'date_utilisation'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'date_creation';

    protected $validationRules = [
        'code' => 'required|min_length[3]|is_unique[code_promo.code,id,{id}]',
        'valeur' => 'required|numeric|greater_than[0]',
        'type' => 'required|in_list[percentage,fixed]',
        'est_actif' => 'permit_empty|in_list[0,1]'
    ];

    /**
     * Vérifie si un code est valide
     */
    public function isCodeValide(string $code): bool
    {
        $codeData = $this->where('code', strtoupper($code))->first();

        if (!$codeData) return false;

        // Vérifier si actif
        if (!$codeData['est_actif']) return false;

        // Vérifier si déjà utilisé (pour les codes à usage unique)
        if ($codeData['est_utilise']) return false;

        // Vérifier les utilisations max
        if ($codeData['utilisations_max'] !== null) {
            if ($codeData['utilisations_actuelles'] >= $codeData['utilisations_max']) {
                return false;
            }
        }

        // Vérifier la date d'expiration
        if ($codeData['date_expiration']) {
            if (strtotime($codeData['date_expiration']) < time()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Valide et applique un code promo
     */
    public function appliquerCode(string $code, int $userId, float $montant): array
    {
        $codeData = $this->where('code', strtoupper($code))->first();

        if (!$codeData) {
            return ['success' => false, 'message' => 'Code promo invalide'];
        }

        if (!$this->isCodeValide($code)) {
            return ['success' => false, 'message' => 'Code promo expiré ou déjà utilisé'];
        }

        // Calculer la réduction
        $reduction = 0;
        if ($codeData['type'] === 'percentage') {
            $reduction = $montant * ($codeData['valeur'] / 100);
        } else {
            $reduction = min($codeData['valeur'], $montant);
        }

        // Mettre à jour les utilisations
        $updateData = [
            'utilisations_actuelles' => $codeData['utilisations_actuelles'] + 1,
            'id_utilisateur' => $userId,
            'date_utilisation' => date('Y-m-d H:i:s')
        ];

        // Si c'est un code à usage unique, marquer comme utilisé
        if ($codeData['utilisations_max'] === 1) {
            $updateData['est_utilise'] = 1;
        }

        $this->update($codeData['id'], $updateData);

        return [
            'success' => true,
            'message' => 'Code appliqué avec succès',
            'reduction' => $reduction,
            'code_data' => $codeData
        ];
    }

    /**
     * Récupère les codes d'un utilisateur
     */
    public function getCodesByUser(int $userId): array
    {
        return $this->where('id_utilisateur', $userId)
            ->orderBy('date_utilisation', 'DESC')
            ->findAll();
    }

    /**
     * Récupère les codes actifs
     */
    public function getCodesActifs(): array
    {
        return $this->where('est_actif', 1)
            ->where('est_utilise', 0)
            ->orderBy('date_creation', 'DESC')
            ->findAll();
    }

    /**
     * Récupère les statistiques des codes
     */
    public function getStats(): array
    {
        return [
            'total' => $this->countAll(),
            'actifs' => $this->where('est_actif', 1)->countAllResults(),
            'utilises' => $this->where('est_utilise', 1)->countAllResults(),
            'en_cours' => $this->where('est_actif', 1)
                ->where('est_utilise', 0)
                ->countAllResults()
        ];
    }
}
