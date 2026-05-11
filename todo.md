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
-[ok] **Modèle** : `ProfilPhysiqueModel`, `ObjectifModel`, `BesoinCaloriqueModel`
-[ok] **Contrôleur** : `DashboardController` - méthode `index()`
-[ok] **Vue** : `dashboard/index.php` (affiche IMC, objectif en cours)
-[ok] **Calcul IMC** : helper `imc_helper.php` (poids / (taille/100)²)
-[ok] **Route** : `GET /dashboard`

### 3.2 Choix des 3 objectifs
-[ok] **Modèle** : `ObjectifModel` (CRUD pour l'utilisateur)
-[ok] **Contrôleur** : `ObjectifController` - méthodes `choisir()`, `modifier()`
-[ok] **Vue** : `objectif/choisir.php` (3 radios : augmenter/réduire/IMC idéal)
-[ok] **JS** : champ `poids_cible_kg` visible uniquement si "IMC idéal"
-[ok] **Route** : `GET/POST /objectif/choisir`

### 3.3 Calcul des besoins caloriques (Métabolisme de base + NAP)
-[ok] **Modèle** : `BesoinCaloriqueModel` (stocke MB, besoin_maintien, besoin_objectif)
-[ok] **Helper** : `metabolisme_helper.php` (Harris & Benedict)
-[ok] **Contrôleur** : `BesoinController` - méthode `calculer()`
-[ok] **Appel AJAX** : depuis la vue objectif (calcul sans rechargement)
-[ok] **Route** : `POST /besoin/calculer`

---

## 4. SUGGESTION RÉGIMES & ACTIVITÉS

### 4.1 Suggestion automatique
-[ok] **Modèle** : `SuggestionModel`, `RegimeModel`, `ActiviteModel`
-[ok] **Contrôleur** : `SuggestionController` - méthode `generer()`
-[ok] **Logique métier** :
  - Si besoin_objectif >> besoin_maintien → régime augmenter
  - Si besoin_objectif << besoin_maintien → régime réduire
  - Sinon → régime maintenir
-[ok] **Vue** : `suggestion/resultat.php` (liste régime + activité)
-[ok] **Route** : `GET /suggestion/resultat/{id_besoin}`

### 4.2 Export PDF
-[ok] **Librairie** : `dompdf` ou `mpdf`
-[ok] **Contrôleur** : `ExportController` - méthode `pdf()`
-[ok] **Vue PDF** : `export/suggestion_pdf.php` (template simplifié)
-[ok] **Route** : `GET /export/pdf/{id_suggestion}`

---

## 5. PORTE-MONNAIE & CODE PROMO

### 5.1 Ajouter argent avec un code
-[ok] **Modèle** : `CodePromoModel` (`code`, `valeur`, `utilise`, `valide`)
-[ok] **Base données** : table `code_promo` (id, code, valeur, est_utilise, id_utilisateur)
-[ok] **Contrôleur** : `WalletController` - méthode `ajouterCode()`
-[ok] **Vue** : `wallet/index.php` (montant actuel + champ code)
-[ok] **JS AJAX** : vérification code et ajout auto du montant
-[ok] **Route** : `POST /wallet/ajouter-code`

### 5.2 Afficher solde
-[ok] **Modèle** : `WalletModel` (somme des transactions)
-[ok] **Table** : `transaction` (id, id_utilisateur, montant, type, date)
-[ok] **Vue** : affichage solde sur dashboard et navbar
-[ok] **Route** : `GET /wallet/solde` (AJAX ou direct)

---

## 6. OPTION GOLD

### 6.1 Paiement unique (prix à définir, ex: 9.99€)
-[ok] **Modèle** : `GoldModel` (vérifier si utilisateur déjà gold)
-[ok] **Contrôleur** : `GoldController` - méthode `paiement()`
-[ok] **Vue** : `gold/paiement.php` (simulation de paiement)
-[ok] **Déduction solde** : utiliser `WalletModel->debiter()`
-[ok] **Flag gold** : champ `is_gold` dans table `utilisateur`
-[ok] **Route** : `GET/POST /gold/acheter`

### 6.2 Application remise 15% sur régimes
-[ok] **Modèle** : `RegimeModel` - méthode `getPrixAvecRemise($id_regime, $is_gold)`
-[ok] **Helper** : `prix_helper.php`
-[ok] **Vue suggestion** : afficher prix normal ET remisé si gold
-[ok] **Check partout** : session `is_gold` à transmettre aux vues

---

## 7. BACK OFFICE (Authentification admin)

### 7.1 Login Admin
-[ok] **Table admin** : `administrateur` (id, email, password)
-[ok] **Contrôleur** : `AdminAuthController` - méthode `login()`
-[ok] **Vue** : `admin/login.php`
-[ok] **Route** : `/admin/login`

### 7.2 Tableau de bord avec graphiques
-[ok] **Contrôleur** : `AdminDashboardController`
-[ok] **Statistiques** :
  - Nombre utilisateurs par objectif
  - Répartition IMC (sous-poids/normal/surpoids/obèse)
  - Top 3 régimes suggérés
-[ok] **Lib graphique** : Chart.js ou Google Charts
-[ok] **Vue** : `admin/dashboard.php`
-[ok] **Route** : `GET /admin`

---

## 8. CRUD RÉGIMES (Back Office)

### 8.1 Liste, création, modification, suppression
-[ok] **Modèle** : `RegimeModel` (validation, champs : nom, description, type_cible, prix_journalier, variation_poids_semaine)
-[ok] **Table régime** ajouter : `prix_journalier DECIMAL(6,2)`, `variation_poids_kg DECIMAL(3,2)`
-[ok] **Contrôleur** : `AdminRegimeController` - méthodes index/create/edit/delete
-[ok] **Vues** : `admin/regime/index.php`, `create.php`, `edit.php`
-[ok] **Route CRUD** : `GET|POST /admin/regime/*`

### 8.2 Spécificité : % viande, poisson, volaille
-[ok] **Table** : `regime_composition` (id_regime, pourcentage_viande, pourcentage_poisson, pourcentage_volaille)
-[ok] **Modèle** : `RegimeCompositionModel`
-[ok] **Vue** : dans create/edit regime → champs pourcentages (somme=100)

---

## 9. CRUD ACTIVITÉS SPORTIVES (Back Office)

### 9.1 CRUD complet
-[ok] **Modèle** : `ActiviteModel`
-[ok] **Table** : `activite_sportive` (id, nom, description, type_cible, depense_calorique_estimee)
-[ok] **Contrôleur** : `AdminActiviteController`
-[ok] **Vues** : `admin/activite/index.php`, `create.php`, `edit.php`
-[ok] **Routes** : `GET|POST /admin/activite/*`

---

## 10. VALIDATION CODES PORTO-MONNAIE (Back Office)

### 10.1 CRUD Codes promo
-[ok] **Modèle** : `CodePromoModel` (admin peut créer/modifier/supprimer)
-[ok] **Contrôleur** : `AdminCodeController`
-[ok] **Vues** : `admin/code/index.php`, `create.php`
-[ok] **Route** : `POST /admin/code/valider` (si validation manuelle)

---

## 11. CRUD PARAMÈTRES

### 11.1 Paramètres système
-[ok] **Table** : `parametre` (id, cle, valeur, description)
-[ok] **Exemples** :
  - `prix_gold` : 9.99
  - `remise_gold_pct` : 15
  - `duree_suggestion_default` : 12 (semaines)
-[ok] **Modèle** : `ParametreModel`
-[ok] **Contrôleur** : `AdminParametreController`
-[ok] **Vues** : `admin/parametre/index.php`, `edit.php`
-[ok] **Route** : `GET|POST /admin/parametre`

---

## 12. DONNÉES MINIMALES À INSÉRER (Script SQL)

### 12.1 Données de test
-[ok] 5 utilisateurs (dont 1 gold)
-[ok] 15 codes promo (dont 5 déjà utilisés)
-[ok] 5 régimes chacun avec ses % viande/poisson/volaille
-[ok] 5 activités sportives

---

## 13. BONUS TECHNIQUES

### 13.1 AJAX partout où pertinent
-[ok] Vérification email existant (inscription)
-[ok] Validation code promo
-[ok] Calcul IMC en direct
-[ok] Suggestion sans rechargement

### 13.2 Commits et branches
-[ok] Chaque fonctionnalité = une branche → merge dans `main`
-[ok] Commits réguliers (pas 1 seul à la fin)
-[ok] Fichier `README.md` expliquant installation

### 13.3 Google Sheet suivi tâches
-[ok] Créer le sheet avec colonnes : Tâche | Responsable | Statut | Date début | Date fin
-[ok] Lien partagé dans formulaire de livraison

---

## 14. LIVRAISON FINALE

-[ok] Formulaire Google Forms rempli
-[ok] Lien GitHub/GitLab (public ou avec accès)
-[ok] Script SQL complet (structure + données)
-[ok] Liste membres (noms, emails, GitHub usernames)
-[ok] Lien Google Sheet suivi
-[ok] Vérification que la branche `main` est à jour avec tous les merges
