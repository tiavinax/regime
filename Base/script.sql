-- ============================================================
--  GESTION DES NOTES — Script de création base de données
--  Projet : ETU003955 | Framework : CodeIgniter 4
--  Généré pour MySQL 5.7+
-- ============================================================

CREATE DATABASE IF NOT EXISTS gestion_notes
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE gestion_notes;

CREATE TABLE IF NOT EXISTS parcours (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code            VARCHAR(10)  NOT NULL UNIQUE,
    libelle         VARCHAR(150) NOT NULL,
    responsable     VARCHAR(150) NOT NULL DEFAULT '',
    semestre        TINYINT      NOT NULL DEFAULT 4 COMMENT '3 = tronc commun, 4 = parcours',
    created_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS matieres (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code            VARCHAR(10)  NOT NULL UNIQUE,
    intitule        VARCHAR(200) NOT NULL,
    credits         TINYINT      NOT NULL DEFAULT 0,
    semestre        TINYINT      NOT NULL COMMENT '3 ou 4',
    parcours_id     INT UNSIGNED DEFAULT NULL COMMENT 'NULL = UE commune tous parcours',
    is_optionnelle  TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '1 = UE au choix parmi liste',
    created_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_matiere_parcours
        FOREIGN KEY (parcours_id) REFERENCES parcours(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS etudiants (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    matricule       VARCHAR(20)  NOT NULL UNIQUE,
    nom             VARCHAR(100) NOT NULL,
    prenom          VARCHAR(100) NOT NULL,
    parcours_id     INT UNSIGNED NOT NULL COMMENT 'Parcours suivi par l étudiant',
    annee_academique VARCHAR(9)  NOT NULL DEFAULT '2024-2025' COMMENT 'Ex: 2024-2025',
    created_at      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_etudiant_parcours
        FOREIGN KEY (parcours_id) REFERENCES parcours(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS notes (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etudiant_id     INT UNSIGNED     NOT NULL,
    matiere_id      INT UNSIGNED     NOT NULL,
    note_devoir     DECIMAL(5,2)         NULL DEFAULT NULL COMMENT 'Note de devoir (sur 20)',
    note_examen     DECIMAL(5,2)         NULL DEFAULT NULL COMMENT 'Note d examen (sur 20)',
    moyenne         DECIMAL(5,2)         NULL DEFAULT NULL COMMENT 'Calculée : devoir*40% + examen*60%',
    annee_academique VARCHAR(9)       NOT NULL DEFAULT '2024-2025',
    created_at      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_note_etudiant
        FOREIGN KEY (etudiant_id) REFERENCES etudiants(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_note_matiere
        FOREIGN KEY (matiere_id) REFERENCES matieres(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT uq_note_etudiant_matiere_annee
        UNIQUE (etudiant_id, matiere_id, annee_academique)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- INDEX utiles pour les requêtes bulletin
-- ============================================================
CREATE INDEX idx_notes_etudiant    ON notes (etudiant_id);
CREATE INDEX idx_notes_matiere     ON notes (matiere_id);
CREATE INDEX idx_notes_annee       ON notes (annee_academique);
CREATE INDEX idx_etudiants_parcours ON etudiants (parcours_id);
CREATE INDEX idx_matieres_semestre  ON matieres (semestre);

-- ============================================================
-- SEED : Données de base — PARCOURS
-- ============================================================
INSERT INTO parcours (code, libelle, responsable, semestre) VALUES
('COMMUN',  'Tronc commun',              '',                      3),
('DEV',     'Développement',             'Razafinjoelina Tahina', 4),
('BDR',     'Bases de Données et Réseaux','Rakotomalala Vahatriniaina', 4),
('WEB',     'Web et Design',             'Rabenanahary Rojo',     4);

-- ============================================================
-- SEED : Données de base — MATIERES S3 (tronc commun)
-- parcours_id = 1 (COMMUN)
-- ============================================================
INSERT INTO matieres (code, intitule, credits, semestre, parcours_id, is_optionnelle) VALUES
('INF201', 'Programmation orientée objet',  6, 3, 1, 0),
('INF202', 'Bases de données objets',       6, 3, 1, 0),
('INF203', 'Programmation système',         4, 3, 1, 0),
('INF208', 'Réseaux informatiques',         6, 3, 1, 0),
('MTH201', 'Méthodes numériques',           4, 3, 1, 0),
('ORG201', 'Bases de gestion',              4, 3, 1, 0);

-- ============================================================
-- SEED : Données de base — MATIERES S4
-- Parcours DEV (id=2)
-- ============================================================
INSERT INTO matieres (code, intitule, credits, semestre, parcours_id, is_optionnelle) VALUES
-- UE au choix parmi INF204/INF205/INF206
('INF204', 'Système d information géographique', 6, 4, 2, 1),
('INF205', 'Système d information',              6, 4, 2, 1),
('INF206', 'Interface Homme/Machine',            6, 4, 2, 1),
-- Obligatoires DEV
('INF207', 'Éléments d algorithmique',           6, 4, 2, 0),
('INF210', 'Mini-projet de développement',      10, 4, 2, 0),
-- UE maths au choix
('MTH204', 'Géométrie',                          4, 4, 2, 1),
('MTH205', 'Équations différentielles',          4, 4, 2, 1),
('MTH206', 'Optimisation',                       4, 4, 2, 1),
-- Obligatoire maths
('MTH203', 'MAO',                                4, 4, 2, 0);

-- Parcours BDR (id=3)
INSERT INTO matieres (code, intitule, credits, semestre, parcours_id, is_optionnelle) VALUES
('INF211', 'Mini-projet de bases de données et/ou de réseaux', 10, 4, 3, 0),
('MTH202', 'Analyse des données',                               4, 4, 3, 1);
-- Note : INF205 obligatoire pour BDR — on l insère avec parcours BDR aussi
-- Les matières partagées (INF204,INF206,INF207,MTH205,MTH206,MTH203) sont déjà créées
-- La relation parcours×matière partagée sera gérée dans la requête bulletin par code matière

-- Parcours WEB (id=4)
INSERT INTO matieres (code, intitule, credits, semestre, parcours_id, is_optionnelle) VALUES
('INF209', 'Web dynamique',              6, 4, 4, 0),
('INF212', 'Mini-projet de Web et design',10, 4, 4, 0);

-- ============================================================
-- VERIFICATION : afficher le résumé des tables créées
-- ============================================================
SELECT 'parcours'  AS `table`, COUNT(*) AS lignes FROM parcours
UNION ALL
SELECT 'matieres',  COUNT(*) FROM matieres
UNION ALL
SELECT 'etudiants', COUNT(*) FROM etudiants
UNION ALL
SELECT 'notes',     COUNT(*) FROM notes;

-- ============= Tiavina (10:25) ============]

-- ============================================================
-- AJUSTEMENTS POUR MODULE ETUDIANT
-- ============================================================

-- 1. Ajouter une table users pour l'authentification
CREATE TABLE IF NOT EXISTS users (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50)  NOT NULL UNIQUE,
    email       VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    role        ENUM('admin', 'enseignant', 'etudiant') DEFAULT 'enseignant',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Ajouter un compte admin par défaut (password = 'password' en hash)
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@gesnotes.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- 3. Ajouter un index sur le nom pour les recherches rapides
CREATE INDEX idx_etudiants_nom ON etudiants(nom(20));
CREATE INDEX idx_etudiants_prenom ON etudiants(prenom(20));

-- 4. Ajouter un index composite pour la recherche multicritère
CREATE INDEX idx_etudiants_recherche ON etudiants(nom, prenom, matricule);

-- 5. Ajouter une contrainte de validation sur le matricule (format unique)
ALTER TABLE etudiants 
ADD CONSTRAINT chk_matricule_format 
CHECK (matricule REGEXP '^[A-Z0-9]{6,20}$');