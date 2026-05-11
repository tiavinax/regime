<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<style>
    .wallet-container {
        max-width: 900px;
        margin: 40px auto;
    }
    
    .wallet-card {
        background: linear-gradient(135deg, #5D9B6E, #3D7A4E);
        border-radius: 32px;
        padding: 40px;
        color: white;
        text-align: center;
        margin-bottom: 30px;
    }
    
    .solde-label {
        font-size: 1rem;
        opacity: 0.9;
    }
    
    .solde-value {
        font-size: 4rem;
        font-weight: 800;
        margin: 10px 0;
    }
    
    .gold-badge-wallet {
        background: rgba(255,255,255,0.2);
        display: inline-block;
        padding: 5px 15px;
        border-radius: 30px;
        font-size: 0.85rem;
        margin-top: 10px;
    }
    
    .code-section {
        background: white;
        border-radius: 24px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .code-input-group {
        display: flex;
        gap: 15px;
        margin-top: 15px;
    }
    
    .code-input {
        flex: 1;
        padding: 15px;
        border: 2px solid #e0e0e0;
        border-radius: 50px;
        font-size: 1rem;
        text-transform: uppercase;
    }
    
    .btn-validate {
        background: var(--primary);
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
    }
    
    .transactions-table {
        background: white;
        border-radius: 24px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .transaction-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .transaction-credit {
        color: #27ae60;
    }
    
    .transaction-debit {
        color: #e74c3c;
    }
    
    .btn-ajouter {
        background: var(--secondary);
        color: white;
        padding: 12px 25px;
        border-radius: 50px;
        text-decoration: none;
        display: inline-block;
    }
</style>

<div class="container wallet-container">
    <!-- Carte solde -->
    <div class="wallet-card">
        <div class="solde-label">💰 Votre porte-monnaie</div>
        <div class="solde-value"><?= number_format($solde, 2) ?> €</div>
        <?php if($isGold): ?>
            <div class="gold-badge-wallet">
                <i class="fas fa-crown"></i> Membre Gold -15% sur tous les régimes
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Ajouter un code promo -->
    <div class="code-section">
        <h3><i class="fas fa-ticket-alt"></i> Code promo</h3>
        <p>Entrez votre code pour ajouter de l'argent</p>
        <div class="code-input-group">
            <input type="text" id="codePromo" class="code-input" placeholder="Ex: BIENVENUE10">
            <button id="btnAppliquer" class="btn-validate">
                <i class="fas fa-check"></i> Appliquer
            </button>
        </div>
        <div id="messageCode" style="margin-top: 15px;"></div>
    </div>
    
    <!-- Historique des transactions -->
    <div class="transactions-table">
        <h3><i class="fas fa-history"></i> Historique</h3>
        <?php if(empty($transactions)): ?>
            <p style="text-align: center; color: var(--text-muted); padding: 30px;">
                Aucune transaction pour le moment
            </p>
        <?php else: ?>
            <?php foreach($transactions as $trans): ?>
                <div class="transaction-item">
                    <div>
                        <strong><?= esc($trans['description']) ?></strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                            <?= date('d/m/Y H:i', strtotime($trans['date_transaction'])) ?>
                        </div>
                    </div>
                    <div class="<?= $trans['type'] === 'credit' ? 'transaction-credit' : 'transaction-debit' ?>">
                        <?= $trans['type'] === 'credit' ? '+' : '-' ?> <?= number_format($trans['montant'], 2) ?> €
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="/dashboard" class="btn-ajouter">
            <i class="fas fa-arrow-left"></i> Retour au tableau de bord
        </a>
    </div>
</div>

<script>
document.getElementById('btnAppliquer').addEventListener('click', function() {
    const code = document.getElementById('codePromo').value;
    const messageDiv = document.getElementById('messageCode');
    const btn = this;
    
    if(!code) {
        messageDiv.innerHTML = '<div style="color: #e74c3c;">Veuillez entrer un code</div>';
        return;
    }
    
    // Désactiver le bouton pendant le traitement
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Vérification...';
    messageDiv.innerHTML = '';
    
    fetch('/wallet/appliquer-code', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'code=' + encodeURIComponent(code)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            messageDiv.innerHTML = '<div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 12px;">' + data.message + '</div>';
            // Recharger la page après 2 secondes pour voir le nouveau solde
            setTimeout(() => location.reload(), 2000);
        } else {
            messageDiv.innerHTML = '<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 12px;">' + data.message + '</div>';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Appliquer';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        messageDiv.innerHTML = '<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 12px;">Erreur lors de l\'application du code</div>';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Appliquer';
    });
});
</script>

<?= $this->endSection() ?>
<?= $this->include('layout/footer') ?>