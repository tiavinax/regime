-- Vérifie s'il y a des régimes
SELECT * FROM regime;

-- Si vide, ajoute des données de test
INSERT INTO regime (nom_regime, description, type_cible, prix_journalier) VALUES
('Méditerranéen', 'Riche en légumes, fruits, poissons', 'reduire', 5.99),
('Hyperprotéiné', 'Riche en protéines pour la masse musculaire', 'augmenter', 7.99),
('Équilibré', 'Alimentation équilibrée pour maintenir son poids', 'maintenir', 4.99);

-- Vérifie les activités
SELECT * FROM activite_sportive;

-- Si vide, ajoute des données de test
INSERT INTO activite_sportive (nom_activite, description, type_cible, depense_calorique_estimee) VALUES
('Cardio', 'Course à pied, vélo, natation', 'reduire', 500),
('Musculation', 'Développement musculaire', 'augmenter', 300),
('Marche active', 'Marche rapide quotidienne', 'maintenir', 200);