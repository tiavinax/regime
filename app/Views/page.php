<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriGoal - Design | Application régime & objectifs</title>
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

        /* Couleurs principales */
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
        .btn-secondary {
            background: var(--secondary);
            border: none;
            color: var(--text-dark);
            padding: 12px 32px;
            border-radius: var(--radius-btn);
            font-weight: 700;
            transition: 0.2s;
            cursor: pointer;
        }
        .btn-large {
            padding: 14px 36px;
            font-size: 1rem;
        }

        /* ========== HERO SECTION (page accueil) ========== */
        .hero {
            padding: 80px 0 60px;
            background: linear-gradient(135deg, #F9F6F0 0%, #E9F0E6 100%);
        }
        .hero-grid {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 48px;
            flex-wrap: wrap;
        }
        .hero-content {
            flex: 1;
        }
        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
            color: var(--text-dark);
        }
        .hero-content p {
            font-size: 1.2rem;
            color: var(--text-muted);
            margin-bottom: 32px;
        }
        .hero-stats {
            display: flex;
            gap: 32px;
            margin-top: 24px;
        }
        .stat {
            font-weight: 700;
        }
        .stat-number {
            font-size: 1.8rem;
            color: var(--primary);
        }
        .hero-image {
            flex: 1;
            background: var(--primary-light);
            border-radius: var(--radius-card);
            padding: 40px;
            text-align: center;
            font-size: 5rem;
            box-shadow: var(--shadow-md);
        }

        /* ========== SECTION FONCTIONNALITÉS (Gestalt) ========== */
        .section {
            padding: 60px 0;
        }
        .section-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 16px;
            text-align: center;
        }
        .section-sub {
            text-align: center;
            color: var(--text-muted);
            margin-bottom: 48px;
        }
        .cards-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            justify-content: center;
        }
        .card {
            background: var(--white);
            border-radius: var(--radius-card);
            padding: 28px;
            flex: 1;
            min-width: 260px;
            box-shadow: var(--shadow-sm);
            transition: all 0.25s ease;
            border: 1px solid var(--gray-light);
        }
        .card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
        }
        .card i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 20px;
        }
        .card h3 {
            margin-bottom: 12px;
        }

        /* ========== RECHERCHE MULTI-CRITÈRES ========== */
        .search-section {
            background: var(--white);
            border-radius: 60px;
            padding: 24px 32px;
            margin: 40px 0;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-light);
        }
        .search-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: flex-end;
            justify-content: space-between;
        }
        .filter-group {
            flex: 1;
            min-width: 160px;
        }
        .filter-group label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            font-size: 0.85rem;
        }
        .filter-group select, .filter-group input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 48px;
            border: 1px solid var(--gray-mid);
            font-family: 'Inter', sans-serif;
            background: var(--white);
        }
        .btn-search {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 48px;
            font-weight: bold;
            cursor: pointer;
        }

        /* ========== INSCRIPTION EN 2 ÉTAPES ========== */
        .inscription-wrapper {
            background: var(--white);
            border-radius: var(--radius-card);
            padding: 40px;
            max-width: 900px;
            margin: 40px auto;
            box-shadow: var(--shadow-md);
        }
        .step-indicator {
            display: flex;
            gap: 20px;
            margin-bottom: 32px;
            border-bottom: 2px solid var(--gray-light);
            padding-bottom: 12px;
        }
        .step {
            font-weight: 600;
            color: var(--text-muted);
        }
        .step.active {
            color: var(--primary);
            border-bottom: 3px solid var(--primary);
            padding-bottom: 12px;
        }
        .two-col {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
        }
        .form-group {
            margin-bottom: 20px;
            flex: 1;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        input, select {
            width: 100%;
            padding: 12px 16px;
            border-radius: 24px;
            border: 1px solid var(--gray-mid);
        }

        /* ========== CARTES REGIMES & GOLD ========== */
        .gold-badge {
            background: linear-gradient(135deg, #F4A261, #E76F51);
            color: white;
            padding: 6px 12px;
            border-radius: 60px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        .price {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
        }

        /* ========== FOOTER ========== */
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
            .hero-content h1 {
                font-size: 2.2rem;
            }
            .search-filters {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<!-- MENU HORIZONTAL (Barre de navigation) -->
<div class="navbar">
    <div class="nav-container">
        <div class="logo">Nutri<span>Goal</span></div>
        <div class="nav-links">
            <a href="#">Accueil</a>
            <a href="#">Régimes</a>
            <a href="#">Sports</a>
            <a href="#">Mon profil</a>
        </div>
        <div class="nav-buttons">
            <button class="btn-outline" onclick="alert('Page connexion — redirection vers login')">Se connecter</button>
            <button class="btn-primary" onclick="document.getElementById('inscriptionSection').scrollIntoView({behavior:'smooth'})">S’inscrire</button>
        </div>
    </div>
</div>

<!-- PAGE D'ACCUEIL (Landing) — PAS LA PAGE DE LOGIN -->
<div class="hero">
    <div class="container hero-grid">
        <div class="hero-content">
            <h1>Atteignez <br>votre objectif poids</h1>
            <p>Régimes adaptés à votre IMC, activités sportives sur mesure, suivi intelligent.</p>
            <button class="btn-primary btn-large" onclick="document.getElementById('inscriptionSection').scrollIntoView({behavior:'smooth'})">✨ Commencer l’expérience</button>
            <div class="hero-stats">
                <div class="stat"><span class="stat-number">+2500</span><br>utilisateurs actifs</div>
                <div class="stat"><span class="stat-number">15</span><br>régimes experts</div>
            </div>
        </div>
        <div class="hero-image">
            <i class="fas fa-apple-alt" style="font-size: 4rem; color: var(--primary-dark);"></i>
            <p style="margin-top: 12px;">Calculez votre IMC <br> et booster votre santé</p>
        </div>
    </div>
</div>

<div class="container">
    <!-- Section RECHERCHE MULTI-CRITÈRES -->
    <div class="search-section">
        <h3 style="margin-bottom: 16px;"><i class="fas fa-sliders-h"></i> Recherche multicritères</h3>
        <div class="search-filters">
            <div class="filter-group">
                <label>Objectif</label>
                <select>
                    <option>Perdre du poids</option>
                    <option>Augmenter mon poids</option>
                    <option>Atteindre IMC idéal</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Durée (semaines)</label>
                <select>
                    <option>4 semaines</option>
                    <option>8 semaines</option>
                    <option>12 semaines</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Prix max (€)</label>
                <input type="number" placeholder="ex: 150">
            </div>
            <button class="btn-search"><i class="fas fa-search"></i> Rechercher</button>
        </div>
    </div>

    <!-- Exemple de RÉGIMES (CRUD visuel) -->
    <div class="section">
        <h2 class="section-title">Régimes populaires</h2>
        <p class="section-sub">Personnalisés selon votre métabolisme et objectif</p>
        <div class="cards-grid">
            <div class="card">
                <i class="fas fa-leaf"></i>
                <h3>Méditerranéen Actif</h3>
                <p>Perte de poids - 8 semaines</p>
                <div><span class="price">149€</span> <span class="gold-badge"><i class="fas fa-crown"></i> -15% Gold</span></div>
                <div style="margin-top: 12px;">🐟 30% poisson | 🍗 20% volaille | 🥩 15% viande</div>
            </div>
            <div class="card">
                <i class="fas fa-dumbbell"></i>
                <h3>Hyperprotéiné</h3>
                <p>Augmentation masse - 12 semaines</p>
                <div><span class="price">199€</span></div>
                <div style="margin-top: 12px;">🍗 45% volaille | 🥩 35% viande</div>
            </div>
            <div class="card">
                <i class="fas fa-heartbeat"></i>
                <h3>Équilibre IMC idéal</h3>
                <p>Rééquilibrage - 6 semaines</p>
                <div><span class="price">99€</span></div>
                <div>🏃 Sport inclus : cardio doux</div>
            </div>
        </div>
    </div>

    <!-- SECTION INSCRIPTION (2 PAGES VISUELLEMENT) -->
    <div id="inscriptionSection" class="inscription-wrapper">
        <h2 style="margin-bottom: 8px;">Créer mon compte</h2>
        <p style="margin-bottom: 24px;">Étape 1 / 2 : Informations personnelles</p>
        <div class="step-indicator">
            <div class="step active">📝 Identité</div>
            <div class="step">❤️ Santé & objectifs</div>
        </div>
        <!-- Page 1 (simulée) -->
        <div class="two-col">
            <div class="form-group"><label>Nom complet</label><input type="text" placeholder="Dupont Marie"></div>
            <div class="form-group"><label>Email</label><input type="email" placeholder="marie@exemple.com"></div>
            <div class="form-group"><label>Genre</label><select><option>Femme</option><option>Homme</option><option>Autre</option></select></div>
            <div class="form-group"><label>Mot de passe</label><input type="password" placeholder="••••••"></div>
        </div>
        <div style="border-top: 1px solid #ede9e2; margin: 24px 0;"></div>
        <p><strong>Étape 2/2 : Mes données de santé</strong></p>
        <div class="two-col">
            <div class="form-group"><label>Taille (cm)</label><input type="number" placeholder="165"></div>
            <div class="form-group"><label>Poids (kg)</label><input type="number" placeholder="62"></div>
        </div>
        <div class="form-group"><label>Objectif principal</label>
            <select>
                <option>Réduire mon poids</option>
                <option>Augmenter mon poids</option>
                <option>Atteindre mon IMC idéal</option>
            </select>
        </div>
        <button class="btn-primary" style="width:100%; margin-top: 24px;">✅ Finaliser inscription</button>
        <p style="margin-top: 16px; font-size:0.8rem;">Après calcul, votre IMC s’affichera et des régimes sur mesure vous seront proposés.</p>
    </div>

    <!-- GOLD OPTION + MONNAIE / CODE promo -->
    <div class="cards-grid" style="margin: 40px 0;">
        <div class="card" style="background: #FEF5E8;">
            <i class="fas fa-gem" style="color:#E76F51;"></i>
            <h3>Option Gold <i class="fas fa-crown"></i></h3>
            <p>Paiement unique : <strong>49€</strong> à vie</p>
            <p>✅ 15% de réduction sur tous les régimes</p>
            <p>✅ Accès illimité aux programmes sportifs avancés</p>
            <button class="btn-primary">Devenir Gold</button>
        </div>
        <div class="card">
            <i class="fas fa-wallet"></i>
            <h3>Porte-monnaie NutriGoal</h3>
            <p>Ajoutez de l’argent avec un code :</p>
            <div style="display:flex; gap: 8px; margin-top: 12px;">
                <input type="text" placeholder="CODE PROMO" style="flex:3;">
                <button class="btn-outline" style="flex:1;">Valider</button>
            </div>
            <small>ex: BIENVENUE10, NUTRIGOLD</small>
        </div>
    </div>

    <!-- ACTIVITES SPORTIVES suggérées -->
    <div class="section">
        <h2 class="section-title">Activités sportives associées</h2>
        <div class="cards-grid">
            <div class="card"><i class="fas fa-walking"></i><h3>Marche active</h3><p>30min/jour · brûle graisse</p></div>
            <div class="card"><i class="fas fa-swimmer"></i><h3>Natation</h3><p>3x/semaine · cardio+tonus</p></div>
            <div class="card"><i class="fas fa-bicycle"></i><h3>Vélo doux</h3><p>Idéal pour IMC idéal</p></div>
        </div>
    </div>

    <!-- BACK OFFICE APERÇU (statistique / dashboard design) -->
    <div class="inscription-wrapper" style="background:#E9F0E6">
        <h3><i class="fas fa-chart-line"></i> Back Office - Tableau de bord admin</h3>
        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 24px;">
            <div style="background:white; border-radius: 24px; padding: 16px; flex:1;"><strong>👥 Utilisateurs</strong><br>24 inscrits cette semaine</div>
            <div style="background:white; border-radius: 24px; padding: 16px; flex:1;"><strong>💰 Revenus régimes</strong><br>1 280 €</div>
            <div style="background:white; border-radius: 24px; padding: 16px; flex:1;"><strong>⭐ Gold membres</strong><br>128 utilisateurs</div>
        </div>
        <div style="margin-top: 24px;"><canvas id="fakeChart" width="400" height="150" style="width:100%; background:#fff; border-radius: 24px;"></canvas></div>
        <p style="margin-top: 12px;"><i class="fas fa-database"></i> CRUD régimes (viande/poisson/volaille), validation codes, gestion activités sportives.</p>
    </div>
</div>

<footer>
    <div class="container">
        <p>© 2026 NutriGoal — Application régime & IMC. Design Gestalt | Multi-pages | Recherche multicritères</p>
        <p><i class="fas fa-code"></i> Projet S4+ — Livraison 11 mai 2026</p>
    </div>
</footer>

<!-- Petit script juste pour le graphique symbolique -->
<script>
    // simulation d'un mini graphique (canvas factice)
    const canvas = document.getElementById('fakeChart');
    if(canvas) {
        const ctx = canvas.getContext('2d');
        canvas.width = canvas.clientWidth;
        canvas.height = 150;
        ctx.fillStyle = "#5D9B6E";
        ctx.fillRect(40, 80, 60, 40);
        ctx.fillRect(120, 50, 60, 70);
        ctx.fillRect(200, 30, 60, 90);
        ctx.fillRect(280, 70, 60, 50);
        ctx.fillStyle = "#F4A261";
        ctx.fillRect(40, 130, 310, 10);
        ctx.font = "12px Inter";
        ctx.fillStyle = "#1E2A2E";
        ctx.fillText("Évolution inscriptions", 20, 30);
    }
    // Alerte pour démontrer la redirection "Commencer l'expérience"
    console.log("Design final — principe Gestalt (proximité, similarité, continuité visuelle)");
</script>
</body>
</html>
