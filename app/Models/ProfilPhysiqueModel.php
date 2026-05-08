<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilPhysiqueModel extends Model
{
    protected $table = 'profil_physique';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_utilisateur',
        'poids_kg',
        'taille_cm',
        'niveau_activite',
        'date_mesure'
    ];
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;
    
    // Dates
    protected $createdField = 'date_mesure';
    protected $updatedField = false;
    
    // Validation rules
    protected $validationRules = [
        'id_utilisateur' => 'required|integer|is_not_unique[utilisateur.id]',
        'poids_kg' => 'required|numeric|greater_than[10]|less_than[300]',
        'taille_cm' => 'required|numeric|greater_than[50]|less_than[250]',
        'niveau_activite' => 'required|in_list[sedentaire,leger,modere,actif,extreme]'
    ];
    
    protected $validationMessages = [
        'poids_kg' => [
            'required' => 'Le poids est obligatoire',
            'numeric' => 'Le poids doit être un nombre',
            'greater_than' => 'Le poids doit être supérieur à 10 kg',
            'less_than' => 'Le poids doit être inférieur à 300 kg'
        ],
        'taille_cm' => [
            'required' => 'La taille est obligatoire',
            'numeric' => 'La taille doit être un nombre',
            'greater_than' => 'La taille doit être supérieure à 50 cm',
            'less_than' => 'La taille doit être inférieure à 250 cm'
        ]
    ];
    
    /**
     * Récupère le dernier profil physique d'un utilisateur
     * @param int $userId
     * @return object|null
     */
    public function getLastProfilByUser(int $userId)
    {
        return $this->where('id_utilisateur', $userId)
                    ->orderBy('date_mesure', 'DESC')
                    ->first();
    }
    
    /**
     * Récupère tous les profils physiques d'un utilisateur (historique)
     * @param int $userId
     * @return array
     */
    public function getAllProfilsByUser(int $userId)
    {
        return $this->where('id_utilisateur', $userId)
                    ->orderBy('date_mesure', 'DESC')
                    ->findAll();
    }
    
    /**
     * Calcule l'IMC à partir du poids et taille
     * @param float $poidsKg
     * @param float $tailleCm
     * @return float
     */
    public function calculerIMC(float $poidsKg, float $tailleCm): float
    {
        if($tailleCm <= 0) return 0;
        $tailleM = $tailleCm / 100;
        return round($poidsKg / ($tailleM * $tailleM), 1);
    }
    
    /**
     * Interprète l'IMC
     * @param float $imc
     * @return string
     */
    public function interpreterIMC(float $imc): string
    {
        if($imc < 16.5) return 'Dénutrition sévère';
        if($imc < 18.5) return 'Maigreur';
        if($imc < 25) return 'Corpulence normale';
        if($imc < 30) return 'Surpoids';
        if($imc < 35) return 'Obésité modérée';
        if($imc < 40) return 'Obésité sévère';
        return 'Obésité massive';
    }
    
    /**
     * Calcule l'âge à partir d'une date de naissance
     * @param string $dateNaissance
     * @return int
     */
    public function calculerAge(string $dateNaissance): int
    {
        $naissance = new \DateTime($dateNaissance);
        $aujourdhui = new \DateTime();
        return $naissance->diff($aujourdhui)->y;
    }
    
    /**
     * Calcule le métabolisme de base (Harris & Benedict révisée)
     * @param string $genre 'homme' ou 'femme'
     * @param float $poidsKg
     * @param float $tailleCm
     * @param int $age
     * @return int
     */
    public function calculerMetabolismeBase(string $genre, float $poidsKg, float $tailleCm, int $age): int
    {
        if($genre === 'homme') {
            // Homme : 88,362 + (13,397 × poids) + (4,799 × taille) − (5,677 × age)
            $mb = 88.362 + (13.397 * $poidsKg) + (4.799 * $tailleCm) - (5.677 * $age);
        } else {
            // Femme : 447,593 + (9,247 × poids) + (3,098 × taille) − (4,330 × age)
            $mb = 447.593 + (9.247 * $poidsKg) + (3.098 * $tailleCm) - (4.330 * $age);
        }
        
        return (int) round($mb);
    }
    
    /**
     * Calcule le besoin énergétique total (BET) selon le niveau d'activité
     * @param int $metabolismeBase
     * @param string $niveauActivite
     * @return int
     */
    public function calculerBesoinMaintien(int $metabolismeBase, string $niveauActivite): int
    {
        $facteurs = [
            'sedentaire' => 1.2,      // Peu ou pas d'exercice
            'leger' => 1.375,         // Exercice léger 1-3j/semaine
            'modere' => 1.55,         // Exercice modéré 3-5j/semaine
            'actif' => 1.725,         // Exercice intense 6-7j/semaine
            'extreme' => 1.9          // Très intense + métier physique
        ];
        
        $facteur = $facteurs[$niveauActivite] ?? 1.2;
        return (int) round($metabolismeBase * $facteur);
    }
    
    /**
     * Calcule le besoin calorique selon l'objectif
     * @param int $besoinMaintien
     * @param string $typeObjectif 'augmenter_poids', 'reduire_poids', 'imc_ideal'
     * @param float|null $poidsCible
     * @param float|null $poidsActuel
     * @return int
     */
    public function calculerBesoinObjectif(int $besoinMaintien, string $typeObjectif, ?float $poidsCible = null, ?float $poidsActuel = null): int
    {
        switch($typeObjectif) {
            case 'reduire_poids':
                // Déficit calorique de 500 kcal/semaine = perte de 0.5kg/semaine
                return $besoinMaintien - 500;
            case 'augmenter_poids':
                // Surplus calorique de 500 kcal/semaine = gain de 0.5kg/semaine
                return $besoinMaintien + 500;
            case 'imc_ideal':
                // Calcul personnalisé basé sur l'IMC cible
                if($poidsCible && $poidsActuel && $poidsActuel > 0) {
                    $difference = $poidsCible - $poidsActuel;
                    if($difference > 0) {
                        // Besoin d'augmenter
                        return $besoinMaintien + 400;
                    } else {
                        // Besoin de réduire
                        return $besoinMaintien - 400;
                    }
                }
                return $besoinMaintien;
            default:
                return $besoinMaintien;
        }
    }
    
    /**
     * Calcule le poids idéal selon la formule de Lorentz
     * @param string $genre 'homme' ou 'femme'
     * @param float $tailleCm
     * @return float
     */
    public function calculerPoidsIdeal(string $genre, float $tailleCm): float
    {
        if($genre === 'homme') {
            // Homme : Taille - 100 - ((Taille - 150) / 4)
            return round($tailleCm - 100 - (($tailleCm - 150) / 4), 1);
        } else {
            // Femme : Taille - 100 - ((Taille - 150) / 2.5)
            return round($tailleCm - 100 - (($tailleCm - 150) / 2.5), 1);
        }
    }
    
    /**
     * Obtient le facteur d'activité en texte
     * @param string $niveau
     * @return string
     */
    public function getNiveauActiviteText(string $niveau): string
    {
        $textes = [
            'sedentaire' => 'Sédentaire (peu ou pas d\'exercice)',
            'leger' => 'Légèrement actif (1-3 jours/semaine)',
            'modere' => 'Modérément actif (3-5 jours/semaine)',
            'actif' => 'Très actif (6-7 jours/semaine)',
            'extreme' => 'Extrêmement actif (métier physique + sport)'
        ];
        
        return $textes[$niveau] ?? $niveau;
    }
}