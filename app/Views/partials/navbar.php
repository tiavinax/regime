<div class="sidebar">
    <a href="/admin" class="logo">Nutri<span>Goal</span></a>
    <nav>
        <a href="/admin"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="/admin/regimes"><i class="fas fa-utensils"></i> Régimes</a>
        <a href="/admin/activites" class="active"><i class="fas fa-running"></i> Activités</a>
        <a href="/admin/users"><i class="fas fa-users"></i> Utilisateurs</a>
        <a href="/admin/codes"><i class="fas fa-ticket-alt"></i> Codes promo</a>
        <a href="/admin/parametres"><i class="fas fa-cog"></i> Paramètres</a>
        <div style="margin-top: 30px; text-align: center;">
            <a href="<?= base_url('/admin/reset') ?>" class="btn-outline" style="background: #e74c3c; color: white; border-color: #e74c3c;">
                <i class="fas fa-database"></i> Reset SQL
            </a>
        </div>
    </nav>
</div>