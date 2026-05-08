INSERT INTO utilisateur (nom, email, password, genre, date_naissance) 
VALUES ('fyh', 'fyh@gmail.com', MD5('123456'), 'homme', '1990-01-01');

-- Profil actuel unique
INSERT INTO profil_physique (id_utilisateur, poids_kg, taille_cm, niveau_activite) 
VALUES (1, 75.0, 175, 'modere');

-- Objectif actif unique
INSERT INTO objectif (id_utilisateur, type_objectif, poids_cible_kg, date_debut, duree_souhaitee_semaines, status) 
VALUES (1, 'reduire_poids', 70.0, CURDATE(), 8, 'en_cours');

SELECT * FROM besoin_calorique WHERE id_objectif = 1 ORDER BY date_calcul DESC;