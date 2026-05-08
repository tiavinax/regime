<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="form-card" style="max-width: 480px;">
    <h1 class="form-title">🔐 Administration</h1>
    <p class="form-subtitle">Accès réservé aux administrateurs</p>
    
    <?php if(session()->getFlashdata('error')): ?>
        <div style="background: #fee; color: #c0392b; padding: 12px; border-radius: 12px; margin-bottom: 24px;">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    
    <form action="/admin/login" method="POST">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required autofocus>
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn-primary" style="width: 100%;">
            Se connecter <i class="fas fa-arrow-right"></i>
        </button>
    </form>
    
    <p style="text-align: center; margin-top: 24px;">
        <a href="/login" style="color: var(--primary);">← Retour au site</a>
    </p>
</div>

<?= $this->endSection() ?>
<?= $this->include('layout/footer') ?>