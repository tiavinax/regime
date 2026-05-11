# ETAPE CRITIQUE QUE CHAQUE COLLABORATEUR DOIVENT FAIRE AVANT DE COMMENCET A TRAVAILLER

# ⚠️ RÈGLES GIT STRICTES - LISEZ AVANT DE COMMENCER

## 🚫 **NE JAMAIS FAIRE :**

# ❌ INTERDIT ABSOLUMENT :
git add .              # Ajoute TOUS les fichiers
git add *              # Ajoute TOUS les fichiers
git commit -a          # Commit TOUS les changements


# Toujours ajouter fichier par fichier :

git add chemin_vers_fichier

# 1. Se mettre à jour
git pull origin main

# 2. Travailler

# 3. Ajouter UNIQUEMENT ses fichiers (otanzao no tokony atao rehefa i commit modification)
git add chemin_vers_fichier

# 4. Commit
git commit -m "feat(detector): optimisation scan ARP"

# 5. Pull (vérifier conflits)
git pull origin main

# 6. Push
git push origin main


# Configure Git (une seule fois) : (TSY MAITSY ATAO RAHA MBOLA TSY NANAO)

git config --global user.name "Ton Nom"
git config --global user.email "ton.email@example.com"

# 🌿 Guide des Branches Git

Commandes Essentielles

CREER & NAVIGUER : 

# Voir toutes les branches
git branch

# Créer une nouvelle branche
git branch nom-de-la-branche

# Changer de branche
git checkout nom-de-la-branche

# Créer ET changer de branche
git checkout -b nouvelle-branche

TRAVAILER SUR UNE BRANCHE :

# 1. Se mettre sur la branche
git checkout ma-feature

# 2. Travailler normalement (modifier fichiers)
# 3. Ajouter les changements
git add .

# 4. Sauvegarder (commiter)
git commit -m "Description des modifications"

# 5. Envoyer sur GitHub (si publiée)
git push origin ma-feature

🤝 FUSIONNER (Merge)

# 1. Retourner sur main
git checkout main

# 2. Mettre à jour main
git pull origin main

# 3. Fusionner votre branche
git merge ma-branche

# 4. Pousser les changements
git push origin main

🧹 Nettoyage 

# Supprimer une branche locale
git branch -d nom-branche

# Supprimer une branche distante
git push origin --delete nom-branche

# Voir toutes les branches (mêmes distantes)
git branch -a

Solution : Forcer l'écrasement de la branche distante
Option 1 : Push forcé (recommendée)
```bash
git push origin dev --force
ou

```bash
git push origin dev -f
Option 2 : Push forcé avec lease (plus sûre, vérifie qu'il n'y a pas eu de modifications)
```bash
git push origin dev --force-with-lease
Vérification après le push
```bash
git log origin/dev --oneline -5   # Vérifier que la remote a bien été écrasée
git status                         # Vérifier que tout est synchronisé
Alternative : Supprimer puis recréer la branche distante
1. Supprimer la branche dev distante
```bash
git push origin --delete dev
2. Pousser votre nouvelle branche dev
```bash
git push origin dev
Important : Nettoyer la branche dev locale
Si votre dev locale est toujours derrière la remote et que vous voulez la synchroniser :

```bash
git fetch origin
git branch -u origin/dev   # Réassocier la branche locale à la remote
Résumé complet des commandes :
```bash
# Vérifier que vous êtes sur la bonne branche
git branch

# Écraser la remote dev
git push origin dev --force-with-lease

# Vérifier l'état
git fetch origin
git log origin/dev --oneline -3
Attention : Cela supprime définitivement les 2 commits distants qui n'étaient pas dans votre dev locale. Assurez-vous que ces commits ne sont nécessaires nulle part (main, auth, etc.).

## CONFLICT 

Vous avez des fichiers en conflit non résolus. Vous ne pouvez pas changer de branche tant que ces conflits ne sont pas réglés.

Solution : Supprimer complètement la branche dev et recréer une nouvelle dev
        1 : Annuler l'état de fusion problématique
        
```bash
git merge --abort
```
        2 : Basculer vers la branche auth
```bash
git checkout auth
```
        3 : Supprimer la branche dev locale
```bash
git branch -D dev
```
Le -D force la suppression (même si les commits ne sont pas fusionnés).

        4 : Recréer une branche dev fraîche depuis auth
```bash
git checkout -b dev
Cette nouvelle branche dev sera identique à auth.

Option : Si vous voulez que dev suive origin/dev
```bash
git branch -u origin/dev
Alternative : Tout en une ligne
```bash
git merge --abort && git checkout auth && git branch -D dev && git checkout -b dev
Vérification finale
```bash
git branch
git status
Important : Cela supprime définitivement votre commit local sur l'ancienne dev. Si vous aviez des modifications importantes non poussées, sauvegardez-les d'abord avec git stash avant de supprimer la branche.