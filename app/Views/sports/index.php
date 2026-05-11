<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<style>
    .sport-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        padding: 40px 0;
    }
    
    .sport-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s;
        text-align: center;
    }
    
    .sport-card:hover {
        transform: translateY(-5px);
    }
    
    .sport-icon {
        background: linear-gradient(135deg, var(--primary-light), var(--primary));
        padding: 40px;
        text-align: center;
    }
    
    .sport-icon i {
        font-size: 4rem;
        color: white;
    }
    
    .sport-content {
        padding: 20px;
    }
    
    .sport-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .sport-description {
        color: var(--text-muted);
        margin-bottom: 15px;
        font-size: 0.9rem;
    }
    
    .sport-calories {
        background: #fff3e0;
        padding: 10px;
        border-radius: 12px;
        margin: 15px 0;
    }
    
    .calories-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--secondary);
    }
    
    .sport-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .badge-reduire {
        background: #e74c3c20;
        color: #e74c3c;
    }
    
    .badge-augmenter {
        background: #2ecc7120;
        color: #27ae60;
    }
    
    .badge-maintenir {
        background: #3498db20;
        color: #2980b9;
    }
    
    .btn-sport {
        background: var(--secondary);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 30px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: 0.2s;
    }
    
    .btn-sport:hover {
        background: var(--secondary-dark);
        transform: scale(1.05);
    }
</style>

<div class="container">
    <div class="page-header">
        <h1>🏃‍♂️ Activités Sportives</h1>
        <p>Complétez votre programme avec nos activités recommandées</p>
    </div>
    
    <div class="sport-grid">
        <?php foreach($activites as $activite): ?>
        <div class="sport-card">
            <div class="sport-icon">
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
            
            <div class="sport-content">
                <span class="sport-badge 
                    <?= $activite['type_cible'] === 'reduire' ? 'badge-reduire' : ($activite['type_cible'] === 'augmenter' ? 'badge-augmenter' : 'badge-maintenir') ?>">
                    <?php if($activite['type_cible'] === 'reduire'): ?>
                        🔻 Brûle-graisse
                    <?php elseif($activite['type_cible'] === 'augmenter'): ?>
                        💪 Prise de muscle
                    <?php else: ?>
                        ⚖️ Bien-être
                    <?php endif; ?>
                </span>
                
                <h3 class="sport-title"><?= esc($activite['nom_activite']) ?></h3>
                <p class="sport-description"><?= esc(substr($activite['description'] ?? 'Description non disponible', 0, 80)) ?>...</p>
                
                <div class="sport-calories">
                    <div class="calories-value"><?= number_format($activite['depense_calorique_estimee'] ?? 0) ?></div>
                    <div>kcal / heure</div>
                </div>
                
                <a href="/sports/<?= $activite['id'] ?>" class="btn-sport">
                    <i class="fas fa-info-circle"></i> Voir les détails
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->include('layout/footer') ?>