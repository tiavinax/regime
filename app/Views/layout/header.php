<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= $title ?? 'NutriGoal' ?> | Application régime & objectifs</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/style.css">

    <style>
        /* ===== VARIABLES ===== */
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
            --error: #e74c3c;
            --success: #2ecc71;
            --shadow-sm: 0 8px 20px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 12px 28px rgba(0, 0, 0, 0.08);
            --radius-card: 28px;
            --radius-btn: 40px;
            --radius-input: 24px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F9F6F0;
            color: var(--text-dark);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1,
        h2,
        h3,
        .logo,
        .nav-links a,
        .btn {
            font-family: 'Poppins', sans-serif;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
            width: 100%;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: var(--white);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
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
            text-decoration: none;
        }

        .logo span {
            color: var(--secondary-dark);
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
        }

        /* ===== BOUTONS ===== */
        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--primary);
            color: var(--primary);
            padding: 8px 20px;
            border-radius: var(--radius-btn);
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: 0.2s;
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            color: white;
            padding: 10px 28px;
            border-radius: var(--radius-btn);
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: 0.2s;
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
            cursor: pointer;
        }

        /* ===== FORMULAIRES (UX) ===== */
        .form-card {
            background: var(--white);
            border-radius: var(--radius-card);
            padding: 40px;
            max-width: 600px;
            margin: 40px auto;
            box-shadow: var(--shadow-md);
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
            text-align: center;
        }

        .form-subtitle {
            text-align: center;
            color: var(--text-muted);
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px 18px;
            border-radius: var(--radius-input);
            border: 1.5px solid var(--gray-mid);
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            transition: 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .form-group .error {
            color: var(--error);
            font-size: 0.8rem;
            margin-top: 6px;
            display: block;
        }

        /* ===== WIZARD (multi-étapes) ===== */
        .wizard-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
        }

        .wizard-steps::before {
            content: '';
            position: absolute;
            top: 24px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gray-light);
            z-index: 1;
        }

        .step {
            text-align: center;
            flex: 1;
            position: relative;
            z-index: 2;
            background: var(--white);
        }

        .step-circle {
            width: 48px;
            height: 48px;
            background: var(--gray-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--text-muted);
            transition: 0.2s;
        }

        .step.active .step-circle {
            background: var(--primary);
            color: white;
        }

        .step.completed .step-circle {
            background: var(--success);
            color: white;
        }

        .step-label {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .step.active .step-label {
            color: var(--primary);
            font-weight: 600;
        }

        /* ===== FOOTER ===== */
        .main-content {
            flex: 1;
        }

        footer {
            background: #1E2A2E;
            color: #CBC3B5;
            padding: 40px 0;
            text-align: center;
            margin-top: 60px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 20px;
            }

            .nav-container {
                flex-direction: column;
                gap: 16px;
            }

            .form-card {
                padding: 24px;
                margin: 20px;
            }

            .step-circle {
                width: 36px;
                height: 36px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="nav-container">
            <a href="/" class="logo">Nutri<span>Goal</span></a>
            <div class="nav-links">
                <a href="<?= base_url('/') ?>">Accueil</a>
                <a href="<?= base_url('/dashboard') ?>">Mon profil</a>
                <a href="<?= base_url('/regimes') ?>">Régimes</a>
                <a href="<?= base_url('/sports') ?>">Sports</a>
                <?php if (session()->has('user_id')): ?>
                    <a href="<?= base_url('/wallet') ?>">💰 Porte-monnaie</a>
                    <?php if (!(session()->get('is_gold') ?? false)): ?>
                        <a href="/gold" style="color: #E76F51;">💎 Gold</a>
                    <?php else: ?>
                        <a href="#" style="color: #F4A261;">👑 Gold</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="nav-buttons">
                <?php if (session()->has('user_id')): ?>
                    <a href="/dashboard" class="btn-outline">Mon profil</a>
                    <a href="/logout" class="btn-outline">Déconnexion</a>
                <?php else: ?>
                    <a href="/login" class="btn-outline">Se connecter</a>
                    <a href="/register" class="btn-primary">S'inscrire</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <main class="main-content">
        <?= $this->renderSection('content') ?>
    </main>