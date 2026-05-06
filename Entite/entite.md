Les entités principales du sujet
Voici les concepts (entités) que vous devez modéliser dans votre application.

**`Utilisateur :`** La personne qui s'inscrit. (Identité : nom, email, genre, dateNaissance)
**`ProfilPhysique :`** Les données de santé qui changent dans le temps (poids, taille, activité, objectif). Séparé de Utilisateur car on peut en avoir plusieurs historiquement.
**`Objectif :`** Ce que l'utilisateur veut atteindre (type, valeur cible, date de début).
**`BesoinCalorique :`** Le résultat du calcul (MB, besoin_maintien, besoin_ajuste_selon_objectif).
**`Regime :`** Un plan alimentaire suggéré (ex: "Hyperprotéiné", "Hypocalorique", "Méditerranéen").
**`ActiviteSportive :`** Une activité suggérée (ex: "Cardio", "Musculation", "Marche").
**`Suggestion :`** Le lien entre un utilisateur, un régime, une activité, pour une durée donnée.

