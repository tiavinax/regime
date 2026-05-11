-- script_SQL_reset.sql
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE transaction;
TRUNCATE TABLE wallet;
TRUNCATE TABLE suggestion;
TRUNCATE TABLE besoin_calorique;
TRUNCATE TABLE objectif;
TRUNCATE TABLE profil_physique;
TRUNCATE TABLE code_promo;
TRUNCATE TABLE utilisateur;
TRUNCATE TABLE regime_composition;
TRUNCATE TABLE regime;
TRUNCATE TABLE activite_sportive;
TRUNCATE TABLE parametres;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- INSERTION DES DONNÉES
-- ============================================

-- 1. UTILISATEURS
INSERT INTO utilisateur (id, nom, email, password, genre, date_naissance, is_gold, role) VALUES
(1, 'Admin System', 'admin@nutrigoal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'homme', '1985-01-15', 1, 'admin'),
(2, 'Jean Dupont', 'jean.dupont@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'homme', '1990-05-20', 0, 'user'),
(3, 'Marie Martin', 'marie.martin@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'femme', '1988-12-10', 1, 'user'),
(4, 'Sophie Bernard', 'sophie.bernard@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'femme', '1995-03-25', 0, 'user'),
(5, 'Thomas Petit', 'thomas.petit@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'homme', '2000-07-08', 0, 'user');

-- 2. PROFILS PHYSIQUES
INSERT INTO profil_physique (id_utilisateur, poids_kg, taille_cm, niveau_activite) VALUES
(1, 75.5, 178, 'actif'),
(2, 82.0, 175, 'modere'),
(3, 65.0, 165, 'actif'),
(4, 58.0, 160, 'leger'),
(5, 70.0, 170, 'modere');

-- 3. OBJECTIFS
INSERT INTO objectif (id_utilisateur, type_objectif, date_debut, duree_souhaitee_semaines, status) VALUES
(1, 'maintenir', CURDATE(), 0, 'en_cours'),
(2, 'reduire_poids', CURDATE(), 12, 'en_cours'),
(3, 'maintenir', CURDATE(), 0, 'en_cours'),
(4, 'augmenter_poids', CURDATE(), 8, 'en_cours'),
(5, 'reduire_poids', CURDATE(), 10, 'en_cours');

-- 4. RÉGIMES
INSERT INTO regime (id, nom_regime, description, type_cible, prix_journalier) VALUES
(1, 'Méditerranéen', 'Riche en légumes, fruits, poissons', 'reduire', 5.99),
(2, 'Hyperprotéiné', 'Riche en protéines pour la masse musculaire', 'augmenter', 7.99),
(3, 'Équilibré', 'Alimentation équilibrée', 'maintenir', 4.99),
(4, 'Cétogène', 'Très faible en glucides', 'reduire', 8.99),
(5, 'Végétarien', 'Sans viande, riche en légumineuses', 'maintenir', 6.50);

-- 5. COMPOSITIONS
INSERT INTO regime_composition (id_regime, pourcentage_viande, pourcentage_poisson, pourcentage_volaille) VALUES
(1, 15, 35, 20),
(2, 40, 10, 35),
(3, 25, 20, 25),
(4, 30, 15, 20),
(5, 0, 25, 0);

-- 6. ACTIVITÉS SPORTIVES
INSERT INTO activite_sportive (id, nom_activite, description, type_cible, depense_calorique_estimee) VALUES
(1, 'Cardio', 'Course à pied, vélo, natation', 'reduire', 500),
(2, 'Musculation', 'Développement musculaire', 'augmenter', 300),
(3, 'Marche active', 'Marche rapide quotidienne', 'maintenir', 200),
(4, 'Natation', 'Sport complet sans impact', 'reduire', 600),
(5, 'Vélo', 'Cyclisme modéré', 'maintenir', 450);

-- 7. WALLETS
INSERT INTO wallet (id_utilisateur, solde) VALUES
(1, 100.00),
(2, 50.00),
(3, 200.00),
(4, 75.00),
(5, 30.00);

-- 8. CODES PROMO (15 codes)
INSERT INTO code_promo (code, valeur, type, est_utilise, est_actif, utilisations_max, utilisations_actuelles, date_expiration, date_creation) VALUES
('BIENVENUE10', 200.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), NOW()),
('SANTE20', 220.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), NOW()),
('NUTRI30', 300.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 45 DAY), NOW()),
('GOAL40', 400.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 60 DAY), NOW()),
('REMIZE50', 500.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), NOW()),
('GOLD25', 250.00, 'fixed', 1, 1, 1, 1, DATE_ADD(NOW(), INTERVAL 45 DAY), NOW()),
('FITNESS35', 350.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 60 DAY), NOW()),
('BIENETRE45', 450.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 90 DAY), NOW()),
('SANTEPLUS55', 550.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 60 DAY), NOW()),
('PASSION60', 160.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 90 DAY), NOW()),
('AMBASSADOR70', 270.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 120 DAY), NOW()),
('NUTRIGOLD80', 180.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 90 DAY), NOW()),
('PROMO15', 215.00, 'fixed', 0, 1, 10, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), NOW()),
('WELLNESS25', 325.00, 'fixed', 0, 1, 5, 0, DATE_ADD(NOW(), INTERVAL 60 DAY), NOW()),
('NUTRITION20', 320.00, 'fixed', 0, 1, 3, 0, DATE_ADD(NOW(), INTERVAL 45 DAY), NOW());

-- 9. PARAMÈTRES
INSERT INTO parametres (cle, valeur, description) VALUES
('prix_gold', '49.99', 'Prix de l''option Gold'),
('remise_gold_pct', '15', 'Pourcentage de remise Gold'),
('duree_suggestion_default', '12', 'Durée par défaut des suggestions');