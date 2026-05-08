<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Tableau de bord' ?> - NutriGoal</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F9F6F0;
            color: #1E2A2E;
            line-height: 1.5;
        }

        :root {
            --primary: #5D9B6E;
            --primary-dark: #3D7A4E;
            --primary-light: #B8D9C2;
            --secondary: #F4A261;
            --secondary-dark: #E76F51;
            --gray-light: #EDE9E2;
            --gray-mid: #CBC3B5;
            --text-dark: #1E2A2E;
            --text-muted: #6B7A6F;
            --white: #FFFFFF;
            --shadow-sm: 0 8px 20px rgba(0,0,0,0.05);
            --shadow-md: 0 12px 28px rgba(0,0,0,0.08);
            --radius-card: 28px;
            --radius-btn: 40px;
        }

        h1, h2, h3, h4, .logo, .nav-links a, .btn {
            font-family: 'Poppins', sans-serif;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
        }

        /* ========== HEADER / MENU HORIZONTAL ========== */
        .navbar {
            background: var(--white);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(2px);
            border-bottom: 1px solid var(--gray-light);
        }
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 32px;
            max-width: 1400px;
            margin: 0 auto;
        }
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: -0.5px;
        }
        .logo span {
            color: var(--secondary-dark);
            font-weight: 800;
        }
        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        .nav-links a {
            text-decoration: none;
            font-weight: 500;
            color: var(--text-dark);
            transition: 0.2s;
        }
        .nav-links a:hover {
            color: var(--primary);
        }
        .nav-buttons {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--primary);
            color: var(--primary);
            padding: 8px 20px;
            border-radius: var(--radius-btn);
            font-weight: 600;
            transition: 0.2s;
            cursor: pointer;
        }
        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }
        .btn-primary {
            background: var(--primary);
            border: none;
            color: white;
            padding: 8px 24px;
            border-radius: var(--radius-btn);
            font-weight: 600;
            transition: 0.2s;
            cursor: pointer;
            box-shadow: var(--shadow-sm);
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* ========== DASHBOARD CARDS ========== */
        .dashboard-header {
            background: linear-gradient(135deg, #F9F6F0 0%, #E9F0E6 100%);
            padding: 40px 0 20px;
            margin-bottom: 40px;
            border-radius: 0 0 30px 30px;
        }
        .welcome-badge {
            background: var(--primary);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            display: inline-block;
        }
        .stat-card {
            background: var(--white);
            border-radius: var(--radius-card);
            padding: 28px;
            box-shadow: var(--shadow-sm);
            transition: all 0.25s ease;
            border: 1px solid var(--gray-light);
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
        }
        .stat-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 8px;
        }
        .imc-normal { color: #28a745; }
        .imc-warning { color: #ffc107; }
        .imc-danger { color: #dc3545; }
        .progress-custom {
            height: 10px;
            border-radius: 10px;
            margin-top: 12px;
        }
        .badge-objectif {
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-augmenter { background: #d4edda; color: #155724; }
        .badge-reduire { background: #fff3cd; color: #856404; }
        .badge-imc { background: #d1ecf1; color: #0c5460; }
        .info-box {
            background: var(--primary-light);
            border-radius: 20px;
            padding: 20px;
            margin-top: 30px;
        }
        footer {
            background: #1E2A2E;
            color: #CBC3B5;
            padding: 40px 0;
            text-align: center;
            margin-top: 60px;
        }
        @media (max-width: 800px) {
            .nav-container {
                flex-direction: column;
                gap: 16px;
            }
        }
    </style>
</head>
<body>

<!-- MENU HORIZONTAL -->
<div class="navbar">
    <div class="nav-container">
        <div class="logo">Nutri<span>Goal</span></div>
        <div class="nav-links">
            <a href="/dashboard">Accueil</a>
            <a href="/regimes">Régimes</a>
            <a href="/sports">Sports</a>
            <a href="/profil">Mon profil</a>
        </div>
        <div class="nav-buttons">
            <span style="margin-right: 10px;"><i class="fas fa-user-circle"></i> <?= esc($nom ?? 'Utilisateur') ?></span>
            <button class="btn-outline" onclick="window.location.href='/login '"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
        </div>
    </div>
</div>

<!-- EN-TÊTE DASHBOARD -->
<div class="dashboard-header">
    <div class="container">
        <div class="welcome-badge">
            <i class="fas fa-chart-line me-2"></i> Tableau de bord
        </div>
        <h1 style="margin-top: 20px;">Bonjour, <?= esc($nom ?? 'Invité') ?> 👋</h1>
        <p class="text-muted">Voici votre état de forme et vos objectifs personnalisés</p>
    </div>
</div>

<div class="container">
    
    <!-- ALERTE SI PAS DE PROFIL -->
    <?php if (!$profil): ?>
        <div class="stat-card" style="text-align: center; background: #fff3cd; border-color: #ffc107;">
            <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #856404;"></i>
            <h3 style="margin-top: 10px;">Profil incomplet</h3>
            <p>Vous n'avez pas encore complété vos données de santé.</p>
            <button class="btn-primary" onclick="window.location.href='/profil/completer'">Compléter mon profil →</button>
        </div>
    <?php endif; ?>

    <!-- ROW 1 : IMC + OBJECTIF -->
    <div style="display: flex; flex-wrap: wrap; gap: 32px; margin-bottom: 40px;">
        
        <!-- CARTE IMC -->
        <div style="flex: 1; min-width: 280px;">
            <div class="stat-card">
                <h3><i class="fas fa-calculator" style="color: var(--primary);"></i> Indice de Masse Corporelle</h3>
                <?php if ($profil): ?>
                    <div class="stat-value <?= 
                        $imc < 18.5 ? 'imc-warning' : 
                        ($imc < 25 ? 'imc-normal' : 'imc-danger') 
                    ?>" style="font-size: 3rem;">
                        <?= number_format($imc, 1) ?>
                    </div>
                    <div class="stat-label">IMC</div>
                    <h4 style="margin: 15px 0;"><?= $interpretationImc ?></h4>
                    <hr>
                    <div style="display: flex; justify-content: space-between;">
                        <div>
                            <small class="text-muted">Poids actuel</small>
                            <h4><?= $profil['poids_kg'] ?> kg</h4>
                        </div>
                        <div>
                            <small class="text-muted">Taille</small>
                            <h4><?= $profil['taille_cm'] ?> cm</h4>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-muted" style="margin-top: 20px;">Connectez-vous pour voir vos données</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- CARTE OBJECTIF ACTIF -->
        <div style="flex: 1; min-width: 280px;">
            <div class="stat-card">
                <h3><i class="fas fa-bullseye" style="color: var(--primary);"></i> Objectif actuel</h3>
                <?php if ($objectifActif): ?>
                    <div style="margin: 20px 0;">
                        <span class="badge-objectif <?= 
                            $objectifActif['type_objectif'] == 'augmenter_poids' ? 'badge-augmenter' : 
                            ($objectifActif['type_objectif'] == 'reduire_poids' ? 'badge-reduire' : 'badge-imc')
                        ?>">
                            <i class="fas fa-<?= 
                                $objectifActif['type_objectif'] == 'augmenter_poids' ? 'arrow-up' : 
                                ($objectifActif['type_objectif'] == 'reduire_poids' ? 'arrow-down' : 'star')
                            ?> me-1"></i>
                            <?= $objectifActif['type_objectif'] == 'augmenter_poids' ? 'Prendre du poids' : 
                               ($objectifActif['type_objectif'] == 'reduire_poids' ? 'Perdre du poids' : 'Atteindre IMC idéal') ?>
                        </span>
                    </div>
                    
                    <?php if ($objectifActif['poids_cible_kg']): ?>
                        <h4>🎯 Objectif : <strong><?= $objectifActif['poids_cible_kg'] ?> kg</strong></h4>
                    <?php endif; ?>
                    
                    <?php if ($poidsIdeal && $objectifActif['type_objectif'] == 'imc_ideal'): ?>
                        <p class="text-muted">Poids idéal suggéré : <strong><?= $poidsIdeal ?> kg</strong></p>
                    <?php endif; ?>
                    
                    <?php if ($progression !== null): ?>
                        <div style="margin-top: 25px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <span>Progression</span>
                                <span><strong><?= min(100, $progression) ?>%</strong></span>
                            </div>
                            <div class="progress-custom" style="background: #e9ecef;">
                                <div style="width: <?= min(100, $progression) ?>%; height: 100%; background: var(--primary); border-radius: 10px;"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div style="text-align: center; margin: 30px 0;">
                        <i class="fas fa-flag-checkered" style="font-size: 3rem; color: var(--gray-mid);"></i>
                        <p class="text-muted" style="margin: 15px 0;">Aucun objectif actif</p>
                        <button class="btn-primary" onclick="window.location.href='/objectif/choisir'">Définir un objectif</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ROW 2 : BESOINS CALORIQUES -->
    <div style="display: flex; flex-wrap: wrap; gap: 32px; margin-bottom: 40px;">
        
        <div style="flex: 1; min-width: 200px;">
            <div class="stat-card" style="text-align: center;">
                <i class="fas fa-bed" style="font-size: 2rem; color: var(--primary);"></i>
                <div class="stat-value" style="font-size: 1.8rem;"><?= number_format($metabolismeBase, 0) ?></div>
                <div class="stat-label">Métabolisme de base (kcal/jour)</div>
                <small class="text-muted">📌 Au repos</small>
            </div>
        </div>
        
        <div style="flex: 1; min-width: 200px;">
            <div class="stat-card" style="text-align: center;">
                <i class="fas fa-running" style="font-size: 2rem; color: var(--primary);"></i>
                <div class="stat-value" style="font-size: 1.8rem;"><?= number_format($besoinMaintien, 0) ?></div>
                <div class="stat-label">Besoin de maintien (kcal/jour)</div>
                <?php if ($profil): ?>
                    <small class="text-muted">🏃 Niveau : <?= ucfirst($profil['niveau_activite']) ?></small>
                <?php endif; ?>
            </div>
        </div>
        
        <div style="flex: 1; min-width: 200px;">
            <div class="stat-card" style="text-align: center; <?= $besoinObjectif ? 'border: 2px solid var(--primary);' : '' ?>">
                <i class="fas fa-chart-line" style="font-size: 2rem; color: var(--primary);"></i>
                <div class="stat-value" style="font-size: 1.8rem;"><?= $besoinObjectif ? number_format($besoinObjectif, 0) : '—' ?></div>
                <div class="stat-label">Besoin objectif (kcal/jour)</div>
                <?php if ($besoinObjectif && $besoinMaintien): ?>
                    <small class="text-muted">
                        <?php if ($besoinObjectif > $besoinMaintien): ?>
                            📈 +<?= $besoinObjectif - $besoinMaintien ?> kcal
                        <?php elseif ($besoinObjectif < $besoinMaintien): ?>
                            📉 <?= $besoinObjectif - $besoinMaintien ?> kcal
                        <?php else: ?>
                            ⚖️ Maintien
                        <?php endif; ?>
                    </small>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- DERNIER CALCUL + CONSEIL -->
    <?php if ($dernierCalcul): ?>
    <div class="info-box">
        <i class="fas fa-history"></i> Dernier calcul calorique : <strong><?= date('d/m/Y H:i', strtotime($dernierCalcul['date_calcul'])) ?></strong>
    </div>
    <?php endif; ?>

    <div class="info-box" style="background: #E9F0E6;">
        <i class="fas fa-lightbulb" style="color: var(--secondary-dark);"></i>
        <strong>Conseil personnalisé :</strong>
        <?php if ($besoinObjectif && $besoinMaintien && $besoinObjectif > $besoinMaintien): ?>
            Augmentez votre apport de <strong><?= $besoinObjectif - $besoinMaintien ?> kcal</strong> par jour pour atteindre votre objectif.
        <?php elseif ($besoinObjectif && $besoinMaintien && $besoinObjectif < $besoinMaintien): ?>
            Réduisez votre apport de <strong><?= $besoinMaintien - $besoinObjectif ?> kcal</strong> par jour pour atteindre votre objectif.
        <?php else: ?>
            Complétez votre profil et définissez un objectif pour des recommandations personnalisées.
        <?php endif; ?>
    </div>

</div>

<footer>
    <div class="container">
        <p>© 2026 NutriGoal — Application régime & IMC</p>
        <p><i class="fas fa-code"></i> Suivez vos progrès, atteignez vos objectifs</p>
    </div>
</footer>

</body>
</html>