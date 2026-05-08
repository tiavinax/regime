<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="form-card">
    <h1 class="form-title">Votre profil santé</h1>
    <p class="form-subtitle">Étape 2 sur 2 — Calculons votre IMC</p>
    
    <div class="wizard-steps">
        <div class="step completed">
            <div class="step-circle"><i class="fas fa-check"></i></div>
            <div class="step-label">Identité</div>
        </div>
        <div class="step active">
            <div class="step-circle">2</div>
            <div class="step-label">Santé & objectif</div>
        </div>
    </div>
    
    <form action="/register/step2" method="POST" id="registerForm2">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label for="taille_cm">Taille (cm) *</label>
            <input type="number" id="taille_cm" name="taille_cm" step="0.1" required>
        </div>
        
        <div class="form-group">
            <label for="poids_kg">Poids (kg) *</label>
            <input type="number" id="poids_kg" name="poids_kg" step="0.1" required>
        </div>
        
        <div class="form-group">
            <label for="niveau_activite">Niveau d'activité physique *</label>
            <select id="niveau_activite" name="niveau_activite" required>
                <option value="">Sélectionnez</option>
                <option value="sedentaire">Sédentaire (peu ou pas d'exercice)</option>
                <option value="leger">Légèrement actif (1-3j/semaine)</option>
                <option value="modere">Modérément actif (3-5j/semaine)</option>
                <option value="actif">Très actif (6-7j/semaine)</option>
                <option value="extreme">Extrêmement actif (métier physique + sport)</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="type_objectif">Objectif principal *</label>
            <select id="type_objectif" name="type_objectif" required>
                <option value="">Sélectionnez</option>
                <option value="reduire_poids">Réduire mon poids</option>
                <option value="augmenter_poids">Augmenter mon poids</option>
                <option value="imc_ideal">Atteindre mon IMC idéal</option>
            </select>
        </div>
        
        <div class="form-group" id="poidsCibleGroup" style="display: none;">
            <label for="poids_cible_kg">Poids cible (kg)</label>
            <input type="number" id="poids_cible_kg" name="poids_cible_kg" step="0.1">
        </div>
        
        <div class="form-group">
            <label for="duree_souhaitee_semaines">Durée souhaitée (semaines)</label>
            <input type="number" id="duree_souhaitee_semaines" name="duree_souhaitee_semaines" placeholder="Optionnel">
        </div>
        
        <button type="submit" class="btn-primary" style="width: 100%; padding: 14px;">
            Finaliser mon inscription <i class="fas fa-check"></i>
        </button>
    </form>
</div>

<script>
// Affichage conditionnel du poids cible selon objectif
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