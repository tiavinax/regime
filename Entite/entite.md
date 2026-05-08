# Projet : Regime

Les entités principales du sujet
Voici les concepts (entités) que vous devez modéliser dans votre application.

**`Utilisateur :`** La personne qui s'inscrit. (Identité : nom, email, genre, dateNaissance)
**`ProfilPhysique :`** Les données de santé qui changent dans le temps (poids, taille, activité, objectif). Séparé de Utilisateur car on peut en avoir plusieurs historiquement.
**`Objectif :`** Ce que l'utilisateur veut atteindre (type, valeur cible, date de début).
**`BesoinCalorique :`** Le résultat du calcul (MB, besoin_maintien, besoin_ajuste_selon_objectif).
**`Regime :`** Un plan alimentaire suggéré (ex: "Hyperprotéiné", "Hypocalorique", "Méditerranéen").
**`ActiviteSportive :`** Une activité suggérée (ex: "Cardio", "Musculation", "Marche").
**`Suggestion :`** Le lien entre un utilisateur, un régime, une activité, pour une durée donnée.



**Formule de Harris & Benedict (révisée)**
C’est la plus courante pour calculer le métabolisme de base (MB) → calories dépensées au repos, à jeun, température neutre.

Homme : MB = 88,362 + (13,397 × poids en kg) + (4,799 × taille en cm) − (5,677 × age)
Femme : MB = 447,593 + (9,247 × poids)+ (3,098 × taille) − (4,330 × age)

**Besoin énergétique total (BET)**

NAP :
Sédentaire : 1,2
Légèrement actif : 1,375
Modérément actif : 1,55
Très actif : 1,725

