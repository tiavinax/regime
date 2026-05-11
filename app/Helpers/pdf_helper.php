<?php

use App\Models\UserModel;
use App\Models\ProfilPhysiqueModel;
use App\Models\ObjectifModel;
use App\Models\RegimeModel;
use App\Models\ActiviteSportiveModel;

if (!function_exists('genererPDFSuggestion')) {
    function genererPDFSuggestion($userId)
    {
        // Inclure FPDF
        require_once APPPATH . 'ThirdParty/fpdf/fpdf.php';
        
        // Récupérer les données
        $userModel = new UserModel();
        $profilModel = new ProfilPhysiqueModel();
        $objectifModel = new ObjectifModel();
        $regimeModel = new RegimeModel();
        $activiteModel = new ActiviteSportiveModel();
        $db = \Config\Database::connect();
        
        $user = $userModel->find($userId);
        $profil = $profilModel->getLastProfilByUser($userId);
        $objectif = $objectifModel->getObjectifActif($userId);
        
        // Récupérer la dernière suggestion
        $suggestion = $db->table('suggestion')
            ->select('suggestion.*, regime.nom_regime, regime.description as regime_desc, regime.prix_journalier, activite_sportive.nom_activite, activite_sportive.description as activite_desc')
            ->join('besoin_calorique', 'besoin_calorique.id = suggestion.id_besoin')
            ->join('regime', 'regime.id = suggestion.id_regime')
            ->join('activite_sportive', 'activite_sportive.id = suggestion.id_activite')
            ->where('besoin_calorique.id_objectif', $objectif['id'])
            ->orderBy('suggestion.date_suggestion', 'DESC')
            ->get()
            ->getRowArray();
        
        // Créer le PDF
        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        
        // En-tête
        $pdf->SetFillColor(93, 155, 110);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 15, 'NUTRI GOAL - VOTRE PROGRAMME PERSONNALISE', 0, 1, 'C', true);
        $pdf->Ln(10);
        
        // Informations utilisateur
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, 'Informations utilisateur', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(50, 8, 'Nom:', 0, 0);
        $pdf->Cell(0, 8, $user['nom'], 0, 1);
        $pdf->Cell(50, 8, 'Email:', 0, 0);
        $pdf->Cell(0, 8, $user['email'], 0, 1);
        $pdf->Cell(50, 8, 'Genre:', 0, 0);
        $pdf->Cell(0, 8, $user['genre'], 0, 1);
        
        // Mensurations
        $imc = $profilModel->calculerIMC($profil['poids_kg'], $profil['taille_cm']);
        $interpretation = $profilModel->interpreterIMC($imc);
        
        $pdf->Cell(50, 8, 'Taille:', 0, 0);
        $pdf->Cell(0, 8, $profil['taille_cm'] . ' cm', 0, 1);
        $pdf->Cell(50, 8, 'Poids:', 0, 0);
        $pdf->Cell(0, 8, $profil['poids_kg'] . ' kg', 0, 1);
        $pdf->Cell(50, 8, 'IMC:', 0, 0);
        $pdf->Cell(0, 8, $imc . ' (' . $interpretation . ')', 0, 1);
        $pdf->Ln(5);
        
        // Objectif
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Votre objectif', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 11);
        
        $objectifText = [
            'augmenter_poids' => 'Augmenter mon poids',
            'reduire_poids' => 'Reduire mon poids',
            'imc_ideal' => 'Atteindre mon IMC ideal'
        ];
        $pdf->Cell(0, 8, 'Objectif: ' . ($objectifText[$objectif['type_objectif']] ?? $objectif['type_objectif']), 0, 1);
        if ($objectif['duree_souhaitee_semaines']) {
            $pdf->Cell(0, 8, 'Duree souhaitee: ' . $objectif['duree_souhaitee_semaines'] . ' semaines', 0, 1);
        }
        $pdf->Ln(5);
        
        // Suggestion
        if ($suggestion) {
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->SetFillColor(244, 162, 97);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(0, 10, 'VOTRE PROGRAMME PERSONNALISE', 0, 1, 'C', true);
            $pdf->Ln(5);
            
            // Régime
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(93, 155, 110);
            $pdf->Cell(0, 8, 'Regime recommande: ' . $suggestion['nom_regime'], 0, 1);
            $pdf->SetFont('Arial', '', 11);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->MultiCell(0, 6, $suggestion['regime_desc'], 0, 1);
            $pdf->Cell(0, 8, 'Prix journalier: ' . number_format($suggestion['prix_journalier'], 2) . ' €', 0, 1);
            $pdf->Ln(4);
            
            // Activité
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(93, 155, 110);
            $pdf->Cell(0, 8, 'Activite sportive recommande: ' . $suggestion['nom_activite'], 0, 1);
            $pdf->SetFont('Arial', '', 11);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->MultiCell(0, 6, $suggestion['activite_desc'], 0, 1);
            $pdf->Ln(4);
            
            // Durée
            $pdf->Cell(0, 8, 'Duree recommandee: ' . $suggestion['duree_recommandee_semaines'] . ' semaines', 0, 1);
            $pdf->Ln(5);
            
            // Message personnalisé
            $pdf->SetFont('Arial', 'I', 11);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->MultiCell(0, 6, $suggestion['message_personnalise'], 0, 1);
        }
        
        // Pied de page
        $pdf->SetY(-20);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 10, 'Document genere le ' . date('d/m/Y à H:i'), 0, 0, 'L');
        $pdf->Cell(0, 10, 'NutriGoal - Votre objectif poids, notre expertise', 0, 0, 'R');
        
        // Générer le fichier
        $filename = 'suggestion_' . $user['nom'] . '_' . date('Ymd_His') . '.pdf';
        $pdf->Output('D', $filename);
        exit();
    }
}