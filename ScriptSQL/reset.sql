-- ============================================
-- SCRIPT DE RÉINITIALISATION COMPLÈTE
-- À exécuter pour repartir à zéro
-- ============================================

-- Désactiver les contraintes pour supprimer facilement
SET FOREIGN_KEY_CHECKS = 0;

-- Supprimer toutes les tables existantes
DROP TABLE IF EXISTS suggestion;
DROP TABLE IF EXISTS besoin_calorique;
DROP TABLE IF EXISTS objectif;
DROP TABLE IF EXISTS profil_physique;
DROP TABLE IF EXISTS regime_composition;
DROP TABLE IF EXISTS activite_sportive;
DROP TABLE IF EXISTS regime;
DROP TABLE IF EXISTS code_promo;
DROP TABLE IF EXISTS utilisateur;

-- Réactiver les contraintes
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- CRÉATION DES TABLES
-- ============================================

-- Table utilisateur
CREATE TABLE utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    genre ENUM('homme', 'femme') NOT NULL,
    date_naissance DATE NOT NULL,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_gold TINYINT DEFAULT 0,
    role ENUM('user', 'admin') DEFAULT 'user'
);

-- Table profil physique
CREATE TABLE profil_physique (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT NOT NULL,
    poids_kg DECIMAL(5,2) NOT NULL,
    taille_cm DECIMAL(5,2) NOT NULL,
    niveau_activite ENUM('sedentaire', 'leger', 'modere', 'actif', 'extreme') NOT NULL,
    date_mesure DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE
);

-- Table objectif
CREATE TABLE objectif (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT NOT NULL,
    type_objectif ENUM('augmenter_poids', 'reduire_poids', 'imc_ideal') NOT NULL,
    poids_cible_kg DECIMAL(5,2) NULL,
    date_debut DATE NOT NULL,
    duree_souhaitee_semaines INT NULL,
    status ENUM('en_cours', 'atteint', 'abandonne') DEFAULT 'en_cours',
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE
);

-- Table besoin calorique
CREATE TABLE besoin_calorique (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_objectif INT NOT NULL,
    id_profil INT NOT NULL,
    metabolisme_base_kcal INT NOT NULL,
    besoin_maintien_kcal INT NOT NULL,
    besoin_objectif_kcal INT NOT NULL,
    date_calcul DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_objectif) REFERENCES objectif(id) ON DELETE CASCADE,
    FOREIGN KEY (id_profil) REFERENCES profil_physique(id) ON DELETE CASCADE
);

-- Table régime
CREATE TABLE regime (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_regime VARCHAR(100) NOT NULL,
    description TEXT,
    type_cible ENUM('augmenter', 'reduire', 'maintenir') NOT NULL,
    apport_calorique_reference INT,
    prix_journalier DECIMAL(6,2) DEFAULT 5.00,
    variation_poids_semaine DECIMAL(3,2) DEFAULT 0.5
);

-- Table composition du régime (%, viande, poisson, volaille)
CREATE TABLE regime_composition (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_regime INT NOT NULL,
    pourcentage_viande INT DEFAULT 0,
    pourcentage_poisson INT DEFAULT 0,
    pourcentage_volaille INT DEFAULT 0,
    FOREIGN KEY (id_regime) REFERENCES regime(id) ON DELETE CASCADE
);

-- Table activité sportive
CREATE TABLE activite_sportive (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_activite VARCHAR(100) NOT NULL,
    description TEXT,
    type_cible ENUM('augmenter', 'reduire', 'maintenir') NOT NULL,
    depense_calorique_estimee INT
);

-- Table suggestion
CREATE TABLE suggestion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_besoin INT NOT NULL,
    id_regime INT NOT NULL,
    id_activite INT NOT NULL,
    duree_recommandee_semaines INT NOT NULL,
    message_personnalise TEXT,
    date_suggestion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_besoin) REFERENCES besoin_calorique(id) ON DELETE CASCADE,
    FOREIGN KEY (id_regime) REFERENCES regime(id),
    FOREIGN KEY (id_activite) REFERENCES activite_sportive(id)
);

-- Table code promo (porte-monnaie)
CREATE TABLE code_promo (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) NOT NULL UNIQUE,
    valeur DECIMAL(10,2) NOT NULL,
    est_utilise TINYINT DEFAULT 0,
    id_utilisateur INT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_utilisation DATETIME NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE SET NULL
);

-- ============================================
-- INSERTION DES DONNÉES DE TEST (5 utilisateurs, 5 régimes, 5 activités, 15 codes)
-- ============================================

-- 1. INSÉRER 5 UTILISATEURS
INSERT INTO utilisateur (nom, email, password, genre, date_naissance, is_gold, role) VALUES
('Admin User', 'admin@nutrigoal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'homme', '1985-01-15', 1, 'admin'),
('Jean Dupont', 'jean.dupont@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'homme', '1990-05-20', 0, 'user'),
('Marie Martin', 'marie.martin@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'femme', '1988-12-10', 1, 'user'),
('Sophie Bernard', 'sophie.bernard@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'femme', '1995-03-25', 0, 'user'),
('Thomas Petit', 'thomas.petit@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'homme', '2000-07-08', 0, 'user');

-- mot de passe pour tous = "password"

-- 2. INSÉRER LES PROFILS PHYSIQUES
INSERT INTO profil_physique (id_utilisateur, poids_kg, taille_cm, niveau_activite, date_mesure) VALUES
(1, 75.5, 178, 'actif', NOW()),
(2, 82.0, 175, 'modere', NOW()),
(3, 65.0, 165, 'actif', NOW()),
(4, 58.0, 160, 'leger', NOW()),
(5, 70.0, 170, 'modere', NOW());

-- 3. INSÉRER LES OBJECTIFS
INSERT INTO objectif (id_utilisateur, type_objectif, poids_cible_kg, date_debut, duree_souhaitee_semaines, status) VALUES
(1, 'maintenir', NULL, '2026-05-01', 0, 'en_cours'),
(2, 'reduire_poids', NULL, '2026-05-01', 12, 'en_cours'),
(3, 'maintenir', NULL, '2026-05-01', 0, 'en_cours'),
(4, 'augmenter_poids', 62.0, '2026-05-01', 8, 'en_cours'),
(5, 'reduire_poids', NULL, '2026-05-01', 10, 'en_cours');

-- 4. INSÉRER LES BESOINS CALORIQUES (calculs approximatifs)
INSERT INTO besoin_calorique (id_objectif, id_profil, metabolisme_base_kcal, besoin_maintien_kcal, besoin_objectif_kcal, date_calcul) VALUES
(1, 1, 1650, 2400, 2400, NOW()),
(2, 2, 1750, 2500, 2000, NOW()),
(3, 3, 1400, 2100, 2100, NOW()),
(4, 4, 1300, 1800, 2100, NOW()),
(5, 5, 1600, 2300, 1900, NOW());

-- 5. INSÉRER 5 RÉGIMES
INSERT INTO regime (nom_regime, description, type_cible, apport_calorique_reference, prix_journalier, variation_poids_semaine) VALUES
('Méditerranéen', 'Riche en légumes, fruits, poissons, huile d''olive - Idéal pour la santé cardiovasculaire', 'reduire', 1800, 5.99, 0.5),
('Hyperprotéiné', 'Riche en protéines pour la construction musculaire et la satiété', 'augmenter', 2500, 7.99, 0.8),
('Équilibré', 'Alimentation équilibrée pour maintenir son poids et sa santé', 'maintenir', 2100, 4.99, 0.0),
('Cétogène', 'Très faible en glucides, riche en lipides - Pour perte de poids rapide', 'reduire', 1600, 8.99, 1.0),
('Végétarien', 'Sans viande, riche en légumineuses et protéines végétales', 'maintenir', 2000, 6.50, 0.3);

-- 6. INSÉRER LES COMPOSITIONS DES RÉGIMES (%, viande, poisson, volaille)
INSERT INTO regime_composition (id_regime, pourcentage_viande, pourcentage_poisson, pourcentage_volaille) VALUES
(1, 15, 35, 20),   -- Méditerranéen : plus de poisson
(2, 40, 10, 35),   -- Hyperprotéiné : plus de viande et volaille
(3, 25, 20, 25),   -- Équilibré : équilibré
(4, 30, 15, 20),   -- Cétogène : plus de viande
(5, 0, 25, 0);     -- Végétarien : seulement poisson

-- 7. INSÉRER 5 ACTIVITÉS SPORTIVES
INSERT INTO activite_sportive (nom_activite, description, type_cible, depense_calorique_estimee) VALUES
('Cardio (Course à pied)', 'Course à pied modérée - 8 km/h - Excellent pour brûler les graisses', 'reduire', 500),
('Musculation', 'Entraînement en force pour développer la masse musculaire', 'augmenter', 300),
('Marche active', 'Marche rapide quotidienne - Idéal pour débutants', 'maintenir', 200),
('Natation', 'Nage crawl modérée - Sport complet sans impact', 'reduire', 600),
('Vélo', 'Cyclisme modéré - 20 km/h - Bon pour l''endurance', 'maintenir', 450);

-- 8. INSÉRER 15 CODES PROMO
INSERT INTO code_promo (code, valeur, est_utilise, id_utilisateur, date_utilisation) VALUES
('BIENVENUE10', 10.00, 0, NULL, NULL),
('NUTRIGOLD20', 20.00, 0, NULL, NULL),
('SANTE2026', 15.00, 0, NULL, NULL),
('REMIZE50', 50.00, 0, NULL, NULL),
('CODE5EURO', 5.00, 0, NULL, NULL),
('GOLD25', 25.00, 1, 3, NOW()),
('PROMO10', 10.00, 1, 2, NOW()),
('SOLDE15', 15.00, 0, NULL, NULL),
('BIENETRE30', 30.00, 0, NULL, NULL),
('FITNESS100', 100.00, 0, NULL, NULL),
('PASSIONSPORT', 15.00, 0, NULL, NULL),
('SANTEPLUS', 20.00, 0, NULL, NULL),
('RENOUVELLE', 25.00, 0, NULL, NULL),
('DECOUVERTE', 10.00, 0, NULL, NULL),
('AMBASSADEUR', 50.00, 0, NULL, NULL);

-- 9. INSÉRER DES SUGGESTIONS
INSERT INTO suggestion (id_besoin, id_regime, id_activite, duree_recommandee_semaines, message_personnalise) VALUES
(1, 3, 3, 12, '🎯 Programme équilibré : Suivez le régime "Équilibré" et pratiquez la "Marche active" pour maintenir votre forme.'),
(2, 1, 1, 12, '🎯 Programme perte de poids : Suivez le régime "Méditerranéen" et pratiquez le "Cardio" pendant 12 semaines.'),
(3, 3, 3, 8, '🎯 Programme bien-être : Continuez avec le régime "Équilibré" et la "Marche active".'),
(4, 2, 2, 8, '💪 Programme prise de masse : Suivez le régime "Hyperprotéiné" et pratiquez la "Musculation" pendant 8 semaines.'),
(5, 1, 1, 10, '🎯 Programme minceur : Suivez le régime "Méditerranéen" et pratiquez le "Cardio" pendant 10 semaines.');

-- ============================================
-- VÉRIFICATIONS FINALES
-- ============================================

SELECT '✅ Base de données réinitialisée avec succès !' AS Message;
SELECT COUNT(*) AS Utilisateurs FROM utilisateur;
SELECT COUNT(*) AS Profils FROM profil_physique;
SELECT COUNT(*) AS Objectifs FROM objectif;
SELECT COUNT(*) AS BesoinsCaloriques FROM besoin_calorique;
SELECT COUNT(*) AS Regimes FROM regime;
SELECT COUNT(*) AS Activites FROM activite_sportive;
SELECT COUNT(*) AS CodesPromo FROM code_promo;
SELECT COUNT(*) AS Suggestions FROM suggestion;

-- ============================================
-- TEST DE CONNEXION (mot de passe = "password" pour tous)
-- Email: jean.dupont@email.com / password
-- Email: admin@nutrigoal.com / password
-- ============================================