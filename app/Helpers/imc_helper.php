<?php

// Calcule l'IMC
function calculer_imc($poids_kg, $taille_cm)
{
    if ($taille_cm <= 0 || $poids_kg <= 0) {
        return 0;
    }
    $taille_m = $taille_cm / 100;
    return round($poids_kg / ($taille_m * $taille_m), 1);
}

// Interprète l'IMC
function interpreter_imc($imc)
{
    if ($imc < 16.5) return 'Dénutrition';
    if ($imc < 18.5) return 'Maigreur';
    if ($imc < 25) return 'Poids normal';
    if ($imc < 30) return 'Surpoids';
    if ($imc < 35) return 'Obésité modérée';
    if ($imc < 40) return 'Obésité sévère';
    return 'Obésité morbide';
}

// Calcule le métabolisme de base (Harris-Benedict)
function calculer_metabolisme_base($poids_kg, $taille_cm, $age, $genre)
{
    if ($genre == 'homme') {
        return round(13.707 * $poids_kg + 492.3 * ($taille_cm / 100) - 6.673 * $age + 77.607);
    } else {
        return round(9.740 * $poids_kg + 172.9 * ($taille_cm / 100) - 4.737 * $age + 667.051);
    }
}

// Calcule le besoin de maintien selon le niveau d'activité
function calculer_besoin_maintien($metabolisme_base, $niveau_activite)
{
    $facteurs = [
        'sedentaire' => 1.2,
        'leger' => 1.375,
        'modere' => 1.55,
        'actif' => 1.725,
        'extreme' => 1.9
    ];
    
    $facteur = isset($facteurs[$niveau_activite]) ? $facteurs[$niveau_activite] : 1.2;
    return round($metabolisme_base * $facteur);
}

// Calcule le besoin selon l'objectif
function calculer_besoin_objectif($besoin_maintien, $type_objectif, $poids_cible = null, $poids_actuel = null)
{
    switch ($type_objectif) {
        case 'reduire_poids':
            return max(1200, $besoin_maintien - 500);
        case 'augmenter_poids':
            return $besoin_maintien + 500;
        case 'imc_ideal':
            if ($poids_cible && $poids_actuel) {
                if ($poids_cible > $poids_actuel) {
                    return $besoin_maintien + 400;
                } else {
                    return max(1200, $besoin_maintien - 400);
                }
            }
            return $besoin_maintien;
        default:
            return $besoin_maintien;
    }
}

// Calcule le poids idéal (IMC 21.5)
function calculer_poids_ideal($taille_cm)
{
    $taille_m = $taille_cm / 100;
    return round(21.5 * ($taille_m * $taille_m), 1);
}