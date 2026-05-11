<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Codes Promo - Admin NutriGoal</title>
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
        .sidebar nav a:hover, .sidebar nav a.active {
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
        .btn-edit, .btn-toggle {
            padding: 5px 12px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.8rem;
            display: inline-block;
            margin-right: 5px;
        }
        .btn-edit { background: #F4A261; color: white; }
        .btn-toggle { background: #3498db; color: white; }
        .btn-delete { background: #E76F51; color: white; padding: 5px 12px; border-radius: 20px; text-decoration: none; font-size: 0.8rem; display: inline-block; }
        table {
            width: 100%;
            background: white;
            border-radius: 20px;
            border-collapse: collapse;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #EDE9E2;
        }
        th {
            background: #5D9B6E;
            color: white;
        }
        .badge-actif {
            background: #2ecc71;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            display: inline-block;
        }
        .badge-inactif {
            background: #e74c3c;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            display: inline-block;
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
                <a href="/admin/regimes"><i class="fas fa-utensils"></i> Régimes</a>
                <a href="/admin/activites"><i class="fas fa-running"></i> Activités</a>
                <a href="/admin/users"><i class="fas fa-users"></i> Utilisateurs</a>
                <a href="/admin/codes" class="active"><i class="fas fa-ticket-alt"></i> Codes promo</a>
                <a href="/admin/parametres"><i class="fas fa-cog"></i> Paramètres</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-ticket-alt"></i> Gestion des Codes Promo</h1>
                <div>
                    <span>👋 <?= esc($admin_nom) ?></span>
                    <a href="/admin/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                </div>
            </div>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <a href="/admin/codes/create" class="btn-add"><i class="fas fa-plus"></i> Nouveau code promo</a>

            <table>
                <thead>
                    <tr><th>ID</th><th>Code</th><th>Valeur</th><th>Type</th><th>Utilisations</th><th>Expiration</th><th>Statut</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach($codes as $code): ?>
                    <tr>
                        <td><?= $code['id'] ?> </td>
                        <td><strong><?= esc($code['code']) ?></strong> </td>
                        <td><?= $code['type'] == 'percentage' ? $code['valeur'] . '%' : number_format($code['valeur'], 2) . ' €' ?> </td>
                        <td><?= $code['type'] == 'percentage' ? 'Pourcentage' : 'Montant fixe' ?> </td>
                        <td><?= $code['utilisations_actuelles'] ?> / <?= $code['utilisations_max'] ?? '∞' ?> </td>
                        <td><?= $code['date_expiration'] ? date('d/m/Y', strtotime($code['date_expiration'])) : 'Jamais' ?> </td>
                        <td>
                            <?php if($code['est_actif']): ?>
                                <span class="badge-actif">Actif</span>
                            <?php else: ?>
                                <span class="badge-inactif">Inactif</span>
                            <?php endif; ?>
                         </td>
                        <td>
                            <a href="/admin/codes/edit/<?= $code['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Modifier</a>
                            <a href="/admin/codes/toggle/<?= $code['id'] ?>" class="btn-toggle"><i class="fas fa-power-off"></i> Activer/Désactiver</a>
                            <a href="/admin/codes/delete/<?= $code['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer ce code promo ?')"><i class="fas fa-trash"></i> Suppr</a>
                         </td>
                     </tr>
                    <?php endforeach; ?>
                </tbody>
             </table>
        </div>
    </div>
</body>
</html>