<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier utilisateur - Admin NutriGoal</title>
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
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #EDE9E2;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-group input {
            width: auto;
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
    </style>
</head>

<body>
    <div class="admin-container">
        <!-- START MENU  -->
        <?= $this->include('partials/navbar') ?>
        <!-- END MENU  -->

        <div class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-edit"></i> Modifier l'utilisateur</h1>
                <div>
                    <span>👋 <?= esc($admin_nom) ?></span>
                    <a href="/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                </div>
            </div>

            <div class="form-card">
                <form action="/admin/users/update/<?= $user['id'] ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label>Nom complet *</label>
                        <input type="text" name="nom" value="<?= esc($user['nom']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" value="<?= esc($user['email']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                        <input type="password" name="password" placeholder="********">
                    </div>

                    <div class="form-group">
                        <label>Genre *</label>
                        <select name="genre" required>
                            <option value="homme" <?= $user['genre'] == 'homme' ? 'selected' : '' ?>>Homme</option>
                            <option value="femme" <?= $user['genre'] == 'femme' ? 'selected' : '' ?>>Femme</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Rôle *</label>
                        <select name="role" required>
                            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>Utilisateur standard</option>
                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Administrateur</option>
                        </select>
                    </div>

                    <div class="form-group checkbox-group">
                        <input type="checkbox" name="is_gold" value="1" <?= $user['is_gold'] ? 'checked' : '' ?>>
                        <label>Membre Gold (15% de réduction sur les régimes)</label>
                    </div>

                    <div style="display: flex; gap: 15px; margin-top: 20px;">
                        <button type="submit" class="btn-save"><i class="fas fa-save"></i> Enregistrer</button>
                        <a href="/admin/users" class="btn-back"><i class="fas fa-arrow-left"></i> Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>