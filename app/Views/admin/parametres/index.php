<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - Admin NutriGoal</title>
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
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }
        .param-group {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #EDE9E2;
        }
        .param-group label {
            display: block;
            font-weight: 600;
            color: #1E2A2E;
            margin-bottom: 5px;
        }
        .param-group .description {
            font-size: 0.8rem;
            color: #6B7A6F;
            margin-bottom: 10px;
        }
        .param-group input, .param-group select {
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
                <a href="/admin/codes"><i class="fas fa-ticket-alt"></i> Codes promo</a>
                <a href="/admin/parametres" class="active"><i class="fas fa-cog"></i> Paramètres</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-cog"></i> Paramètres généraux</h1>
                <div>
                    <span>👋 <?= esc($admin_nom) ?></span>
                    <a href="/admin/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                </div>
            </div>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <div class="form-card">
                <h2 style="margin-bottom: 20px;">Configuration de l'application</h2>
                
                <?php foreach($parametres as $param): ?>
                <div class="param-group">
                    <form action="/admin/parametres/update/<?= $param['id'] ?>" method="POST" style="display: flex; gap: 15px; align-items: flex-end;">
                        <?= csrf_field() ?>
                        <div style="flex: 1;">
                            <label><?= ucfirst(str_replace('_', ' ', $param['cle'])) ?></label>
                            <?php if($param['description']): ?>
                                <div class="description"><?= esc($param['description']) ?></div>
                            <?php endif; ?>
                            <?php if($param['type'] == 'textarea'): ?>
                                <textarea name="valeur" rows="3"><?= esc($param['valeur']) ?></textarea>
                            <?php else: ?>
                                <input type="text" name="valeur" value="<?= esc($param['valeur']) ?>">
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn-save" style="margin-bottom: 0; padding: 12px 20px;">Sauvegarder</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>