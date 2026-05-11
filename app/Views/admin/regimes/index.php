<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Régimes - Admin NutriGoal</title>
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

        .sidebar nav a:hover,
        .sidebar nav a.active {
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

        .btn-add {
            background: #5D9B6E;
            color: white;
            padding: 10px 20px;
            border-radius: 40px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn-edit {
            background: #F4A261;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.8rem;
            display: inline-block;
            margin-right: 5px;
        }

        .btn-delete {
            background: #E76F51;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.8rem;
            display: inline-block;
        }

        table {
            width: 100%;
            background: white;
            border-radius: 20px;
            border-collapse: collapse;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #EDE9E2;
        }

        th {
            background: #5D9B6E;
            color: white;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-augmenter {
            background: #2ecc71;
            color: white;
        }

        .badge-reduire {
            background: #e74c3c;
            color: white;
        }

        .badge-maintenir {
            background: #3498db;
            color: white;
        }

        .alert-success {
            background: #D1FAE5;
            color: #065F46;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
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
                <h1><i class="fas fa-utensils"></i> Gestion des Régimes</h1>
                <div>
                    <span>👋 <?= esc($admin_nom) ?></span>
                    <a href="/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                </div>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <a href="/admin/regimes/create" class="btn-add"><i class="fas fa-plus"></i> Nouveau régime</a>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Prix/jour</th>
                        <th>Actions</th< /tr>
                </thead>
                <tbody>
                    <?php foreach ($regimes as $regime): ?>
                        <tr>
                            <td><?= $regime['id'] ?></td>
                            <td><strong><?= esc($regime['nom_regime']) ?></strong></td>
                            <td><?= substr(esc($regime['description']), 0, 50) ?>...</td>
                            <td>
                                <span class="badge badge-<?= $regime['type_cible'] ?>">
                                    <?= $regime['type_cible'] === 'augmenter' ? '⬆️ Prise' : ($regime['type_cible'] === 'reduire' ? '⬇️ Perte' : '⚖️ Maintien') ?>
                                </span>
                            </td>
                            <td><?= $regime['prix_journalier'] ? number_format($regime['prix_journalier'], 2) : '-' ?> €</td>
                            <td>
                                <a href="/admin/regimes/edit/<?= $regime['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Modifier</a>
                                <a href="/admin/regimes/delete/<?= $regime['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer ce régime ?')"><i class="fas fa-trash"></i> Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>