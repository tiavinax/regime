<div class="navbar">
    <div class="nav-container">
        <a href="/" class="logo">Nutri<span>Goal</span></a>
        <div class="nav-links">
            <a href="/">Accueil</a>
            <a href="/regimes">Régimes</a>
            <a href="/sports">Sports</a>
            <a href="/a-propos">À propos</a>
            <?php if(session()->has('user_id')): ?>
                <a href="/dashboard">Mon profil</a>
            <?php endif; ?>
            <?php if(session()->has('isAdminLoggedIn')): ?>
                <a href="/admin" style="color: #E76F51;"><i class="fas fa-shield-alt"></i> Admin</a>
            <?php endif; ?>
        </div>
        <div class="nav-buttons">
            <?php if(session()->has('user_id')): ?>
                <span style="color: var(--primary);">👋 <?= session()->get('user_nom') ?></span>
                <a href="/dashboard" class="btn-outline">Mon profil</a>
                <a href="/logout" class="btn-outline">Déconnexion</a>
            <?php else: ?>
                <a href="/login" class="btn-outline">Se connecter</a>
                <a href="/register" class="btn-primary">S'inscrire</a>
            <?php endif; ?>
        </div>
    </div>
</div>