-- Table wallet (porte-monnaie)
CREATE TABLE IF NOT EXISTS wallet (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT NOT NULL UNIQUE,
    solde DECIMAL(10,2) DEFAULT 0,
    date_mise_a_jour DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE
);

-- Table transaction (historique des transactions)
CREATE TABLE IF NOT EXISTS transaction (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    type ENUM('credit', 'debit') NOT NULL,
    description VARCHAR(255),
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE
);

-- Ajouter un wallet pour chaque utilisateur existant
INSERT INTO wallet (id_utilisateur, solde) 
SELECT id, 0 FROM utilisateur WHERE id NOT IN (SELECT id_utilisateur FROM wallet);