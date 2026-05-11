<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<style>
    .sport-detail {
        max-width: 1000px;
        margin: 40px auto;
        background: white;
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .sport-header {
        background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
        padding: 50px;
        text-align: center;
        color: white;
    }
    
    .sport-icon-large {
        font-size: 5rem;
        margin-bottom: 20px;
    }
    
    .sport-header h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }
    
    .sport-badge-large {
        display: inline-block;
        padding: 8px 25px;
        border-radius: 30px;
        font-size: 1rem;
        font-weight: 600;
        margin-top: 15px;
    }
    
    .sport-body {
        padding: 40px;
    }
    
    .calories-card {
        background: linear-gradient(135deg, #fff3e0, #ffe8cc);
        padding: 30px;
        border-radius: 24px;
        text-align: center;
        margin-bottom: 30px;
    }
    
    .calories-value-large {
        font-size: 4rem;
        font-weight: 800;
        color: var(--secondary-dark);
    }
    
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }
    
    .detail-item {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 20px;
        text-align: center;
    }
    
    .detail-item i {
        font-size: 2rem;
        color: var(--secondary);
        margin-bottom: 10px;
    }
    
    .detail-label {
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    
    .detail-value {
        font-size: 1.3rem;
        font-weight: 700;
        margin-top: 5px;
    }
    
    .benefits-section {
        background: #e8f5e9;
        padding: 25px;
        border-radius: 20px;
        margin: 30px 0;
    }
    
    .benefits-section h3 {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .benefits-list {
        list-style: none;
        padding: 0;
    }
    
    .benefits-list li {
        padding: 8px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .benefits-list li i {
        color: #27ae60;
    }
    
    .programme-example {
        background: #e3f2fd;
        padding: 25px;
        border-radius: 20px;
        margin: 30px 0;
    }
    
    .programme-example h3 {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .btn-retour-sport {
        background: transparent;
        border: 2px solid var(--secondary);
        color: var(--secondary);
        padding: 12px 30px;
        border-radius: 50px;
        text-decoration: none;
        display: inline-block;
        transition: 0.3s;
    }
    
    .btn-retour-sport:hover {
        background: var(--secondary);
        color: white;
    }
    
    .btn-programme {
        background: var(--secondary);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        text-decoration: none;
        display: inline-block;
        margin-left: 15px;
        transition: 0.3s;
    }
    
    .btn-programme:hover {
        background: var(--secondary-dark);
        transform: scale(1.05);
    }
    
    @media (max-width: 768px) {
        .sport-header h1 { font-size: 1.8rem; }
        .calories-value-large { font-size: 3rem; }
        .sport-body { padding: 20px; }
    }
</style>

<div class="container">
    <div class="sport-detail">
        <div class="sport-header">
            <div class="sport-icon-large">
                <?php if(strpos($activite['nom_activite'], 'Cardio') !== false): ?>
                    <i class="fas fa-running"></i>
                <?php elseif(strpos($activite['nom_activite'], 'Musculation') !== false): ?>
                    <i class="fas fa-dumbbell"></i>
                <?php elseif(strpos($activite['nom_activite'], 'Marche') !== false): ?>
                    <i class="fas fa-walking"></i>
                <?php elseif(strpos($activite['nom_activite'], 'Natation') !== false): ?>
                    <i class="fas fa-swimmer"></i>
                <?php elseif(strpos($activite['nom_activite'], 'Vélo') !== false): ?>
                    <i class="fas fa-bicycle"></i>
                <?php else: ?>
                    <i class="fas fa-heartbeat"></i>
                <?php endif; ?>
            </div>
            <h1><?= esc($activite['nom_activite']) ?></h1>
            <p><?= esc($activite['description']) ?></p>
            <span class="sport-badge-large badge-<?= $activite['type_cible'] ?>">
                <?php if($activite['type_cible'] === 'reduire'): ?>
                    🔻 Activité brûle-graisse
                <?php elseif($activite['type_cible'] === 'augmenter'): ?>
                    💪 Activité prise de muscle
                <?php else: ?>
                    ⚖️ Activité bien-être
                <?php endif; ?>
            </span>
        </div>
        
        <div class="sport-body">
            <!-- Calories -->
            <div class="calories-card">
                <div class="calories-value-large">
                    <?= number_format($activite['depense_calorique_estimee'] ?? 0) ?>
                </div>
                <div>kcal brûlées par heure</div>
                <div style="margin-top: 10px; font-size: 0.9rem;">
                    ⏱️ Équivalent à environ 
                    <?= round(($activite['depense_calorique_estimee'] ?? 0) / 100, 1) ?> 
                    pommes 🍎
                </div>
            </div>
            
            <!-- Détails -->
            <div class="details-grid">
                <div class="detail-item">
                    <i class="fas fa-clock"></i>
                    <div class="detail-label">Durée recommandée</div>
                    <div class="detail-value">30-45 min / séance</div>
                </div>
                <div class="detail-item">
                    <i class="fas fa-calendar-week"></i>
                    <div class="detail-label">Fréquence</div>
                    <div class="detail-value">3-5 fois / semaine</div>
                </div>
                <div class="detail-item">
                    <i class="fas fa-chart-line"></i>
                    <div class="detail-label">Niveau</div>
                    <div class="detail-value">
                        <?php if($activite['type_cible'] === 'reduire'): ?>
                            Intermédiaire
                        <?php elseif($activite['type_cible'] === 'augmenter'): ?>
                            Avancé
                        <?php else: ?>
                            Débutant
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Bienfaits -->
            <div class="benefits-section">
                <h3><i class="fas fa-check-circle"></i> Bienfaits de cette activité</h3>
                <ul class="benefits-list">
                    <?php if($activite['type_cible'] === 'reduire'): ?>
                        <li><i class="fas fa-fire"></i> Brûle efficacement les calories</li>
                        <li><i class="fas fa-heart"></i> Améliore la santé cardiovasculaire</li>
                        <li><i class="fas fa-charging-station"></i> Augmente l'endurance</li>
                    <?php elseif($activite['type_cible'] === 'augmenter'): ?>
                        <li><i class="fas fa-dumbbell"></i> Développe la masse musculaire</li>
                        <li><i class="fas fa-bone"></i> Renforce les os et articulations</li>
                        <li><i class="fas fa-chart-line"></i> Améliore la force et la puissance</li>
                    <?php else: ?>
                        <li><i class="fas fa-leaf"></i> Réduit le stress et l'anxiété</li>
                        <li><i class="fas fa-heartbeat"></i> Améliore la santé globale</li>
                        <li><i class="fas fa-bed"></i> Meilleure qualité de sommeil</li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <!-- Programme d'exemple -->
            <div class="programme-example">
                <h3><i class="fas fa-calendar-alt"></i> Programme type sur une semaine</h3>
                <ul class="benefits-list">
                    <li><i class="fas fa-check-circle" style="color: #2980b9;"></i> Lundi : 30 min <?= esc($activite['nom_activite']) ?></li>
                    <li><i class="fas fa-check-circle" style="color: #2980b9;"></i> Mercredi : 45 min <?= esc($activite['nom_activite']) ?></li>
                    <li><i class="fas fa-check-circle" style="color: #2980b9;"></i> Vendredi : 30 min <?= esc($activite['nom_activite']) ?></li>
                    <li><i class="fas fa-check-circle" style="color: #2980b9;"></i> Samedi : 1h de repos actif (marche)</li>
                </ul>
            </div>
            
            <!-- Conseils -->
            <div style="background: #fce4ec; padding: 25px; border-radius: 20px; margin: 30px 0;">
                <h3><i class="fas fa-lightbulb"></i> Conseils pour bien démarrer</h3>
                <ul class="benefits-list">
                    <li><i class="fas fa-water"></i> Hydratez-vous avant, pendant et après</li>
                    <li><i class="fas fa-shoe-prints"></i> Portez des vêtements adaptés</li>
                    <li><i class="fas fa-chart-simple"></i> Augmentez l'intensité progressivement</li>
                    <li><i class="fas fa-sleep"></i> Accordez-vous des jours de repos</li>
                </ul>
            </div>
            
            <div style="text-align: center;">
                <a href="/sports" class="btn-retour-sport">
                    <i class="fas fa-arrow-left"></i> Retour aux activités
                </a>
                <?php if(session()->has('user_id')): ?>
                    <a href="/dashboard" class="btn-programme">
                        <i class="fas fa-plus-circle"></i> Ajouter à mon programme
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .badge-reduire { background: #e74c3c; color: white; }
    .badge-augmenter { background: #27ae60; color: white; }
    .badge-maintenir { background: #3498db; color: white; }
</style>

<?= $this->endSection() ?>
<?= $this->include('layout/footer') ?>