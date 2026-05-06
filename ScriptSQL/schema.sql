CREATE DATABASE regime DEFAULT CHARACTER SET = 'utf8mb4';

CREATE TABLE utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    genre ENUM('homme', 'femme') NOT NULL,
    date_naissance DATE NOT NULL,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE profil_physique (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT NOT NULL,
    poids_kg DECIMAL(5,2) NOT NULL,
    taille_cm DECIMAL(5,2) NOT NULL,
    niveau_activite ENUM('sedentaire', 'leger', 'modere', 'actif', 'extreme') NOT NULL,
    date_mesure DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id)
);

CREATE TABLE objectif (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT NOT NULL,
    type_objectif ENUM('augmenter_poids', 'reduire_poids', 'imc_ideal') NOT NULL,
    poids_cible_kg DECIMAL(5,2) NULL,
    date_debut DATE NOT NULL,
    duree_souhaitee_semaines INT NULL,
    status ENUM('en_cours', 'atteint', 'abandonne') DEFAULT 'en_cours',
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id)
);

CREATE TABLE besoin_calorique (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_objectif INT NOT NULL,
    id_profil INT NOT NULL,
    metabolisme_base_kcal INT NOT NULL,
    besoin_maintien_kcal INT NOT NULL,
    besoin_objectif_kcal INT NOT NULL,
    date_calcul DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_objectif) REFERENCES objectif(id),
    FOREIGN KEY (id_profil) REFERENCES profil_physique(id)
);

CREATE TABLE regime (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_regime VARCHAR(100) NOT NULL,
    description TEXT,
    type_cible ENUM('augmenter', 'reduire', 'maintenir') NOT NULL,
    apport_calorique_reference INT
);

CREATE TABLE activite_sportive (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_activite VARCHAR(100) NOT NULL,
    description TEXT,
    type_cible ENUM('augmenter', 'reduire', 'maintenir') NOT NULL,
    depense_calorique_estimee INT
);

CREATE TABLE suggestion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_besoin INT NOT NULL,
    id_regime INT NOT NULL,
    id_activite INT NOT NULL,
    duree_recommandee_semaines INT NOT NULL,
    message_personnalise TEXT,
    date_suggestion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_besoin) REFERENCES besoin_calorique(id),
    FOREIGN KEY (id_regime) REFERENCES regime(id),
    FOREIGN KEY (id_activite) REFERENCES activite_sportive(id)
);