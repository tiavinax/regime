<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - NutriGoal</title>
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
            background: linear-gradient(135deg, #1E2A2E 0%, #2D3E3F 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 28px;
            padding: 40px 36px;
            max-width: 440px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 45px rgba(0,0,0,0.2);
        }
        .logo {
            font-size: 2rem;
            font-weight: 700;
            color: #5D9B6E;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
        }
        .logo span {
            color: #E76F51;
        }
        .badge {
            background: #1E2A2E;
            color: white;
            display: inline-block;
            padding: 5px 16px;
            border-radius: 60px;
            font-size: 0.75rem;
            font-weight: 600;
            margin: 20px 0;
        }
        h2 {
            font-size: 1.5rem;
            margin-bottom: 30px;
            color: #1E2A2E;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            color: #4A5B5E;
        }
        .form-group input {
            width: 100%;
            padding: 14px 18px;
            border: 1.5px solid #E0DCD5;
            border-radius: 24px;
            font-size: 0.95rem;
            outline: none;
            transition: 0.2s;
        }
        .form-group input:focus {
            border-color: #5D9B6E;
            box-shadow: 0 0 0 3px rgba(93, 155, 110, 0.1);
        }
        .btn-login {
            width: 100%;
            background: #5D9B6E;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.2s;
        }
        .btn-login:hover {
            background: #3D7A4E;
            transform: translateY(-2px);
        }
        .alert-error {
            background: #FEE2E2;
            color: #B91C1C;
            padding: 12px;
            border-radius: 16px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            text-align: left;
        }
        .alert-success {
            background: #D1FAE5;
            color: #065F46;
            padding: 12px;
            border-radius: 16px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            text-align: left;
        }
        .separator {
            margin: 24px 0;
            border-top: 1px solid #EDE9E2;
        }
        .demo-box {
            background: #F9F6F0;
            border-radius: 20px;
            padding: 16px;
            text-align: left;
            font-size: 0.75rem;
            color: #6B7A6F;
        }
        .demo-box code {
            background: #E8E4DD;
            padding: 2px 6px;
            border-radius: 8px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #5D9B6E;
            text-decoration: none;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <a href="/" class="logo">Nutri<span>Goal</span></a>
        <div class="badge">
            <i class="fas fa-shield-alt"></i> ESPACE ADMINISTRATEUR
        </div>
        <h2>Connexion</h2>
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <form action="/admin/doLogin" method="POST">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" placeholder="admin@exemple.com" value="<?= old('email') ?>" required autofocus>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Mot de passe</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>
        
        <div class="separator"></div>
        
        <div class="demo-box">
            <p><i class="fas fa-info-circle"></i> <strong>Compte de démonstration :</strong></p>
            <p>📧 Email : <code>admin@exemple.com</code></p>
            <p>🔑 Mot de passe : <code>admin123</code></p>
        </div>
        
        <a href="/" class="back-link"><i class="fas fa-arrow-left"></i> Retour au site</a>
    </div>
</body>
</html>