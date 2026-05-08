<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjectifModel extends Model
{
    protected $table = 'objectif';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_utilisateur',
        'type_objectif',
        'poids_cible_kg',
        'date_debut',
        'duree_souhaitee_semaines',
        'status'
    ];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;
    
    // Validation rules
    protected $validationRules = [
        'id_utilisateur' => 'required|integer|is_not_unique[utilisateur.id]',
        'type_objectif' => 'required|in_list[augmenter_poids,reduire_poids,imc_ideal]',
        'poids_cible_kg' => 'permit_empty|numeric|greater_than[10]|less_than[300]',
        'date_debut' => 'required|valid_date',
        'duree_souhaitee_semaines' => 'permit_empty|numeric|greater_than[0]|less_than[52]',
        'status' => 'permit_empty|in_list[en_cours,atteint,abandonne]'
    ];
    
    protected $validationMessages = [
        'type_objectif' => [
            'required' => 'L\'objectif est obligatoire',
            'in_list' => 'L\'objectif sélectionné n\'est pas valide'
        ],
        'poids_cible_kg' => [
            'numeric' => 'Le poids cible doit être un nombre',
            'greater_than' => 'Le poids cible doit être supérieur à 10 kg',
            'less_than' => 'Le poids cible doit être inférieur à 300 kg'
        ]
    ];
    
    /**
     * Récupère l'objectif actif d'un utilisateur (en cours)
     * @param int $userId
     * @return object|null
     */
    public function getObjectifActif(int $userId)
    {
        return $this->where('id_utilisateur', $userId)
                    ->where('status', 'en_cours')
                    ->orderBy('date_debut', 'DESC')
                    ->first();
    }
    
    /**
     * Récupère tous les objectifs d'un utilisateur (historique)
     * @param int $userId
     * @return array
     */
    public function getAllObjectifsByUser(int $userId)
    {
        return $this->where('id_utilisateur', $userId)
                    ->orderBy('date_debut', 'DESC')
                    ->findAll();
    }
    
    /**
     * Récupère le dernier objectif (même terminé)
     * @param int $userId
     * @return object|null
     */
    public function getLastObjectif(int $userId)
    {
        return $this->where('id_utilisateur', $userId)
                    ->orderBy('date_debut', 'DESC')
                    ->first();
    }
    
    /**
     * Définit un nouvel objectif pour l'utilisateur
     * @param int $userId
     * @param string $type
     * @param float|null $poidsCible
     * @param int|null $dureeSemaines
     * @return bool|int
     */
    public function definirObjectif(int $userId, string $type, ?float $poidsCible = null, ?int $dureeSemaines = null)
    {
        // Désactiver l'ancien objectif
        $this->where('id_utilisateur', $userId)
             ->where('status', 'en_cours')
             ->set(['status' => 'abandonne'])
             ->update();
        
        // Créer le nouvel objectif
        return $this->insert([
            'id_utilisateur' => $userId,
            'type_objectif' => $type,
            'poids_cible_kg' => $poidsCible,
            'date_debut' => date('Y-m-d'),
            'duree_souhaitee_semaines' => $dureeSemaines,
            'status' => 'en_cours'
        ]);
    }
    
    /**
     * Marque un objectif comme atteint
     * @param int $objectifId
     * @return bool
     */
    public function marquerAtteint(int $objectifId): bool
    {
        return $this->update($objectifId, ['status' => 'atteint']);
    }
    
    /**
     * Abandonne un objectif
     * @param int $objectifId
     * @return bool
     */
    public function abandonner(int $objectifId): bool
    {
        return $this->update($objectifId, ['status' => 'abandonne']);
    }
    
    /**
     * Obtient le texte de l'objectif en français
     * @param string $type
     * @return string
     */
    public function getObjectifText(string $type): string
    {
        $textes = [
            'augmenter_poids' => 'Augmenter mon poids',
            'reduire_poids' => 'Réduire mon poids',
            'imc_ideal' => 'Atteindre mon IMC idéal'
        ];
        
        return $textes[$type] ?? $type;
    }
    
    /**
     * Obtient l'icône de l'objectif
     * @param string $type
     * @return string
     */
    public function getObjectifIcone(string $type): string
    {
        $icones = [
            'augmenter_poids' => 'fa-arrow-up',
            'reduire_poids' => 'fa-arrow-down',
            'imc_ideal' => 'fa-bullseye'
        ];
        
        return $icones[$type] ?? 'fa-flag-checkered';
    }
    
    /**
     * Calcule le temps écoulé depuis le début de l'objectif
     * @param string $dateDebut
     * @return int Nombre de jours
     */
    public function getTempsEcoule(string $dateDebut): int
    {
        $debut = new \DateTime($dateDebut);
        $aujourdhui = new \DateTime();
        return $debut->diff($aujourdhui)->days;
    }
    
    /**
     * Calcule le temps restant estimé en semaines
     * @param int|null $dureeSemaines
     * @param string $dateDebut
     * @return int|null
     */
    public function getTempsRestant(?int $dureeSemaines, string $dateDebut): ?int
    {
        if(!$dureeSemaines) return null;
        
        $debut = new \DateTime($dateDebut);
        $aujourdhui = new \DateTime();
        $semainesEcoulees = floor($debut->diff($aujourdhui)->days / 7);
        
        return max(0, $dureeSemaines - $semainesEcoulees);
    }
    
    /**
     * Calcule le pourcentage de progression estimé
     * @param int|null $dureeSemaines
     * @param string $dateDebut
     * @return int
     */
    public function getProgressionEstimee(?int $dureeSemaines, string $dateDebut): int
    {
        if(!$dureeSemaines || $dureeSemaines <= 0) return 0;
        
        $debut = new \DateTime($dateDebut);
        $aujourdhui = new \DateTime();
        $semainesEcoulees = floor($debut->diff($aujourdhui)->days / 7);
        
        return min(100, (int) round(($semainesEcoulees / $dureeSemaines) * 100));
    }
    
    /**
     * Vérifie si l'objectif est dépassé (date dépassée)
     * @param int|null $dureeSemaines
     * @param string $dateDebut
     * @return bool
     */
    public function isDepasse(?int $dureeSemaines, string $dateDebut): bool
    {
        if(!$dureeSemaines) return false;
        
        $debut = new \DateTime($dateDebut);
        $dateFin = (clone $debut)->modify("+{$dureeSemaines} weeks");
        $aujourdhui = new \DateTime();
        
        return $aujourdhui > $dateFin;
    }
}