<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="form-card">
    <h1 class="form-title">Créer mon compte</h1>
    <p class="form-subtitle">Étape 1 sur 2 — Vos informations personnelles</p>
    
    <!-- Wizard progress -->
    <div class="wizard-steps">
        <div class="step active">
            <div class="step-circle">1</div>
            <div class="step-label">Identité</div>
        </div>
        <div class="step">
            <div class="step-circle">2</div>
            <div class="step-label">Santé</div>
        </div>
    </div>
    
    <form action="/register/step1" method="POST" id="registerForm1">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label for="nom">Nom complet *</label>
            <input type="text" id="nom" name="nom" value="<?= old('nom') ?>" required autofocus>
            <?php if(session('errors.nom')): ?>
                <span class="error"><?= session('errors.nom') ?></span>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" value="<?= old('email') ?>" required>
            <?php if(session('errors.email')): ?>
                <span class="error"><?= session('errors.email') ?></span>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="genre">Genre *</label>
            <select id="genre" name="genre" required>
                <option value="">Sélectionnez</option>
                <option value="homme" <?= old('genre') == 'homme' ? 'selected' : '' ?>>Homme</option>
                <option value="femme" <?= old('genre') == 'femme' ? 'selected' : '' ?>>Femme</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="date_naissance">Date de naissance *</label>
            <input type="date" id="date_naissance" name="date_naissance" value="<?= old('date_naissance') ?>" required>
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe *</label>
            <input type="password" id="password" name="password" required>
            <span style="font-size: 0.75rem; color: var(--text-muted);">Minimum 4 caractères</span>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe *</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <button type="submit" class="btn-primary" style="width: 100%; padding: 14px;">
            Continuer <i class="fas fa-arrow-right"></i>
        </button>
    </form>
    
    <p style="text-align: center; margin-top: 24px;">
        Déjà un compte ? <a href="/login" style="color: var(--primary);">Se connecter</a>
    </p>
</div>

<script>
// Validation simple : juste vérifier que les 2 mots de passe correspondent
const password = document.getElementById('password');
const confirmPassword = document.getElementById('confirm_password');

confirmPassword.addEventListener('input', function() {
    if(password.value !== this.value) {
        this.setCustomValidity('Les mots de passe ne correspondent pas');
    } else {
        this.setCustomValidity('');
    }
});

password.addEventListener('input', function() {
    if(confirmPassword.value !== this.value) {
        confirmPassword.setCustomValidity('Les mots de passe ne correspondent pas');
    } else {
        confirmPassword.setCustomValidity('');
    }
});
</script>

<?= $this->endSection() ?>
<?= $this->include('layout/footer') ?>