<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Choisir mon objectif' ?> - NutriGoal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #5D9B6E;
            --primary-dark: #3D7A4E;
            --primary-light: #B8D9C2;
            --radius-card: 28px;
            --radius-btn: 50px;
            --shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(145deg, #F9F6F0 0%, #EDE9E2 100%);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            line-height: 1.5;
        }

        /* Conteneur principal centré et plus aéré */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 24px 60px;
        }

        /* Navigation */
        .navbar {
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -1px;
        }
        .logo span {
            color: #E76F51;
        }

        /* Titres */
        h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .subtitle {
            text-align: center;
            color: #6B7A6F;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        /* Carte IMC */
        .card-imc {
            background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
            border-radius: var(--radius-card);
            padding: 1.8rem;
            text-align: center;
            margin-bottom: 2.5rem;
            box-shadow: var(--shadow);
        }
        .card-imc i {
            font-size: 2rem;
            color: #2E7D32;
        }

        /* Grille des objectifs (centrée et espacée) */
        .objectifs-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
            margin: 2rem 0 2.5rem;
        }
        .card-objectif {
            background: white;
            border-radius: var(--radius-card);
            padding: 2rem 1.5rem;
            flex: 1;
            min-width: 260px;
            max-width: 320px;
            text-align: center;
            transition: all 0.25s ease;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            cursor: pointer;
            border: 2px solid transparent;
        }
        .card-objectif:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 35px rgba(0,0,0,0.1);
        }
        .card-objectif.selected {
            border-color: var(--primary);
            background: #F4FBF7;
            box-shadow: 0 12px 28px rgba(93,155,110,0.2);
        }
        .card-objectif i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .card-objectif h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .card-objectif p {
            color: #6c757d;
            margin-bottom: 0;
        }

        /* Durée */
        .duree-wrapper {
            max-width: 320px;
            margin: 1rem auto 2rem;
            text-align: center;
        }
        .form-select-custom {
            border-radius: 50px;
            padding: 12px 20px;
            border: 1px solid #ddd;
            background: white;
            font-weight: 500;
        }

        /* Bouton valider */
        .btn-valider {
            background: linear-gradient(95deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: var(--radius-btn);
            padding: 14px 40px;
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .btn-valider:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .btn-valider:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(93,155,110,0.4);
        }

        /* Objectif en cours (carte spéciale) */
        .objectif-actuel-card {
            background: linear-gradient(115deg, var(--primary), #2D6A4F);
            color: white;
            border-radius: 28px;
            padding: 1.8rem;
            margin: 2.5rem 0 1.5rem;
            box-shadow: var(--shadow);
        }
        .objectif-actuel-card .badge {
            font-size: 0.85rem;
            padding: 0.4rem 1rem;
            border-radius: 40px;
        }

        /* Historique - groupe séparé */
        .historique-section {
            margin-top: 2rem;
        }
        .historique-item {
            background: white;
            border-radius: 20px;
            padding: 1.2rem 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: 0.15s;
            border-left: 6px solid var(--primary);
        }

        footer {
            background: #1E2A2E;
            color: #bbb;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 16px;
            }
            .card-objectif {
                min-width: 100%;
            }
            h1 {
                font-size: 1.9rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-inner">
        <div class="logo">Nutri<span>Goal</span></div>
        <div>
            <span class="me-3"><i class="fas fa-user-circle"></i> <?= session()->get('nom') ?? 'Invité' ?></span>
            <a href="/dashboard" class="btn btn-outline-success rounded-pill btn-sm"><i class="fas fa-chart-line"></i> Dashboard</a>
        </div>
    </div>
</nav>

<div class="main-container">
    <!-- Messages flash -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success rounded-pill text-center"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger rounded-pill text-center"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <h1>🎯 Choisissez votre objectif</h1>
    <div class="subtitle">Sélectionnez ce que vous souhaitez accomplir pour votre santé</div>

    <!-- Bloc IMC centré -->
    <?php if (isset($profil) && $profil): ?>
        <div class="card-imc">
            <i class="fas fa-chart-line"></i>
            <h4 class="mt-2">Votre IMC actuel : <strong><?= number_format($imc ?? 0, 1) ?></strong> (<?= $interpretationImc ?? 'Non défini' ?>)</h4>
            <?php if (isset($poids_ideal) && $poids_ideal): ?>
                <p class="mb-0 mt-1"><i class="fas fa-star"></i> Poids idéal suggéré : <strong><?= $poids_ideal ?> kg</strong></p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center rounded-4">⚠️ Complétez votre profil pour des recommandations personnalisées.</div>
    <?php endif; ?>

    <form action="/objectif/enregistrer" method="post" id="objectifForm">
        <?= csrf_field() ?>
        <!-- Grille des 3 objectifs : centrée, bien espacée -->
        <div class="objectifs-grid">
            <div class="card-objectif" data-objectif="reduire_poids" onclick="selectObjectif(this, 'reduire_poids')">
                <i class="fas fa-arrow-down" style="color: #dc3545;"></i>
                <h3>📉 Perdre du poids</h3>
                <p>Atteindre un poids plus bas</p>
            </div>
            <div class="card-objectif" data-objectif="augmenter_poids" onclick="selectObjectif(this, 'augmenter_poids')">
                <i class="fas fa-arrow-up" style="color: #28a745;"></i>
                <h3>📈 Prendre du poids</h3>
                <p>Gagner en masse musculaire</p>
            </div>
            <div class="card-objectif" data-objectif="imc_ideal" onclick="selectObjectif(this, 'imc_ideal')">
                <i class="fas fa-star" style="color: #ffc107;"></i>
                <h3>⭐ IMC idéal</h3>
                <p>Poids de forme automatique</p>
                <?php if (isset($poids_ideal) && $poids_ideal): ?>
                    <small class="text-muted"><?= $poids_ideal ?> kg</small>
                <?php endif; ?>
            </div>
        </div>

        <input type="hidden" name="type_objectif" id="type_objectif">
        <input type="hidden" name="poids_cible_kg" id="poids_cible_kg">
        <input type="hidden" name="duree_semaines" id="duree_semaines" value="8">

        <!-- Durée centrée -->
        <div class="duree-wrapper">
            <label class="form-label fw-bold"><i class="fas fa-calendar-week"></i> Durée souhaitée</label>
            <select id="duree_select" class="form-select-custom form-select" onchange="document.getElementById('duree_semaines').value = this.value">
                <option value="4">4 semaines (1 mois)</option>
                <option value="8" selected>8 semaines (2 mois)</option>
                <option value="12">12 semaines (3 mois)</option>
                <option value="16">16 semaines (4 mois)</option>
            </select>
        </div>

        <div class="text-center">
            <button type="submit" class="btn-valider" id="btnValider" disabled>
                <i class="fas fa-check-circle"></i> Valider mon objectif
            </button>
        </div>
    </form>

    <!-- Objectif en cours (groupe séparé, bien mis en avant) -->
    <?php if (isset($objectifActif) && $objectifActif): ?>
        <div class="objectif-actuel-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <i class="fas fa-bullseye fa-2x mb-2"></i>
                    <h4 class="mb-1">📌 Objectif en cours</h4>
                    <p class="mb-0">
                        <?php if ($objectifActif['type_objectif'] == 'reduire_poids'): ?>
                            📉 Perdre du poids → <strong><?= $objectifActif['poids_cible_kg'] ?> kg</strong>
                        <?php elseif ($objectifActif['type_objectif'] == 'augmenter_poids'): ?>
                            📈 Prendre du poids → <strong><?= $objectifActif['poids_cible_kg'] ?> kg</strong>
                        <?php else: ?>
                            ⭐ IMC idéal → <strong><?= $objectifActif['poids_cible_kg'] ?> kg</strong>
                        <?php endif; ?>
                    </p>
                    <small><i class="far fa-calendar-alt"></i> Débuté le <?= date('d/m/Y', strtotime($objectifActif['date_debut'])) ?></small>
                </div>
                <div class="mt-3 mt-sm-0">
                    <span class="badge bg-light text-dark me-2">En cours</span>
                    <a href="/objectif/terminer/<?= $objectifActif['id'] ?>" class="btn btn-light btn-sm rounded-pill" onclick="return confirm('Valider cet objectif comme atteint ?')">✔️ Atteint</a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Historique (groupe séparé) -->
    <?php if (isset($historique) && !empty($historique)): ?>
        <div class="historique-section">
            <h4 class="mb-3"><i class="fas fa-history"></i> Historique des objectifs</h4>
            <?php foreach ($historique as $obj): ?>
                <div class="historique-item">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <?php if ($obj['type_objectif'] == 'reduire_poids'): ?>
                                <i class="fas fa-arrow-down text-danger"></i> Perdre du poids → <strong><?= $obj['poids_cible_kg'] ?> kg</strong>
                            <?php elseif ($obj['type_objectif'] == 'augmenter_poids'): ?>
                                <i class="fas fa-arrow-up text-success"></i> Prendre du poids → <strong><?= $obj['poids_cible_kg'] ?> kg</strong>
                            <?php else: ?>
                                <i class="fas fa-star text-warning"></i> IMC idéal → <strong><?= $obj['poids_cible_kg'] ?> kg</strong>
                            <?php endif; ?>
                            <br><small class="text-muted">Débuté le <?= date('d/m/Y', strtotime($obj['date_debut'])) ?></small>
                        </div>
                        <div>
                            <?php if ($obj['status'] == 'atteint'): ?>
                                <span class="badge bg-info">Atteint ✓</span>
                            <?php elseif ($obj['status'] == 'abandonne'): ?>
                                <span class="badge bg-secondary">Abandonné</span>
                            <?php else: ?>
                                <span class="badge bg-success">En cours</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<footer>
    <p>© 2026 NutriGoal — Votre parcours vers la santé</p>
</footer>

<script>
    let selectedCard = null;
    let poidsActuel = <?= isset($profil['poids_kg']) ? $profil['poids_kg'] : 70 ?>;
    let poidsIdeal = <?= isset($poids_ideal) ? $poids_ideal : 65 ?>;

    function selectObjectif(card, objectif) {
        if (selectedCard) selectedCard.classList.remove('selected');
        card.classList.add('selected');
        selectedCard = card;
        document.getElementById('type_objectif').value = objectif;
        let btn = document.getElementById('btnValider');
        btn.disabled = false;

        if (objectif === 'reduire_poids') {
            let cible = prompt("Entrez votre poids cible (kg) :", poidsActuel - 5);
            if (cible && cible > 20 && cible < 300) {
                document.getElementById('poids_cible_kg').value = cible;
            } else { resetSelection(card); }
        } 
        else if (objectif === 'augmenter_poids') {
            let cible = prompt("Entrez votre poids cible (kg) :", poidsActuel + 5);
            if (cible && cible > 20 && cible < 300) {
                document.getElementById('poids_cible_kg').value = cible;
            } else { resetSelection(card); }
        }
        else if (objectif === 'imc_ideal') {
            if (confirm(`Atteindre votre poids idéal (${poidsIdeal} kg) ?`)) {
                document.getElementById('poids_cible_kg').value = poidsIdeal;
            } else {
                resetSelection(card);
            }
        }
    }
    function resetSelection(card) {
        card.classList.remove('selected');
        selectedCard = null;
        document.getElementById('type_objectif').value = '';
        document.getElementById('btnValider').disabled = true;
        alert("Saisie annulée ou invalide");
    }
</script>
</body>
</html>