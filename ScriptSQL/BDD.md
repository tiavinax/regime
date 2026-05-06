```sql
-- 1. Table utilisateur : Gère l'authentification et l'identité civile
-- Pourquoi ? Séparer les infos stables (nom, email) des infos variables (poids).
CREATE TABLE utilisateur (
    id_utilisateur INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,            -- Pour personnaliser l'affichage
    email VARCHAR(150) UNIQUE NOT NULL,   -- Utilisé pour le login, doit être unique
    mot_de_passe VARCHAR(255) NOT NULL,   -- Stocké hashé (BCrypt, Argon2)
    genre ENUM('homme', 'femme') NOT NULL,-- Essentiel pour la formule de calcul
    date_naissance DATE NOT NULL,          -- Pour calculer l'age (besoin énergétique)
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 2. Table profil_physique : Stocke les mensurations (peut évoluer)
-- Pourquoi ? Un utilisateur peut se peser plusieurs fois. On veut l'historique et la valeur la plus récente.
CREATE TABLE profil_physique (
    id_profil INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT NOT NULL,          -- Clé étrangère vers utilisateur
    poids_kg DECIMAL(5,2) NOT NULL,       -- Permet de calculer IMC et MB
    taille_cm DECIMAL(5,2) NOT NULL,      -- Permet de calculer IMC et MB (précision)
    niveau_activite ENUM('sedentaire', 'leger', 'modere', 'actif', 'extreme') NOT NULL, -- Facteur NEA
    date_mesure DATETIME DEFAULT CURRENT_TIMESTAMP, -- Pour savoir quelle est la dernière mesure
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
);

-- 3. Table objectif : L'utilisateur choisit ce qu'il veut faire
-- Pourquoi ? Pour stocker la décision de l'utilisateur et la dater.
CREATE TABLE objectif (
    id_objectif INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT NOT NULL,          -- A quel utilisateur appartient cet objectif
    type_objectif ENUM('augmenter_poids', 'reduire_poids', 'imc_ideal') NOT NULL,
    -- Pour 'imc_ideal', on peut calculer la cible. Pour 'augmenter', pas de valeur cible explicite.
    poids_cible_kg DECIMAL(5,2) NULL,     -- Optionnel : si l'utilisateur donne un poids précis
    date_debut DATE NOT NULL,             -- Quand l'objectif commence
    duree_souhaitee_semaines INT NULL,    -- Optionnel : "je veux le faire en X semaines"
    status ENUM('en_cours', 'atteint', 'abandonne') DEFAULT 'en_cours',
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
);

-- 4. Table besoin_calorique : Résultat du calcul (logique métier)
-- Pourquoi ? Pour garder une trace du calcul proposé à l'utilisateur, et ne pas le recalculer à chaque affichage.
CREATE TABLE besoin_calorique (
    id_besoin INT PRIMARY KEY AUTO_INCREMENT,
    id_objectif INT NOT NULL,             -- Lié à l'objectif spécifique
    id_profil INT NOT NULL,               -- Lié au profil utilisé pour ce calcul
    metabolisme_base_kcal INT NOT NULL,   -- Résultat formule Harris & Benedict
    besoin_maintien_kcal INT NOT NULL,    -- MB * activité
    besoin_objectif_kcal INT NOT NULL,    -- Ajusté selon l'objectif (ex: maintien - 400)
    date_calcul DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_objectif) REFERENCES objectif(id_objectif),
    FOREIGN KEY (id_profil) REFERENCES profil_physique(id_profil)
);

-- 5. Table regime : Catalogue des régimes disponibles
-- Pourquoi ? Pour ne pas coder en dur les suggestions, mais les gérer en base de données.
CREATE TABLE regime (
    id_regime INT PRIMARY KEY AUTO_INCREMENT,
    nom_regime VARCHAR(100) NOT NULL,     -- Ex: "Hypercalorique propre", "Hypocalorique équilibré"
    description TEXT,                     -- Ex: "Privilégiez les protéines maigres..."
    type_cible ENUM('augmenter', 'reduire', 'maintenir') NOT NULL, -- Pour quel objectif est-il adapté ?
    apport_calorique_reference INT        -- Ex: 1800 kcal. Optionnel (suggestion)
);

-- 6. Table activite_sportive : Catalogue des sports recommandés
-- Pourquoi ? Même raison que regime. On peut en ajouter sans changer le code.
CREATE TABLE activite_sportive (
    id_activite INT PRIMARY KEY AUTO_INCREMENT,
    nom_activite VARCHAR(100) NOT NULL,   -- Ex: "Marche rapide", "Natation", "Musculation"
    description TEXT,                     -- Ex: "3 fois par semaine, 45 minutes"
    type_cible ENUM('augmenter', 'reduire', 'maintenir') NOT NULL,
    depense_calorique_estimee INT         -- Pour affichage : "Brule environ 300 kcal/séance"
);

-- 7. Table suggestion : Le résultat final que l'application affiche à l'utilisateur
-- Pourquoi ? C'est le lien entre ce que veut l'utilisateur (objectif) et les recommandations (régime + sport).
CREATE TABLE suggestion (
    id_suggestion INT PRIMARY KEY AUTO_INCREMENT,
    id_besoin INT NOT NULL,               -- Pour savoir sur quel calcul se base cette suggestion
    id_regime INT NOT NULL,               -- Le régime recommandé
    id_activite INT NOT NULL,             -- L'activité recommandée
    duree_recommandee_semaines INT NOT NULL, -- Ex: 12 semaines
    message_personnalise TEXT,            -- Ex: "Pour perdre du poids durablement..."
    date_suggestion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_besoin) REFERENCES besoin_calorique(id_besoin),
    FOREIGN KEY (id_regime) REFERENCES regime(id_regime),
    FOREIGN KEY (id_activite) REFERENCES activite_sportive(id_activite)
);
```