DROP TABLE IF EXISTS code_promo;

CREATE TABLE code_promo (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) NOT NULL UNIQUE,
    valeur DECIMAL(10,2) NOT NULL,
    type ENUM('percentage', 'fixed') DEFAULT 'fixed',
    est_utilise TINYINT DEFAULT 0,
    est_actif TINYINT DEFAULT 1,
    utilisations_max INT DEFAULT NULL,
    utilisations_actuelles INT DEFAULT 0,
    id_utilisateur INT NULL,
    date_expiration DATE DEFAULT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_utilisation DATETIME NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE SET NULL
);



-- Vider la table avant insertion 
-- TRUNCATE TABLE code_promo;

INSERT INTO code_promo (code, valeur, type, est_utilise, est_actif, utilisations_max, utilisations_actuelles, date_expiration, date_creation) VALUES

-- Codes de petite valeur (5€ - 10€)
('BIENVENUE5', 5.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), NOW()),
('SANTE10', 10.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), NOW()),
('NUTRI5', 5.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 45 DAY), NOW()),
('GOAL10', 10.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 60 DAY), NOW()),

-- Codes de moyenne valeur (15€ - 25€)
('REMIZE15', 15.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), NOW()),
('GOLD20', 20.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 45 DAY), NOW()),
('FITNESS15', 15.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 60 DAY), NOW()),
('BIENETRE25', 25.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 90 DAY), NOW()),
('SANTEPLUS20', 20.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 60 DAY), NOW()),

-- Codes de grande valeur (30€ - 50€)
('PASSION30', 30.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 90 DAY), NOW()),
('AMBASSADOR50', 50.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 120 DAY), NOW()),
('NUTRIGOLD40', 40.00, 'fixed', 0, 1, 1, 0, DATE_ADD(NOW(), INTERVAL 90 DAY), NOW()),

-- Codes multi-utilisations (plusieurs personnes peuvent l'utiliser)
('PROMO10', 10.00, 'fixed', 0, 1, 10, 0, DATE_ADD(NOW(), INTERVAL 30 DAY), NOW()),
('WELLNESS15', 15.00, 'fixed', 0, 1, 5, 0, DATE_ADD(NOW(), INTERVAL 60 DAY), NOW()),
('NUTRITION20', 20.00, 'fixed', 0, 1, 3, 0, DATE_ADD(NOW(), INTERVAL 45 DAY), NOW());

-- ============================================
-- AFFICHER LES 15 CODES CRÉÉS
-- ============================================
SELECT 
    id,
    code,
    CONCAT(valeur, ' €') as montant,
    CASE 
        WHEN type = 'fixed' THEN 'Montant fixe'
        ELSE 'Pourcentage'
    END as type_reduction,
    utilisations_max as max_utilisations,
    DATE_FORMAT(date_expiration, '%d/%m/%Y') as expire_le,
    CASE 
        WHEN est_actif = 1 THEN 'Actif'
        ELSE 'Inactif'
    END as statut
FROM code_promo
ORDER BY valeur ASC;

-- ============================================
-- RÉCAPITULATIF DES VALEURS
-- ============================================
SELECT 
    COUNT(*) as total_codes,
    SUM(valeur) as valeur_totale_en_euros,
    AVG(valeur) as valeur_moyenne,
    MIN(valeur) as valeur_min,
    MAX(valeur) as valeur_max
FROM code_promo;