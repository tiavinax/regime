<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

    <style>
        .default-user{
            font-size: 13px;
        }
        .span-user {
            font-weight: bold;
        }
        .section-test {
            margin-top: 4%;
        }
    </style>

<div class="form-card" style="max-width: 480px;">
    <h1 class="form-title">Connexion</h1>
    <p class="form-subtitle">Retrouvez votre espace personnalisé</p>
    
    <?php if(session('error')): ?>
        <div style="background: #fee; color: #c0392b; padding: 12px; border-radius: 12px; margin-bottom: 24px; text-align: center;">
            <?= session('error') ?>
        </div>
    <?php endif; ?>
    
    <form action="/login" method="POST">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= old('email') ?? "admin@nutrigoal.com"; ?>" required autofocus>
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" value="password">  
        </div>
        
        <button type="submit" class="btn-primary" style="width: 100%; padding: 14px;">
            Se connecter <i class="fas fa-arrow-right"></i>
        </button>
    </form>
    
    <p style="text-align: center; margin-top: 24px;">
        Pas encore de compte ? <a href="/register" style="color: var(--primary);">S'inscrire</a>
    </p>
    <div class="section-test">
        <p class="default-user"><span class="span-user">user :</span> sophie.bernard@email.com <span class="span-user">Mdp :</span> password</p>
        <p class="default-user"><span class="span-user">admin :</span> admin@nutrigoal.com <span class="span-user">Mdp :</span> password</p>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->include('layout/footer') ?>