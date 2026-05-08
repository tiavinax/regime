<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="form-card">
    <h1 class="form-title">🎯 Définissez votre objectif</h1>
    <p class="form-subtitle">Choisissez ce que vous souhaitez accomplir</p>
    
    <form action="/objectif/doChoisir" method="POST">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label for="type_objectif">Objectif principal *</label>
            <select id="type_objectif" name="type_objectif" required>
                <option value="">Sélectionnez</option>
                <option value="reduire_poids" <?= old('type_objectif') == 'reduire_poids' ? 'selected' : '' ?>>
                    🔻 Réduire mon poids
                </option>
                <option value="augmenter_poids" <?= old('type_objectif') == 'augmenter_poids' ? 'selected' : '' ?>>
                    🔺 Augmenter mon poids
                </option>
                <option value="imc_ideal" <?= old('type_objectif') == 'imc_ideal' ? 'selected' : '' ?>>
                    ⚖️ Atteindre mon IMC idéal
                </option>
            </select>
        </div>
        
        <div class="form-group" id="poidsCibleGroup" style="display: none;">
            <label for="poids_cible_kg">Poids cible (kg)</label>
            <input type="number" id="poids_cible_kg" name="poids_cible_kg" step="0.1" value="<?= old('poids_cible_kg') ?>">
        </div>
        
        <div class="form-group">
            <label for="duree_souhaitee_semaines">Durée souhaitée (semaines)</label>
            <input type="number" id="duree_souhaitee_semaines" name="duree_souhaitee_semaines" 
                   placeholder="Optionnel - ex: 12" value="<?= old('duree_souhaitee_semaines') ?>">
            <span style="font-size: 0.75rem; color: var(--text-muted);">Laissez vide pour une suggestion automatique</span>
        </div>
        
        <button type="submit" class="btn-primary" style="width: 100%; padding: 14px;">
            Valider mon objectif <i class="fas fa-check"></i>
        </button>
    </form>
</div>

<script>
const objectifSelect = document.getElementById('type_objectif');
const poidsCibleGroup = document.getElementById('poidsCibleGroup');

objectifSelect.addEventListener('change', function() {
    if(this.value === 'imc_ideal') {
        poidsCibleGroup.style.display = 'block';
    } else {
        poidsCibleGroup.style.display = 'none';
    }
});
</script>

<?= $this->endSection() ?>
<?= $this->include('layout/footer') ?>