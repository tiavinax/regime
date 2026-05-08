ALTER TABLE regime ADD COLUMN prix_journalier DECIMAL(6,2) DEFAULT 0;
ALTER TABLE regime ADD COLUMN variation_poids_semaine DECIMAL(3,2) DEFAULT 0;
ALTER TABLE utilisateur ADD COLUMN is_gold TINYINT DEFAULT 0;

-- Pour voir la structure actuelle :
DESCRIBE utilisateur;


ALTER TABLE utilisateur MODIFY COLUMN role ENUM('user', 'admin') NOT NULL DEFAULT 'user';
UPDATE utilisateur SET role = 'admin' WHERE email = 'admin@exemple.com';

