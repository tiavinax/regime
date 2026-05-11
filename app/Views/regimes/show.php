<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<style>
    .regime-detail {
        max-width: 1200px;
        margin: 40px auto;
        background: white;
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .regime-header {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        padding: 40px;
        color: white;
        position: relative;
    }

    .regime-header h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .regime-badge {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-top: 15px;
    }

    .badge-reduire {
        background: #e74c3c;
        color: white;
    }

    .badge-augmenter {
        background: #27ae60;
        color: white;
    }

    .badge-maintenir {
        background: #3498db;
        color: white;
    }

    .regime-body {
        padding: 40px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .info-card {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 20px;
        text-align: center;
    }

    .info-card i {
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .info-card .label {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 5px;
    }

    .info-card .value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .composition-section {
        background: #fff8f0;
        padding: 30px;
        border-radius: 24px;
        margin: 30px 0;
    }

    .composition-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .composition-bars {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .comp-bar-item {
        flex: 1;
        min-width: 150px;
    }

    .comp-bar-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .comp-bar {
        background: #e0e0e0;
        height: 30px;
        border-radius: 15px;
        overflow: hidden;
    }

    .comp-bar-fill {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 10px;
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .fill-viande {
        background: #e74c3c;
    }

    .fill-poisson {
        background: #3498db;
    }

    .fill-volaille {
        background: #f39c12;
    }

    .price-section {
        background: linear-gradient(135deg, #f9f6f0, #fff);
        padding: 30px;
        border-radius: 24px;
        margin: 30px 0;
        text-align: center;
        border: 2px solid var(--primary-light);
    }

    .price-original {
        font-size: 1.2rem;
        color: var(--text-muted);
        text-decoration: line-through;
    }

    .price-gold {
        font-size: 2rem;
        font-weight: 700;
        color: #e67e22;
    }

    .price-normal {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary);
    }

    .btn-acheter {
        background: var(--primary);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        margin-top: 20px;
        transition: 0.3s;
    }

    .btn-acheter:hover {
        background: var(--primary-dark);
        transform: scale(1.05);
    }

    .btn-retour {
        background: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
        padding: 12px 30px;
        border-radius: 50px;
        text-decoration: none;
        display: inline-block;
        margin-right: 15px;
        transition: 0.3s;
    }

    .btn-retour:hover {
        background: var(--primary);
        color: white;
    }

    .duree-select {
        padding: 10px 15px;
        border-radius: 30px;
        border: 1px solid var(--gray-mid);
        margin: 0 10px;
    }

    @media (max-width: 768px) {
        .regime-header h1 {
            font-size: 1.8rem;
        }

        .regime-body {
            padding: 20px;
        }

        .composition-bars {
            flex-direction: column;
        }
    }
</style>

<div class="container">
    <!-- Affichage des messages -->
    <?php if (session('success')): ?>
        <div style="background: #d4edda; color: #155724; padding: 20px; border-radius: 16px; margin-bottom: 20px; border-left: 4px solid #28a745;">
            <?= session('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session('error')): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 20px; border-radius: 16px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
            <?= session('error') ?>
        </div>
    <?php endif; ?>
    <div class="regime-detail">
        <div class="regime-header">
            <h1><?= esc($regime['nom_regime']) ?></h1>
            <p><?= esc($regime['description']) ?></p>
            <span class="regime-badge 
                <?= $regime['type_cible'] === 'reduire' ? 'badge-reduire' : ($regime['type_cible'] === 'augmenter' ? 'badge-augmenter' : 'badge-maintenir') ?>">
                <?php if ($regime['type_cible'] === 'reduire'): ?>
                    🔻 Programme Perte de Poids
                <?php elseif ($regime['type_cible'] === 'augmenter'): ?>
                    💪 Programme Prise de Masse
                <?php else: ?>
                    ⚖️ Programme Équilibre
                <?php endif; ?>
            </span>
        </div>

        <div class="regime-body">
            <!-- Informations clés -->
            <div class="info-grid">
                <div class="info-card">
                    <i class="fas fa-calendar-week"></i>
                    <div class="label">Durée recommandée</div>
                    <div class="value">8-12 semaines</div>
                </div>
                <div class="info-card">
                    <i class="fas fa-chart-line"></i>
                    <div class="label">Variation/semaine</div>
                    <div class="value"><?= number_format($regime['variation_poids_semaine'] ?? 0.5, 1) ?> kg</div>
                </div>
                <div class="info-card">
                    <i class="fas fa-fire"></i>
                    <div class="label">Apport calorique</div>
                    <div class="value"><?= number_format($regime['apport_calorique_reference'] ?? 2000, 0) ?> kcal</div>
                </div>
            </div>

            <!-- Composition nutritionnelle -->
            <?php if (isset($regime['pourcentage_viande']) || isset($regime['pourcentage_poisson']) || isset($regime['pourcentage_volaille'])): ?>
                <div class="composition-section">
                    <div class="composition-title">
                        <i class="fas fa-chart-pie"></i>
                        Composition nutritionnelle recommandée
                    </div>
                    <div class="composition-bars">
                        <?php if (isset($regime['pourcentage_viande']) && $regime['pourcentage_viande'] > 0): ?>
                            <div class="comp-bar-item">
                                <div class="comp-bar-label">
                                    <span>🥩 Viande</span>
                                    <span><?= $regime['pourcentage_viande'] ?>%</span>
                                </div>
                                <div class="comp-bar">
                                    <div class="comp-bar-fill fill-viande" style="width: <?= $regime['pourcentage_viande'] ?>%">
                                        <?= $regime['pourcentage_viande'] > 15 ? $regime['pourcentage_viande'] . '%' : '' ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($regime['pourcentage_poisson']) && $regime['pourcentage_poisson'] > 0): ?>
                            <div class="comp-bar-item">
                                <div class="comp-bar-label">
                                    <span>🐟 Poisson</span>
                                    <span><?= $regime['pourcentage_poisson'] ?>%</span>
                                </div>
                                <div class="comp-bar">
                                    <div class="comp-bar-fill fill-poisson" style="width: <?= $regime['pourcentage_poisson'] ?>%">
                                        <?= $regime['pourcentage_poisson'] > 15 ? $regime['pourcentage_poisson'] . '%' : '' ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($regime['pourcentage_volaille']) && $regime['pourcentage_volaille'] > 0): ?>
                            <div class="comp-bar-item">
                                <div class="comp-bar-label">
                                    <span>🍗 Volaille</span>
                                    <span><?= $regime['pourcentage_volaille'] ?>%</span>
                                </div>
                                <div class="comp-bar">
                                    <div class="comp-bar-fill fill-volaille" style="width: <?= $regime['pourcentage_volaille'] ?>%">
                                        <?= $regime['pourcentage_volaille'] > 15 ? $regime['pourcentage_volaille'] . '%' : '' ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Section prix -->
            <div class="price-section">
                <h3>💰 Tarifs</h3>
                <div class="price-normal">
                    <?= number_format($regime['prix_journalier'] ?? 5.99, 2) ?> € <small>/ jour</small>
                </div>

                <?php if ($isGold): ?>
                    <div class="price-gold">
                        <i class="fas fa-crown"></i> Prix Gold : <?= number_format(($regime['prix_journalier'] ?? 5.99) * 0.85, 2) ?> € <small>/ jour (-15%)</small>
                    </div>
                <?php endif; ?>

                <div style="margin-top: 20px;">
                    <label for="duree">📅 Choisissez la durée :</label>
                    <select id="duree" class="duree-select" onchange="calculerPrix()">
                        <option value="4">4 semaines</option>
                        <option value="8" selected>8 semaines</option>
                        <option value="12">12 semaines</option>
                        <option value="16">16 semaines</option>
                    </select>
                </div>

                <div id="prixTotal" style="margin-top: 20px; font-size: 1.2rem;">
                    Prix total : <strong id="totalAmount">0</strong> €
                </div>

                <?php if (session()->has('user_id')): ?>
                    <form action="<?= base_url('/wallet/acheter-regime') ?>" method="POST" id="achatForm">
                        <?= csrf_field() ?>
                        <input type="hidden" name="regime_id" value="<?= $regime['id'] ?>">
                        <input type="hidden" name="duree_semaines" id="dureeInput" value="8">
                        <button type="submit" class="btn-acheter">
                            <i class="fas fa-shopping-cart"></i> Acheter avec mon porte-monnaie
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?= base_url('/login') ?>" class="btn-acheter" style="display: inline-block; text-decoration: none;">
                        <i class="fas fa-sign-in-alt"></i> Connectez-vous pour acheter
                    </a>
                <?php endif; ?>
            </div>

            <script>
                const prixJournalier = <?= $regime['prix_journalier'] ?? 5.99 ?>;
                const isGold = <?= $isGold ? 'true' : 'false' ?>;
                const prixGold = prixJournalier * 0.85;

                function calculerPrix() {
                    const duree = document.getElementById('duree').value;
                    const prix = isGold ? prixGold : prixJournalier;
                    const total = prix * duree * 7;
                    document.getElementById('totalAmount').innerText = total.toFixed(2);
                    document.getElementById('dureeInput').value = duree;
                }

                calculerPrix();
            </script>

            <?= $this->endSection() ?>
            <?= $this->include('layout/footer') ?>