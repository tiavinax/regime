<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="container" style="padding: 40px 0;">

    <!-- Message de succès -->
    <?php if (session('success')): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; border-left: 4px solid #28a745;">
            <?= session('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session('error')): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <!-- En-tête avec bienvenue -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap;">
        <div>
            <h1>Bonjour, <?= $user['nom'] ?? 'Utilisateur' ?> 👋</h1>
            <p style="color: var(--text-muted);">Voici votre tableau de bord personnalisé</p>
        </div>
        <div>
            <?php if (isset($isGold) && $isGold): ?>
                <span style="background: linear-gradient(135deg, #F4A261, #E76F51); color: white; padding: 8px 20px; border-radius: 40px;">
                    <i class="fas fa-crown"></i> Membre Gold
                </span>
            <?php else: ?>
                <a href="/gold" class="btn-outline">Devenir Gold <i class="fas fa-gem"></i></a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Cartes stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">

        <!-- Carte IMC -->
        <div style="background: white; padding: 24px; border-radius: var(--radius-card); box-shadow: var(--shadow-sm);">
            <i class="fas fa-weight-scale" style="font-size: 2rem; color: var(--primary);"></i>
            <h3 style="margin: 12px 0 8px;">IMC</h3>
            <p style="font-size: 2rem; font-weight: 700;"><?= $imc ?? '0' ?></p>
            <p style="color: var(--text-muted);"><?= $interpretationImc ?? 'Non calculé' ?></p>
        </div>

        <!-- Carte Objectif -->
        <div style="background: white; padding: 24px; border-radius: var(--radius-card); box-shadow: var(--shadow-sm);">
            <i class="fas fa-bullseye" style="font-size: 2rem; color: var(--primary);"></i>
            <h3 style="margin: 12px 0 8px;">Objectif</h3>
            <?php if (isset($objectif) && $objectif): ?>
                <p style="font-weight: 600;">
                    <?php
                    $objectifTexts = [
                        'augmenter_poids' => '🔺 Augmenter mon poids',
                        'reduire_poids' => '🔻 Réduire mon poids',
                        'imc_ideal' => '⚖️ Atteindre mon IMC idéal'
                    ];
                    echo $objectifTexts[$objectif['type_objectif']] ?? $objectif['type_objectif'];
                    ?>
                </p>
                <?php if (!empty($objectif['poids_cible_kg'])): ?>
                    <p>Poids cible : <?= $objectif['poids_cible_kg'] ?> kg</p>
                <?php endif; ?>
                <?php if (!empty($objectif['duree_souhaitee_semaines'])): ?>
                    <p>Durée : <?= $objectif['duree_souhaitee_semaines'] ?> semaines</p>
                <?php endif; ?>
            <?php else: ?>
                <p style="color: var(--text-muted);">Aucun objectif défini</p>
                <a href="/objectif/choisir" class="btn-primary" style="display: inline-block; margin-top: 12px; font-size: 0.85rem;">Définir un objectif</a>
            <?php endif; ?>
        </div>

        <!-- Carte Calories -->
        <div style="background: white; padding: 24px; border-radius: var(--radius-card); box-shadow: var(--shadow-sm);">
            <i class="fas fa-fire" style="font-size: 2rem; color: var(--primary);"></i>
            <h3 style="margin: 12px 0 8px;">Calories / jour</h3>
            <?php if (isset($besoin) && $besoin): ?>
                <p style="font-size: 1.5rem; font-weight: 700;"><?= $besoin['besoin_objectif_kcal'] ?? '0' ?></p>
                <p style="color: var(--text-muted);">kcal recommandées</p>
                <small style="color: var(--text-muted);">
                    Maintien: <?= $besoin['besoin_maintien_kcal'] ?? '0' ?> kcal
                </small>
            <?php else: ?>
                <p style="color: var(--text-muted);">Calcul en cours...</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Suggestion du jour -->
    <?php if (isset($suggestion) && $suggestion && isset($regime) && $regime && isset($activite) && $activite): ?>
        <div style="background: linear-gradient(135deg, #5D9B6E 0%, #3D7A4E 100%); color: white; padding: 40px; border-radius: var(--radius-card); margin-bottom: 40px;">
            <h2 style="color: white; margin-bottom: 16px;">📋 Votre programme personnalisé</h2>
            <p style="font-size: 1.1rem; margin-bottom: 24px;"><?= $suggestion['message_personnalise'] ?? 'Programme adapté à vos objectifs' ?></p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">

                <!-- Régime -->
                <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 24px;">
                    <h3><i class="fas fa-utensils"></i> Régime : <?= $regime['nom_regime'] ?? 'Personnalisé' ?></h3>
                    <p><?= $regime['description'] ?? 'Description non disponible' ?></p>
                    <?php if (isset($regime['pourcentage_viande']) && $regime['pourcentage_viande']): ?>
                        <div style="margin-top: 12px;">
                            <p>🥩 Viande : <?= $regime['pourcentage_viande'] ?>%</p>
                            <p>🐟 Poisson : <?= $regime['pourcentage_poisson'] ?>%</p>
                            <p>🍗 Volaille : <?= $regime['pourcentage_volaille'] ?>%</p>
                        </div>
                    <?php endif; ?>
                    <p style="margin-top: 12px;">
                        💰 Prix : <?= number_format($regime['prix_journalier'] ?? 0, 2) ?> €/jour
                        <?php if (isset($suggestion['duree_recommandee_semaines'])): ?>
                            <br>📅 <?= $suggestion['duree_recommandee_semaines'] ?> semaines
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Activité -->
                <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 24px;">
                    <h3><i class="fas fa-dumbbell"></i> Activité : <?= $activite['nom_activite'] ?? 'Adaptée'; ?></h3>
                    <p><?= $activite['description'] ?? 'Description non disponible'; ?></p>
                    <p>🔥 Dépense estimée : <?= $activite['depense_calorique_estimee'] ?? '0' ?> kcal/heure</p>
                </div>
            </div>

            <div style="margin-top: 32px; display: flex; gap: 16px; flex-wrap: wrap;">
                <a href="/suggestion/refresh" class="btn-primary" style="background: white; color: var(--primary); text-decoration: none;">
                    🔄 Générer une nouvelle suggestion
                </a>
                <a href="/dashboard/export-pdf" class="btn-outline" style="border-color: white; color: white; text-decoration: none;">
                    📄 Exporter en PDF
                </a>
            </div>
        </div>
    <?php else: ?>
        <!-- Message si pas de suggestion -->
        <div style="background: white; padding: 40px; border-radius: var(--radius-card); margin-bottom: 40px; text-align: center;">
            <i class="fas fa-rocket" style="font-size: 3rem; color: var(--primary); margin-bottom: 16px;"></i>
            <h3>Votre programme personnalisé arrive bientôt !</h3>
            <p style="color: var(--text-muted); margin-bottom: 24px;">Nous préparons les meilleures suggestions pour atteindre vos objectifs.</p>
            <a href="/suggestion/refresh" class="btn-primary">Générer ma suggestion</a>
        </div>
    <?php endif; ?>

    <!-- Progression -->
    <?php if (isset($objectif) && $objectif): ?>
        <div style="background: white; padding: 32px; border-radius: var(--radius-card); box-shadow: var(--shadow-sm);">
            <h3>📊 Progression de votre objectif</h3>
            <div style="background: var(--gray-light); border-radius: 40px; height: 20px; margin: 20px 0; overflow: hidden;">
                <div style="background: var(--primary); width: <?= min(100, $progression ?? 0) ?>%; height: 100%; transition: width 0.5s;"></div>
            </div>
            <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                <p>📈 Progression estimée : <?= $progression ?? 0 ?>%</p>
                <?php if (isset($tempsRestant) && $tempsRestant !== null && $tempsRestant > 0): ?>
                    <p>⏱️ Temps restant estimé : <?= $tempsRestant ?> semaines</p>
                <?php elseif (isset($tempsRestant) && $tempsRestant !== null && $tempsRestant <= 0): ?>
                    <p>✅ Objectif bientôt atteint !</p>
                <?php endif; ?>
            </div>

            <!-- Info poids -->
            <?php if (isset($profil) && $profil): ?>
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--gray-light);">
                    <p><strong>📏 Vos mensurations actuelles :</strong></p>
                    <p>Poids : <?= $profil['poids_kg'] ?> kg | Taille : <?= $profil['taille_cm'] ?> cm</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
<?= $this->include('layout/footer') ?>