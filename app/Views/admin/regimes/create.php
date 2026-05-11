<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un régime - Admin NutriGoal</title>
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

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
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

        .form-group input,
        .form-group select,
        .form-group textarea {
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
            border: none;
            border-radius: 40px;
            text-decoration: none;
            display: inline-block;
        }

        .error {
            color: #e74c3c;
            font-size: 0.8rem;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <div class="sidebar">
            <a href="/admin" class="logo">Nutri<span>Goal</span></a>
            <nav>
                <a href="/admin"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="/admin/regimes" class="active"><i class="fas fa-utensils"></i> Régimes</a>
                <a href="/admin/activites"><i class="fas fa-running"></i> Activités</a>
                <a href="/admin/users"><i class="fas fa-users"></i> Utilisateurs</a>
                <a href="/admin/codes"><i class="fas fa-ticket-alt"></i> Codes promo</a>
                <a href="/admin/parametres"><i class="fas fa-cog"></i> Paramètres</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-plus"></i> Ajouter un régime</h1>
                <div>
                    <span>👋 <?= esc($admin_nom) ?></span>
                    <a href="/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                </div>
            </div>

            <div class="form-card">
                <form action="/admin/regimes/store" method="POST">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label>Nom du régime *</label>
                        <input type="text" name="nom_regime" value="<?= old('nom_regime') ?>" required>
                        <?php if (session()->get('errors.nom_regime')): ?>
                            <div class="error"><?= session()->get('errors.nom_regime') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Description *</label>
                        <textarea name="description" rows="4" required><?= old('description') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Type cible *</label>
                        <select name="type_cible" required>
                            <option value="">Sélectionner</option>
                            <option value="augmenter">⬆️ Augmenter (prise de poids)</option>
                            <option value="reduire">⬇️ Réduire (perte de poids)</option>
                            <option value="maintenir">⚖️ Maintenir (équilibre)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Apport calorique référence (kcal)</label>
                        <input type="number" name="apport_calorique_reference" value="<?= old('apport_calorique_reference') ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix journalier (€)</label>
                        <input type="number" step="0.01" name="prix_journalier" value="<?= old('prix_journalier') ?>">
                    </div>

                    <div class="form-group">
                        <label>Variation poids/semaine (kg)</label>
                        <input type="number" step="0.1" name="variation_poids_semaine" value="<?= old('variation_poids_semaine') ?>">
                    </div>

                    <div style="display: flex; gap: 15px; margin-top: 20px;">
                        <button type="submit" class="btn-save"><i class="fas fa-save"></i> Enregistrer</button>
                        <a href="/admin/regimes" class="btn-back"><i class="fas fa-arrow-left"></i> Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>