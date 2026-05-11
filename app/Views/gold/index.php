<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<style>
    .gold-container {
        max-width: 1000px;
        margin: 40px auto;
    }

    .gold-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .gold-header h1 {
        font-size: 2.5rem;
        background: linear-gradient(135deg, #F4A261, #E76F51);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .gold-card {
        background: linear-gradient(135deg, #1a1a2e, #16213e);
        border-radius: 32px;
        padding: 40px;
        color: white;
        text-align: center;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .gold-card::before {
        content: '⭐';
        position: absolute;
        top: -20px;
        right: -20px;
        font-size: 150px;
        opacity: 0.1;
        transform: rotate(15deg);
    }

    .gold-icon {
        font-size: 4rem;
        color: #F4A261;
        margin-bottom: 20px;
    }

    .gold-price {
        font-size: 3rem;
        font-weight: 800;
        margin: 20px 0;
    }

    .gold-price small {
        font-size: 1rem;
        color: #ccc;
    }

    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin: 40px 0;
    }

    .benefit-item {
        background: rgba(255, 255, 255, 0.1);
        padding: 20px;
        border-radius: 20px;
        text-align: center;
    }

    .benefit-item i {
        font-size: 2rem;
        color: #F4A261;
        margin-bottom: 10px;
    }

    .benefit-item h3 {
        margin-bottom: 10px;
    }

    .benefit-item p {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .solde-info {
        background: white;
        border-radius: 24px;
        padding: 30px;
        text-align: center;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .solde-amount {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary);
    }

    .btn-gold {
        background: linear-gradient(135deg, #F4A261, #E76F51);
        color: white;
        border: none;
        padding: 18px 40px;
        border-radius: 50px;
        font-size: 1.2rem;
        font-weight: 700;
        cursor: pointer;
        transition: 0.3s;
        width: 100%;
    }

    .btn-gold:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 30px rgba(231, 111, 81, 0.4);
    }

    .btn-gold:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    .warning-text {
        color: #e74c3c;
        margin-top: 15px;
    }

    .manquant {
        color: #e74c3c;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .gold-card {
            padding: 25px;
        }

        .gold-price {
            font-size: 2rem;
        }
    }
</style>

<div class="container gold-container">

    <!-- Messages -->
    <?php if (session('error')): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session('success')): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
            <?= session('success') ?>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="gold-header">
        <h1><i class="fas fa-crown"></i> Devenir Membre Gold</h1>
        <p>Paiement unique, avantages à vie</p>
    </div>

    <!-- Carte Gold -->
    <div class="gold-card">
        <div class="gold-icon">
            <i class="fas fa-gem"></i>
        </div>
        <h2>✨ Option Gold ✨</h2>
        <div class="gold-price">
            49,99 € <small>une seule fois</small>
        </div>
        <p>Profitez d'avantages exclusifs pour toute la durée de votre compte</p>
    </div>

    <!-- Avantages -->
    <div class="benefits-grid">
        <div class="benefit-item">
            <i class="fas fa-percent"></i>
            <h3>15% de réduction</h3>
            <p>Sur tous les régimes alimentaires</p>
        </div>
        <div class="benefit-item">
            <i class="fas fa-infinity"></i>
            <h3>Valable à vie</h3>
            <p>Paiement unique, avantages permanents</p>
        </div>
        <div class="benefit-item">
            <i class="fas fa-chart-line"></i>
            <h3>Programmes prioritaires</h3>
            <p>Accès aux nouveaux régimes en avant-première</p>
        </div>
        <div class="benefit-item">
            <i class="fas fa-headset"></i>
            <h3>Support prioritaire</h3>
            <p>Assistance personnalisée 7j/7</p>
        </div>
    </div>

    <!-- Information solde -->
    <div class="solde-info">
        <i class="fas fa-wallet" style="font-size: 2rem; color: var(--primary);"></i>
        <h3>Votre porte-monnaie</h3>
        <div class="solde-amount"><?= number_format($solde, 2) ?> €</div>

        <?php if ($solde_suffisant): ?>
            <p style="color: #27ae60; margin-top: 10px;">
                ✅ Solde suffisant pour acheter l'option Gold
            </p>
        <?php else: ?>
            <p class="manquant">
                ⚠️ Il vous manque <?= number_format($prix_gold - $solde, 2) ?> €
            </p>
            <p style="margin-top: 10px;">
                <a href="/wallet" class="btn-validate" style="background: var(--primary); color: white; padding: 10px 20px; border-radius: 30px; text-decoration: none;">
                    <i class="fas fa-ticket-alt"></i> Ajouter de l'argent
                </a>
            </p>
        <?php endif; ?>
    </div>

    <!-- Bouton d'achat -->
    <form action="<?= base_url('/gold/acheter') ?>" method="POST">
        <?= csrf_field() ?>
        <button type="submit" class="btn-gold" <?= !$solde_suffisant ? 'disabled' : '' ?>>
            <i class="fas fa-crown"></i>
            <?= $solde_suffisant ? 'Devenir Gold maintenant' : 'Solde insuffisant' ?>
        </button>
    </form>

    <p style="text-align: center; margin-top: 30px; color: var(--text-muted); font-size: 0.85rem;">
        <i class="fas fa-lock"></i> Paiement sécurisé via votre porte-monnaie NutriGoal
    </p>

</div>

<?= $this->endSection() ?>
<?= $this->include('layout/footer') ?>