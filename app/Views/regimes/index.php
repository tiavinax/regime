<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<style>
    .regime-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
        padding: 40px 0;
    }
    
    .regime-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .regime-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    
    .regime-image {
        height: 200px;
        background-size: cover;
        background-position: center;
        position: relative;
    }
    
    .regime-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--primary);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .regime-content {
        padding: 20px;
    }
    
    .regime-title {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--text-dark);
    }
    
    .regime-description {
        color: var(--text-muted);
        margin-bottom: 15px;
        line-height: 1.5;
    }
    
    .regime-composition {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 12px;
        margin: 15px 0;
        display: flex;
        justify-content: space-around;
    }
    
    .comp-item {
        text-align: center;
    }
    
    .comp-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary);
    }
    
    .comp-label {
        font-size: 0.75rem;
        color: var(--text-muted);
    }
    
    .regime-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }
    
    .regime-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
    }
    
    .price-small {
        font-size: 0.8rem;
        font-weight: normal;
        color: var(--text-muted);
    }
    
    .gold-price {
        font-size: 0.9rem;
        color: #e67e22;
    }
    
    .btn-buy {
        background: var(--primary);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 30px;
        cursor: pointer;
        font-weight: 600;
        transition: 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-buy:hover {
        background: var(--primary-dark);
        transform: scale(1.05);
    }
    
    .btn-details {
        background: transparent;
        border: 1.5px solid var(--primary);
        color: var(--primary);
        padding: 8px 16px;
        border-radius: 30px;
        text-decoration: none;
        font-size: 0.85rem;
        transition: 0.2s;
    }
    
    .btn-details:hover {
        background: var(--primary);
        color: white;
    }
    
    .page-header {
        text-align: center;
        padding: 40px 0 20px;
    }
    
    .page-header h1 {
        font-size: 2.5rem;
        color: var(--text-dark);
    }
    
    .page-header p {
        color: var(--text-muted);
        font-size: 1.1rem;
    }
</style>

<div class="container">
    <div class="page-header">
        <h1>🍽️ Nos Régimes Alimentaires</h1>
        <p>Des programmes sur mesure adaptés à vos objectifs</p>
    </div>
    
    <div class="regime-grid">
        <?php foreach($regimes as $regime): ?>
        <div class="regime-card">
            <div class="regime-image" style="background-image: linear-gradient(135deg, var(--primary-light), var(--primary)), url('https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=400'); background-blend-mode: overlay;">
                <span class="regime-badge">
                    <?php if($regime['type_cible'] === 'reduire'): ?>
                        🔻 Perte de poids
                    <?php elseif($regime['type_cible'] === 'augmenter'): ?>
                        🔺 Prise de masse
                    <?php else: ?>
                        ⚖️ Équilibre
                    <?php endif; ?>
                </span>
            </div>
            
            <div class="regime-content">
                <h3 class="regime-title"><?= esc($regime['nom_regime']) ?></h3>
                <p class="regime-description"><?= esc(substr($regime['description'] ?? 'Description non disponible', 0, 100)) ?>...</p>
                
                <div class="regime-composition">
                    <?php if(isset($regime['pourcentage_viande'])): ?>
                    <div class="comp-item">
                        <div class="comp-value">🍖 <?= $regime['pourcentage_viande'] ?>%</div>
                        <div class="comp-label">Viande</div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(isset($regime['pourcentage_poisson'])): ?>
                    <div class="comp-item">
                        <div class="comp-value">🐟 <?= $regime['pourcentage_poisson'] ?>%</div>
                        <div class="comp-label">Poisson</div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(isset($regime['pourcentage_volaille'])): ?>
                    <div class="comp-item">
                        <div class="comp-value">🍗 <?= $regime['pourcentage_volaille'] ?>%</div>
                        <div class="comp-label">Volaille</div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="regime-footer">
                    <div>
                        <span class="regime-price">
                            <?= number_format($regime['prix_journalier'] ?? 5, 2) ?>€
                            <span class="price-small">/jour</span>
                        </span>
                        <?php if($isGold): ?>
                            <div class="gold-price">
                                <i class="fas fa-crown"></i> Prix Gold: <?= number_format(($regime['prix_journalier'] ?? 5) * 0.85, 2) ?>€/jour
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <a href="/regimes/<?= $regime['id'] ?>" class="btn-details">
                            <i class="fas fa-info-circle"></i> Détails
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->include('layout/footer') ?>