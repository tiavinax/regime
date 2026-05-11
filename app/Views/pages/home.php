<?= $this->extend('layout/header') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<div class="hero">
    <div class="container hero-grid">
        <div class="hero-content">
            <h1>Atteignez <br>votre objectif poids</h1>
            <p>Découvrez nos régimes personnalisés et activités sportives adaptées à vos besoins. Calcul de l'IMC, suivi personnalisé et recommandations sur mesure.</p>
            <div class="hero-buttons">
                <a href="/register" class="btn-primary btn-large">✨ Commencer l'expérience</a>
                <a href="/regimes" class="btn-outline btn-large">Découvrir nos régimes</a>
            </div>
            <div class="hero-stats">
                <div class="stat">
                    <span class="stat-number">+2500</span>
                    <br>utilisateurs actifs
                </div>
                <div class="stat">
                    <span class="stat-number">15</span>
                    <br>régimes experts
                </div>
                <div class="stat">
                    <span class="stat-number">98%</span>
                    <br>satisfaction
                </div>
            </div>
        </div>
        <div class="hero-image">
            <i class="fas fa-apple-alt" style="font-size: 4rem; color: var(--primary-dark);"></i>
            <i class="fas fa-heartbeat" style="font-size: 3rem; color: var(--secondary); margin-top: 20px;"></i>
            <p style="margin-top: 20px;">Calculez votre IMC <br>et boostez votre santé</p>
        </div>
    </div>
</div>

<div class="container">
    <!-- Section Fonctionnalités -->
    <div class="section">
        <h2 class="section-title">Pourquoi choisir NutriGoal ?</h2>
        <p class="section-sub">Une approche complète pour atteindre vos objectifs</p>
        <div class="cards-grid">
            <div class="card">
                <i class="fas fa-calculator"></i>
                <h3>Calcul IMC précis</h3>
                <p>Évaluez votre indice de masse corporelle et suivez votre évolution</p>
            </div>
            <div class="card">
                <i class="fas fa-utensils"></i>
                <h3>Régimes personnalisés</h3>
                <p>Des plans alimentaires adaptés à votre objectif (perte, prise, maintien)</p>
            </div>
            <div class="card">
                <i class="fas fa-running"></i>
                <h3>Activités sur mesure</h3>
                <p>Des exercices adaptés à votre niveau et vos objectifs</p>
            </div>
            <div class="card">
                <i class="fas fa-chart-line"></i>
                <h3>Suivi personnalisé</h3>
                <p>Tableau de bord avec progression et suggestions</p>
            </div>
        </div>
    </div>

    <!-- Régimes populaires -->
    <div class="section">
        <h2 class="section-title">Régimes populaires</h2>
        <p class="section-sub">Découvrez nos programmes les plus appréciés</p>
        <div class="cards-grid">
            <?php foreach($regimes_populaires as $regime): ?>
            <div class="card">
                <i class="fas fa-utensils"></i>
                <h3><?= esc($regime['nom_regime']) ?></h3>
                <p><?= substr(esc($regime['description']), 0, 80) ?>...</p>
                <div class="regime-type">
                    <?php if($regime['type_cible'] == 'augmenter'): ?>
                        <span class="badge badge-augmenter">⬆️ Prise de poids</span>
                    <?php elseif($regime['type_cible'] == 'reduire'): ?>
                        <span class="badge badge-reduire">⬇️ Perte de poids</span>
                    <?php else: ?>
                        <span class="badge badge-maintenir">⚖️ Maintien</span>
                    <?php endif; ?>
                </div>
                <a href="/regimes/detail/<?= $regime['id'] ?>" class="btn-outline" style="margin-top: 15px;">En savoir plus</a>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 30px;">
            <a href="/regimes" class="btn-primary">Voir tous les régimes</a>
        </div>
    </div>

    <!-- Activités populaires -->
    <div class="section">
        <h2 class="section-title">Activités recommandées</h2>
        <p class="section-sub">Des exercices adaptés à vos besoins</p>
        <div class="cards-grid">
            <?php foreach($activites_populaires as $activite): ?>
            <div class="card">
                <i class="fas fa-heartbeat"></i>
                <h3><?= esc($activite['nom_activite']) ?></h3>
                <p><?= substr(esc($activite['description']), 0, 80) ?>...</p>
                <div class="calories">
                    <i class="fas fa-fire"></i> <?= $activite['depense_calorique_estimee'] ?> kcal/heure
                </div>
                <a href="/sports/detail/<?= $activite['id'] ?>" class="btn-outline" style="margin-top: 15px;">En savoir plus</a>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 30px;">
            <a href="/sports" class="btn-primary">Voir toutes les activités</a>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="cta-section">
        <div class="cta-content">
            <h2>Prêt à commencer votre transformation ?</h2>
            <p>Rejoignez des milliers d'utilisateurs qui ont atteint leurs objectifs avec NutriGoal</p>
            <a href="/register" class="btn-primary btn-large">Créer mon compte gratuitement</a>
        </div>
    </div>
</div>

<style>
    .hero {
        padding: 80px 0 60px;
        background: linear-gradient(135deg, #F9F6F0 0%, #E9F0E6 100%);
    }
    .hero-grid {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 48px;
        flex-wrap: wrap;
    }
    .hero-content {
        flex: 1;
    }
    .hero-content h1 {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 20px;
    }
    .hero-content p {
        font-size: 1.2rem;
        color: var(--text-muted);
        margin-bottom: 32px;
    }
    .hero-buttons {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }
    .btn-large {
        padding: 14px 36px;
        font-size: 1rem;
    }
    .hero-stats {
        display: flex;
        gap: 32px;
        margin-top: 40px;
    }
    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary);
    }
    .hero-image {
        flex: 1;
        background: var(--primary-light);
        border-radius: var(--radius-card);
        padding: 40px;
        text-align: center;
        box-shadow: var(--shadow-md);
    }
    .section {
        padding: 60px 0;
    }
    .section-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 16px;
        text-align: center;
    }
    .section-sub {
        text-align: center;
        color: var(--text-muted);
        margin-bottom: 48px;
    }
    .cards-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 32px;
        justify-content: center;
    }
    .card {
        background: var(--white);
        border-radius: var(--radius-card);
        padding: 28px;
        flex: 1;
        min-width: 260px;
        box-shadow: var(--shadow-sm);
        transition: all 0.25s ease;
        text-align: center;
    }
    .card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-md);
    }
    .card i {
        font-size: 2.5rem;
        color: var(--primary);
        margin-bottom: 20px;
    }
    .badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        display: inline-block;
        margin-top: 10px;
    }
    .badge-augmenter { background: #2ecc71; color: white; }
    .badge-reduire { background: #e74c3c; color: white; }
    .badge-maintenir { background: #3498db; color: white; }
    .calories {
        margin-top: 10px;
        color: var(--primary);
        font-weight: 600;
    }
    .cta-section {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: var(--radius-card);
        padding: 60px 40px;
        text-align: center;
        margin: 60px 0;
        color: white;
    }
    .cta-section h2 {
        font-size: 2rem;
        margin-bottom: 16px;
    }
    .cta-section .btn-primary {
        background: white;
        color: var(--primary);
        margin-top: 20px;
    }
</style>

<?= $this->endSection() ?>
<?= $this->include('layout/footer') ?>