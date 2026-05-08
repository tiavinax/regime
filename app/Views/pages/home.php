<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="hero" style="padding: 60px 0; background: linear-gradient(135deg, #F9F6F0 0%, #E9F0E6 100%);">
    <div class="container">
        <div style="text-align: center; max-width: 800px; margin: 0 auto;">
            <h1 style="font-size: 3rem; margin-bottom: 20px;">Atteignez votre<br>objectif poids</h1>
            <p style="font-size: 1.2rem; color: var(--text-muted); margin-bottom: 32px;">
                Calculez votre IMC, obtenez un régime personnalisé et suivez vos progrès
            </p>
            <a href="/register" class="btn-primary" style="padding: 14px 36px; font-size: 1.1rem;">
                ✨ Commencer mon bilan gratuit
            </a>
        </div>
    </div>
</div>

<div class="container" style="padding: 60px 0;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 32px;">
        <div class="card" style="background: white; padding: 28px; border-radius: var(--radius-card); text-align: center;">
            <i class="fas fa-calculator" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 16px;"></i>
            <h3>Calcul IMC précis</h3>
            <p>Basé sur votre profil et vos objectifs</p>
        </div>
        <div class="card" style="background: white; padding: 28px; border-radius: var(--radius-card); text-align: center;">
            <i class="fas fa-utensils" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 16px;"></i>
            <h3>Régimes sur mesure</h3>
            <p>Adaptés à votre métabolisme</p>
        </div>
        <div class="card" style="background: white; padding: 28px; border-radius: var(--radius-card); text-align: center;">
            <i class="fas fa-dumbbell" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 16px;"></i>
            <h3>Activités sportives</h3>
            <p>Programmes inclus selon objectif</p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->include('layout/footer') ?>