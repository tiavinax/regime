<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier code promo - Admin NutriGoal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #F9F6F0; }
        .admin-container { display: flex; min-height: 100vh; }
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
        .sidebar .logo span { color: #F4A261; }
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
        .sidebar nav a:hover { background: #5D9B6E; color: white; }
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
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1E2A2E;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #EDE9E2;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
        }
        .btn-save {
            background: #5D9B6E;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-back {
            background: #95A5A6;
            color: white;
            padding: 12px 24px;
            border-radius: 40px;
            text-decoration: none;
            display: inline-block;
        }
        .badge-actif {
            background: #2ecc71;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-block;
        }
        .badge-inactif {
            background: #e74c3c;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
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
                <a href="/admin/users"><i class="fas fa-users"></i> Utilisateurs</a>
                <a href="/admin/codes" class="active"><i class="fas fa-ticket-alt"></i> Codes promo</a>
                <a href="/admin/parametres"><i class="fas fa-cog"></i> Paramètres</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-edit"></i> Modifier le code promo</h1>
                <div>
                    <span>👋 <?= esc($admin_nom) ?></span>
                    <a href="/admin/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                </div>
            </div>

            <div class="form-card">
                <form action="/admin/codes/update/<?= $code['id'] ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label>Code promo *</label>
                        <input type="text" name="code" value="<?= esc($code['code']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Type de réduction *</label>
                        <select name="type" required>
                            <option value="percentage" <?= $code['type'] == 'percentage' ? 'selected' : '' ?>>Pourcentage (%)</option>
                            <option value="fixed" <?= $code['type'] == 'fixed' ? 'selected' : '' ?>>Montant fixe (€)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Valeur *</label>
                        <input type="number" step="0.01" name="valeur" value="<?= $code['valeur'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Nombre maximum d'utilisations</label>
                        <input type="number" name="utilisations_max" value="<?= $code['utilisations_max'] ?>">
                    </div>

                    <div class="form-group">
                        <label>Date d'expiration</label>
                        <input type="date" name="date_expiration" value="<?= $code['date_expiration'] ?>">
                    </div>

                    <div class="form-group">
                        <label>Statut *</label>
                        <select name="est_actif" required>
                            <option value="1" <?= $code['est_actif'] == 1 ? 'selected' : '' ?>>Actif</option>
                            <option value="0" <?= $code['est_actif'] == 0 ? 'selected' : '' ?>>Inactif</option>
                        </select>
                    </div>

                    <div style="display: flex; gap: 15px; margin-top: 20px;">
                        <button type="submit" class="btn-save"><i class="fas fa-save"></i> Mettre à jour</button>
                        <a href="/admin/codes" class="btn-back"><i class="fas fa-arrow-left"></i> Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>