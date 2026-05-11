<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<style>
    .reset-container {
        max-width: 600px;
        margin: 60px auto;
    }
    
    .reset-card {
        background: white;
        border-radius: 24px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .reset-icon {
        font-size: 4rem;
        color: #e74c3c;
        margin-bottom: 20px;
    }
    
    .reset-title {
        font-size: 1.8rem;
        margin-bottom: 20px;
    }
    
    .reset-warning {
        background: #fff3cd;
        color: #856404;
        padding: 20px;
        border-radius: 16px;
        margin: 20px 0;
        text-align: left;
    }
    
    .btn-reset {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        width: 100%;
    }
    
    .btn-reset:hover {
        background: #c0392b;
        transform: scale(1.02);
    }
    
    .btn-cancel {
        background: #95a5a6;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 50px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        margin-top: 15px;
    }
    
    .btn-cancel:hover {
        background: #7f8c8d;
    }
</style>

<div class="container reset-container">
    <div class="reset-card">
        <div class="reset-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        
        <h2 class="reset-title">⚠️ Réinitialisation complète</h2>
        
        <p>Cette action va supprimer et recréer toute la base de données avec des données par défaut.</p>
        
        <div class="reset-warning">
            <strong><i class="fas fa-info-circle"></i> Ce qui sera réinitialisé :</strong>
            <ul style="margin-top: 10px; padding-left: 20px;">
                <li>✓ Tous les utilisateurs (admin + 4 users)</li>
                <li>✓ Tous les régimes (5 régimes)</li>
                <li>✓ Toutes les activités sportives (5 activités)</li>
                <li>✓ Tous les codes promo (15 codes)</li>
                <li>✓ Tous les wallets et transactions</li>
                <li>✓ Tous les objectifs et suggestions</li>
            </ul>
        </div>
        
        <div class="reset-warning" style="background: #f8d7da; color: #721c24;">
            <strong><i class="fas fa-trash-alt"></i> Attention !</strong>
            <p style="margin-top: 10px;">Cette action est irréversible. Toutes les données seront perdues.</p>
        </div>
        
        <form action="<?= base_url('/admin/reset/execute') ?>" method="POST" onsubmit="return confirm('⚠️ Êtes-vous ABSOLUMENT sûr de vouloir réinitialiser la base de données ? Toutes les données seront perdues !')">
            <?= csrf_field() ?>
            <button type="submit" class="btn-reset">
                <i class="fas fa-database"></i> Réinitialiser la base de données
            </button>
        </form>
        
        <a href="<?= base_url('/admin') ?>" class="btn-cancel">
            <i class="fas fa-arrow-left"></i> Annuler
        </a>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->include('layout/footer') ?>