<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails utilisateur - Admin NutriGoal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #F9F6F0;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: #1E2A2E;
            color: white;
            padding: 30px 20px;
            position: fixed;
            height: 100vh;
        }

        .sidebar .logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            color: #5D9B6E;
            text-decoration: none;
            display: block;
        }

        .sidebar .logo span {
            color: #F4A261;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #B0C4B8;
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 8px;
        }

        .sidebar nav a:hover {
            background: #5D9B6E;
            color: white;
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 20px 30px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #EDE9E2;
        }

        .logout-btn {
            background: #E76F51;
            color: white;
            padding: 8px 16px;
            border-radius: 40px;
            text-decoration: none;
        }

        .btn-back {
            background: #95A5A6;
            color: white;
            padding: 12px 24px;
            border-radius: 40px;
            text-decoration: none;
            display: inline-block;
        }

        .detail-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .detail-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #EDE9E2;
        }

        .detail-label {
            width: 200px;
            font-weight: 600;
            color: #1E2A2E;
        }

        .detail-value {
            flex: 1;
            color: #6B7A6F;
        }

        .badge-gold {
            background: linear-gradient(135deg, #F4A261, #E76F51);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        .badge-admin {
            background: #1E2A2E;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <div class="sidebar">
            <a href="/admin" class="logo">Nutri<span>Goal</span></a>
            <nav>
                <a href="/admin"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="/admin/regimes"><i class="fas fa-utensils"></i> Régimes</a>
                <a href="/admin/activites"><i class="fas fa-running"></i> Activités</a>
                <a href="/admin/users" class="active"><i class="fas fa-users"></i> Utilisateurs</a>
                <a href="/admin/codes"><i class="fas fa-ticket-alt"></i> Codes promo</a>
                <a href="/admin/parametres"><i class="fas fa-cog"></i> Paramètres</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-user"></i> Détails utilisateur</h1>
                <div>
                    <span>👋 <?= esc($admin_nom) ?></span>
                    <a href="/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                </div>
            </div>

            <div class="detail-card">
                <h2><i class="fas fa-user-circle"></i> Informations personnelles</h2>
                <div class="detail-row">
                    <div class="detail-label">ID</div>
                    <div class="detail-value"><?= $user['id'] ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Nom complet</div>
                    <div class="detail-value"><?= esc($user['nom']) ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email</div>
                    <div class="detail-value"><?= esc($user['email']) ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Genre</div>
                    <div class="detail-value"><?= $user['genre'] === 'homme' ? 'Homme' : 'Femme' ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Date de naissance</div>
                    <div class="detail-value"><?= date('d/m/Y', strtotime($user['date_naissance'])) ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Date d'inscription</div>
                    <div class="detail-value"><?= date('d/m/Y H:i', strtotime($user['date_inscription'])) ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Statut</div>
                    <div class="detail-value">
                        <?php if ($user['role'] === 'admin'): ?>
                            <span class="badge-admin">Administrateur</span>
                        <?php endif; ?>
                        <?php if ($user['is_gold']): ?>
                            <span class="badge-gold">Membre Gold</span>
                        <?php endif; ?>
                        <?php if ($user['role'] !== 'admin' && !$user['is_gold']): ?>
                            Membre Standard
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if ($profil): ?>
                <div class="detail-card">
                    <h2><i class="fas fa-heartbeat"></i> Profil physique (dernier)</h2>
                    <div class="detail-row">
                        <div class="detail-label">Poids</div>
                        <div class="detail-value"><?= $profil['poids_kg'] ?> kg</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Taille</div>
                        <div class="detail-value"><?= $profil['taille_cm'] ?> cm</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Niveau d'activité</div>
                        <div class="detail-value"><?= $profil['niveau_activite'] ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Date mesure</div>
                        <div class="detail-value"><?= date('d/m/Y', strtotime($profil['date_mesure'])) ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($objectif): ?>
                <div class="detail-card">
                    <h2><i class="fas fa-bullseye"></i> Objectif actuel</h2>
                    <div class="detail-row">
                        <div class="detail-label">Type</div>
                        <div class="detail-value">
                            <?php if ($objectif['type_objectif'] == 'augmenter_poids'): ?>⬆️ Augmenter le poids
                            <?php elseif ($objectif['type_objectif'] == 'reduire_poids'): ?>⬇️ Réduire le poids
                            <?php else: ?>⚖️ Atteindre IMC idéal<?php endif; ?>
                        </div>
                    </div>
                    <?php if ($objectif['poids_cible_kg']): ?>
                        <div class="detail-row">
                            <div class="detail-label">Poids cible</div>
                            <div class="detail-value"><?= $objectif['poids_cible_kg'] ?> kg</div>
                        </div>
                    <?php endif; ?>
                    <div class="detail-row">
                        <div class="detail-label">Date début</div>
                        <div class="detail-value"><?= date('d/m/Y', strtotime($objectif['date_debut'])) ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Durée souhaitée</div>
                        <div class="detail-value"><?= $objectif['duree_souhaitee_semaines'] ?> semaines</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Status</div>
                        <div class="detail-value"><?= $objectif['status'] ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <a href="/admin/users" class="btn-back"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
        </div>
    </div>
</body>

</html>