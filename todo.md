# TODO LIST - PROJET RÉGIME ALIMENTAIRE (CodeIgniter)

## Structure MVC par fonctionnalité
Chaque bloc est indépendant : un membre de l'équipe peut prendre un bloc entier (Modèle + Vue + Contrôleur + JavaScript/AJAX)

---

## 1. MISE EN PLACE INITIALE (Déjà fait)
- [ok] Installation CodeIgniter 4
- [ok] Configuration base de données (`.env`)
- [ok] Création du script SQL (`database.sql`)
- [ok] Configuration des routes de base (`app/Config/Routes.php`)

---

## 2. AUTHENTIFICATION (FRONT OFFICE)

### 2.1 Inscription - Page 1 (Infos personnelles)
- [ok] **Modèle** : `UserModel` (validation nom, email, genre, date_naissance, password hashé)
- [ok] **Contrôleur** : `AuthController` - méthode `registerStep1()`
- [ok] **Vue** : `auth/register_step1.php` (formulaire nom/email/genre/password)
- [ok] **JS** : validation côté client (email unique, mot de passe fort)
- [ok] **Route** : `GET/POST /register/step1`

### 2.2 Inscription - Page 2 (Infos santé)
- [ok] **Modèle** : `ProfilPhysiqueModel` + `ObjectifModel`
- [ok] **Contrôleur** : `AuthController` - méthode `registerStep2()`
- [ok] **Vue** : `auth/register_step2.php` (taille, poids, niveau_activite)
- [ok] **JS** : pré-remplissage conditionnel selon objectif choisi
- [ok] **Route** : `POST /register/step2`

### 2.3 Login
- [ok] **Modèle** : `UserModel` (méthode `verifyCredentials`)
- [ok] **Contrôleur** : `AuthController` - méthode `login()`
- [ok] **Vue** : `auth/login.php`
- [ok] **Session CI4** : stockage `user_id`, `user_nom`, `is_gold`
- [ok] **Route** : `GET/POST /login`
- [ok] **Logout** : `GET /logout`

---

## 3. PROFIL UTILISATEUR & OBJECTIFS

### 3.1 Tableau de bord utilisateur (Front)
- [ ] **Modèle** : `ProfilPhysiqueModel`, `ObjectifModel`, `BesoinCaloriqueModel`
- [ ] **Contrôleur** : `DashboardController` - méthode `index()`
- [ ] **Vue** : `dashboard/index.php` (affiche IMC, objectif en cours)
- [ ] **Calcul IMC** : helper `imc_helper.php` (poids / (taille/100)²)
- [ ] **Route** : `GET /dashboard`

### 3.2 Choix des 3 objectifs
- [ ] **Modèle** : `ObjectifModel` (CRUD pour l'utilisateur)
- [ ] **Contrôleur** : `ObjectifController` - méthodes `choisir()`, `modifier()`
- [ ] **Vue** : `objectif/choisir.php` (3 radios : augmenter/réduire/IMC idéal)
- [ ] **JS** : champ `poids_cible_kg` visible uniquement si "IMC idéal"
- [ ] **Route** : `GET/POST /objectif/choisir`

### 3.3 Calcul des besoins caloriques (Métabolisme de base + NAP)
- [ ] **Modèle** : `BesoinCaloriqueModel` (stocke MB, besoin_maintien, besoin_objectif)
- [ ] **Helper** : `metabolisme_helper.php` (Harris & Benedict)
- [ ] **Contrôleur** : `BesoinController` - méthode `calculer()`
- [ ] **Appel AJAX** : depuis la vue objectif (calcul sans rechargement)
- [ ] **Route** : `POST /besoin/calculer`

---

## 4. SUGGESTION RÉGIMES & ACTIVITÉS

### 4.1 Suggestion automatique
- [ ] **Modèle** : `SuggestionModel`, `RegimeModel`, `ActiviteModel`
- [ ] **Contrôleur** : `SuggestionController` - méthode `generer()`
- [ ] **Logique métier** :
  - Si besoin_objectif >> besoin_maintien → régime augmenter
  - Si besoin_objectif << besoin_maintien → régime réduire
  - Sinon → régime maintenir
- [ ] **Vue** : `suggestion/resultat.php` (liste régime + activité)
- [ ] **Route** : `GET /suggestion/resultat/{id_besoin}`

### 4.2 Export PDF
- [ ] **Librairie** : `dompdf` ou `mpdf`
- [ ] **Contrôleur** : `ExportController` - méthode `pdf()`
- [ ] **Vue PDF** : `export/suggestion_pdf.php` (template simplifié)
- [ ] **Route** : `GET /export/pdf/{id_suggestion}`

---

## 5. PORTE-MONNAIE & CODE PROMO

### 5.1 Ajouter argent avec un code
- [ ] **Modèle** : `CodePromoModel` (`code`, `valeur`, `utilise`, `valide`)
- [ ] **Base données** : table `code_promo` (id, code, valeur, est_utilise, id_utilisateur)
- [ ] **Contrôleur** : `WalletController` - méthode `ajouterCode()`
- [ ] **Vue** : `wallet/index.php` (montant actuel + champ code)
- [ ] **JS AJAX** : vérification code et ajout auto du montant
- [ ] **Route** : `POST /wallet/ajouter-code`

### 5.2 Afficher solde
- [ ] **Modèle** : `WalletModel` (somme des transactions)
- [ ] **Table** : `transaction` (id, id_utilisateur, montant, type, date)
- [ ] **Vue** : affichage solde sur dashboard et navbar
- [ ] **Route** : `GET /wallet/solde` (AJAX ou direct)

---

## 6. OPTION GOLD

### 6.1 Paiement unique (prix à définir, ex: 9.99€)
- [ ] **Modèle** : `GoldModel` (vérifier si utilisateur déjà gold)
- [ ] **Contrôleur** : `GoldController` - méthode `paiement()`
- [ ] **Vue** : `gold/paiement.php` (simulation de paiement)
- [ ] **Déduction solde** : utiliser `WalletModel->debiter()`
- [ ] **Flag gold** : champ `is_gold` dans table `utilisateur`
- [ ] **Route** : `GET/POST /gold/acheter`

### 6.2 Application remise 15% sur régimes
- [ ] **Modèle** : `RegimeModel` - méthode `getPrixAvecRemise($id_regime, $is_gold)`
- [ ] **Helper** : `prix_helper.php`
- [ ] **Vue suggestion** : afficher prix normal ET remisé si gold
- [ ] **Check partout** : session `is_gold` à transmettre aux vues

---

## 7. BACK OFFICE (Authentification admin)

### 7.1 Login Admin
- [ ] **Table admin** : `administrateur` (id, email, password)
- [ ] **Contrôleur** : `AdminAuthController` - méthode `login()`
- [ ] **Vue** : `admin/login.php`
- [ ] **Route** : `/admin/login`

### 7.2 Tableau de bord avec graphiques
- [ ] **Contrôleur** : `AdminDashboardController`
- [ ] **Statistiques** :
  - Nombre utilisateurs par objectif
  - Répartition IMC (sous-poids/normal/surpoids/obèse)
  - Top 3 régimes suggérés
- [ ] **Lib graphique** : Chart.js ou Google Charts
- [ ] **Vue** : `admin/dashboard.php`
- [ ] **Route** : `GET /admin`

---

## 8. CRUD RÉGIMES (Back Office)

### 8.1 Liste, création, modification, suppression
- [ ] **Modèle** : `RegimeModel` (validation, champs : nom, description, type_cible, prix_journalier, variation_poids_semaine)
- [ ] **Table régime** ajouter : `prix_journalier DECIMAL(6,2)`, `variation_poids_kg DECIMAL(3,2)`
- [ ] **Contrôleur** : `AdminRegimeController` - méthodes index/create/edit/delete
- [ ] **Vues** : `admin/regime/index.php`, `create.php`, `edit.php`
- [ ] **Route CRUD** : `GET|POST /admin/regime/*`

### 8.2 Spécificité : % viande, poisson, volaille
- [ ] **Table** : `regime_composition` (id_regime, pourcentage_viande, pourcentage_poisson, pourcentage_volaille)
- [ ] **Modèle** : `RegimeCompositionModel`
- [ ] **Vue** : dans create/edit regime → champs pourcentages (somme=100)

---

## 9. CRUD ACTIVITÉS SPORTIVES (Back Office)

### 9.1 CRUD complet
- [ ] **Modèle** : `ActiviteModel`
- [ ] **Table** : `activite_sportive` (id, nom, description, type_cible, depense_calorique_estimee)
- [ ] **Contrôleur** : `AdminActiviteController`
- [ ] **Vues** : `admin/activite/index.php`, `create.php`, `edit.php`
- [ ] **Routes** : `GET|POST /admin/activite/*`

---

## 10. VALIDATION CODES PORTO-MONNAIE (Back Office)

### 10.1 CRUD Codes promo
- [ ] **Modèle** : `CodePromoModel` (admin peut créer/modifier/supprimer)
- [ ] **Contrôleur** : `AdminCodeController`
- [ ] **Vues** : `admin/code/index.php`, `create.php`
- [ ] **Route** : `POST /admin/code/valider` (si validation manuelle)

---

## 11. CRUD PARAMÈTRES

### 11.1 Paramètres système
- [ ] **Table** : `parametre` (id, cle, valeur, description)
- [ ] **Exemples** :
  - `prix_gold` : 9.99
  - `remise_gold_pct` : 15
  - `duree_suggestion_default` : 12 (semaines)
- [ ] **Modèle** : `ParametreModel`
- [ ] **Contrôleur** : `AdminParametreController`
- [ ] **Vues** : `admin/parametre/index.php`, `edit.php`
- [ ] **Route** : `GET|POST /admin/parametre`

---

## 12. DONNÉES MINIMALES À INSÉRER (Script SQL)

### 12.1 Données de test
- [ ] 5 utilisateurs (dont 1 gold)
- [ ] 15 codes promo (dont 5 déjà utilisés)
- [ ] 5 régimes chacun avec ses % viande/poisson/volaille
- [ ] 5 activités sportives

---

## 13. BONUS TECHNIQUES

### 13.1 AJAX partout où pertinent
- [ ] Vérification email existant (inscription)
- [ ] Validation code promo
- [ ] Calcul IMC en direct
- [ ] Suggestion sans rechargement

### 13.2 Commits et branches
- [ ] Chaque fonctionnalité = une branche → merge dans `main`
- [ ] Commits réguliers (pas 1 seul à la fin)
- [ ] Fichier `README.md` expliquant installation

### 13.3 Google Sheet suivi tâches
- [ ] Créer le sheet avec colonnes : Tâche | Responsable | Statut | Date début | Date fin
- [ ] Lien partagé dans formulaire de livraison

---

## 14. LIVRAISON FINALE

- [ ] Formulaire Google Forms rempli
- [ ] Lien GitHub/GitLab (public ou avec accès)
- [ ] Script SQL complet (structure + données)
- [ ] Liste membres (noms, emails, GitHub usernames)
- [ ] Lien Google Sheet suivi
- [ ] Vérification que la branche `main` est à jour avec tous les merges
