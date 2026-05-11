-- Table codes promo
CREATE TABLE IF NOT EXISTS codes_promo (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    valeur DECIMAL(10,2) NOT NULL,
    type ENUM('percentage', 'fixed') DEFAULT 'percentage',
    utilisations_max INT DEFAULT NULL,
    utilisations_actuelles INT DEFAULT 0,
    date_expiration DATE DEFAULT NULL,
    est_actif TINYINT DEFAULT 1,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table parametres
CREATE TABLE IF NOT EXISTS parametres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cle VARCHAR(100) UNIQUE NOT NULL,
    valeur TEXT,
    description TEXT,
    type VARCHAR(50) DEFAULT 'text'
);

-- Insertion des paramètres par défaut
INSERT INTO parametres (cle, valeur, description) VALUES
('site_name', 'NutriGoal', 'Nom du site'),
('prix_gold', '49.99', 'Prix de l\'abonnement Gold'),
('remise_gold_pct', '15', 'Remise pour les membres Gold (%)'),
('duree_suggestion_default', '12', 'Durée par défaut des suggestions (semaines)'),
('email_contact', 'contact@nutrigoal.com', 'Email de contact'),
('version', '1.0.0', 'Version de l\'application');