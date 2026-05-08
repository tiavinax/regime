<?php

namespace App\Models;

use CodeIgniter\Model;

class BesoinCaloriqueModel extends Model
{
    protected $table = 'besoin_calorique';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_objectif',
        'id_profil',
        'metabolisme_base_kcal',
        'besoin_maintien_kcal',
        'besoin_objectif_kcal',
        'date_calcul'
    ];

    protected $useTimestamps = false;
    
    // Désactiver les dates automatiques
    protected $createdField = '';
    protected $updatedField = '';
    protected $deletedField = '';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Récupère le dernier calcul calorique pour un objectif donné
     * @param int $id_objectif ID de l'objectif
     * @return array|null
     */
    public function getDernierCalcul($id_objectif)
    {
        return $this->where('id_objectif', $id_objectif)
                    ->orderBy('date_calcul', 'DESC')
                    ->first();
    }

    /**
     * Récupère tous les calculs caloriques pour un objectif
     * @param int $id_objectif ID de l'objectif
     * @return array
     */
    public function getHistoriqueParObjectif($id_objectif)
    {
        return $this->where('id_objectif', $id_objectif)
                    ->orderBy('date_calcul', 'DESC')
                    ->findAll();
    }

    /**
     * Récupère l'historique complet des calculs caloriques d'un utilisateur
     * @param int $id_utilisateur ID de l'utilisateur
     * @param int $limit Nombre maximum de résultats
     * @return array
     */
    public function getHistoriqueUtilisateur($id_utilisateur, $limit = 10)
    {
        return $this->db->table('besoin_calorique bc')
            ->select('bc.*, o.type_objectif, o.poids_cible_kg, pp.poids_kg, pp.taille_cm')
            ->join('objectif o', 'o.id = bc.id_objectif')
            ->join('profil_physique pp', 'pp.id = bc.id_profil')
            ->join('utilisateur u', 'u.id = o.id_utilisateur')
            ->where('u.id', $id_utilisateur)
            ->orderBy('bc.date_calcul', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Récupère le dernier calcul calorique d'un utilisateur
     * @param int $id_utilisateur ID de l'utilisateur
     * @return array|null
     */
    public function getDernierCalculUtilisateur($id_utilisateur)
    {
        $result = $this->db->table('besoin_calorique bc')
            ->select('bc.*, o.type_objectif, o.poids_cible_kg, pp.poids_kg, pp.taille_cm, pp.niveau_activite')
            ->join('objectif o', 'o.id = bc.id_objectif')
            ->join('profil_physique pp', 'pp.id = bc.id_profil')
            ->join('utilisateur u', 'u.id = o.id_utilisateur')
            ->where('u.id', $id_utilisateur)
            ->orderBy('bc.date_calcul', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
        
        return $result;
    }

    /**
     * Enregistre un nouveau calcul calorique
     * @param int $id_objectif ID de l'objectif
     * @param int $id_profil ID du profil physique
     * @param int $metabolisme_base Métabolisme de base en kcal
     * @param int $besoin_maintien Besoin de maintien en kcal
     * @param int $besoin_objectif Besoin objectif en kcal
     * @return bool|int
     */
    public function enregistrerCalcul($id_objectif, $id_profil, $metabolisme_base, $besoin_maintien, $besoin_objectif)
    {
        $data = [
            'id_objectif' => $id_objectif,
            'id_profil' => $id_profil,
            'metabolisme_base_kcal' => $metabolisme_base,
            'besoin_maintien_kcal' => $besoin_maintien,
            'besoin_objectif_kcal' => $besoin_objectif,
            'date_calcul' => date('Y-m-d H:i:s')
        ];
        
        return $this->insert($data);
    }

    /**
     * Calcule et enregistre automatiquement les besoins caloriques
     * @param int $id_objectif ID de l'objectif
     * @param int $id_profil ID du profil physique
     * @param float $poids_kg Poids en kg
     * @param float $taille_cm Taille en cm
     * @param int $age Âge en années
     * @param string $genre Genre (homme/femme)
     * @param string $niveau_activite Niveau d'activité
     * @param string $type_objectif Type d'objectif
     * @param float|null $poids_cible Poids cible
     * @return bool|int
     */
    public function calculerEtEnregistrer($id_objectif, $id_profil, $poids_kg, $taille_cm, $age, $genre, $niveau_activite, $type_objectif, $poids_cible = null)
    {
        // Calcul du métabolisme de base (Harris-Benedict)
        if ($genre == 'homme') {
            $metabolisme_base = round(13.707 * $poids_kg + 492.3 * ($taille_cm / 100) - 6.673 * $age + 77.607);
        } else {
            $metabolisme_base = round(9.740 * $poids_kg + 172.9 * ($taille_cm / 100) - 4.737 * $age + 667.051);
        }
        
        // Facteurs d'activité
        $facteurs = [
            'sedentaire' => 1.2,
            'leger' => 1.375,
            'modere' => 1.55,
            'actif' => 1.725,
            'extreme' => 1.9
        ];
        $facteur = isset($facteurs[$niveau_activite]) ? $facteurs[$niveau_activite] : 1.2;
        $besoin_maintien = round($metabolisme_base * $facteur);
        
        // Calcul du besoin selon l'objectif
        switch ($type_objectif) {
            case 'reduire_poids':
                $besoin_objectif = max(1200, $besoin_maintien - 500);
                break;
            case 'augmenter_poids':
                $besoin_objectif = $besoin_maintien + 500;
                break;
            case 'imc_ideal':
                if ($poids_cible && $poids_cible > $poids_kg) {
                    $besoin_objectif = $besoin_maintien + 400;
                } elseif ($poids_cible && $poids_cible < $poids_kg) {
                    $besoin_objectif = max(1200, $besoin_maintien - 400);
                } else {
                    $besoin_objectif = $besoin_maintien;
                }
                break;
            default:
                $besoin_objectif = $besoin_maintien;
        }
        
        // Enregistrement
        return $this->enregistrerCalcul($id_objectif, $id_profil, $metabolisme_base, $besoin_maintien, $besoin_objectif);
    }
}