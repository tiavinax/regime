<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Admin NutriGoal</title>
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

        .btn-view,
        .btn-edit,
        .btn-gold {
            padding: 5px 12px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.8rem;
            display: inline-block;
            margin-right: 5px;
        }

        .btn-view {
            background: #3498db;
            color: white;
        }

        .btn-edit {
            background: #F4A261;
            color: white;
        }

        .btn-gold {
            background: #f1c40f;
            color: #1E2A2E;
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

        .badge-gold {
            background: linear-gradient(135deg, #F4A261, #E76F51);
            color: white;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: bold;
            display: inline-block;
        }

        .badge-admin {
            background: #1E2A2E;
            color: white;
            padding: 2px 8px;
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
        <!-- START MENU  -->
        <?= $this->include('partials/navbar') ?>
        <!-- END MENU  -->

        <div class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-users"></i> Gestion des Utilisateurs</h1>
                <div>
                    <span>👋 <?= esc($admin_nom) ?></span>
                    <a href="/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                </div>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Genre</th>
                        <th>Statut</th>
                        <th>Date inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><strong><?= esc($user['nom']) ?></strong></td>
                            <td><?= esc($user['email']) ?></td>
                            <td><?= $user['genre'] === 'homme' ? '👨 Homme' : '👩 Femme' ?></td>
                            <td>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <span class="badge-admin"><i class="fas fa-shield-alt"></i> Admin</span>
                                <?php endif; ?>
                                <?php if ($user['is_gold']): ?>
                                    <span class="badge-gold"><i class="fas fa-crown"></i> Gold</span>
                                <?php endif; ?>
                                <?php if ($user['role'] !== 'admin' && !$user['is_gold']): ?>
                                    <span style="color:#6B7A6F;">Standard</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($user['date_inscription'])) ?></td>
                            <td>
                                <a href="/admin/users/view/<?= $user['id'] ?>" class="btn-view"><i class="fas fa-eye"></i> Voir</a>
                                <a href="/admin/users/edit/<?= $user['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Modifier</a>
                                <?php if ($user['id'] != session()->get('admin_id')): ?>
                                    <a href="/admin/users/toggle-gold/<?= $user['id'] ?>" class="btn-gold"><i class="fas fa-crown"></i> Gold</a>
                                    <a href="/admin/users/delete/<?= $user['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer cet utilisateur ?')"><i class="fas fa-trash"></i> Suppr</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>