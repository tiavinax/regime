# 🍽️ NutriGoal - Application de régime alimentaire

## 📌 Description
Application web permettant aux utilisateurs de calculer leur IMC, définir des objectifs de poids et recevoir des recommandations personnalisées de régimes et activités sportives.

## 👥 Équipe
| Nom | Rôle |
|-----|------|
| Membre 1 | Authentification, Dashboard, PDF |
| Membre 2 | Back Office, CRUD, Statistiques |
| Membre 3 | Porte-monnaie, Gold, Code promo |

## 🚀 Fonctionnalités

### Front Office
- ✅ Inscription en 2 étapes (infos perso + santé)
- ✅ Connexion / Déconnexion
- ✅ Calcul IMC
- ✅ Choix des objectifs (augmenter/réduire/IMC idéal)
- ✅ Suggestion automatique régime + activité sportive
- ✅ Export PDF des recommandations
- ✅ Porte-monnaie avec codes promo
- ✅ Option Gold (49.99€, -15% sur tous les régimes)
- ✅ Catalogue régimes et sports

### Back Office
- ✅ Dashboard avec statistiques (graphiques, tableaux)
- ✅ CRUD des régimes (avec composition % viande/poisson/volaille)
- ✅ CRUD des activités sportives
- ✅ CRUD des utilisateurs
- ✅ Gestion des codes promo
- ✅ Réinitialisation base de données

## 🛠️ Technologies
- **Framework**: CodeIgniter 4
- **Base de données**: MySQL / MariaDB
- **Front**: HTML/CSS, JavaScript, AJAX, Chart.js
- **PDF**: FPDF
- **Versioning**: Git / GitHub

## 📦 Installation

### Prérequis
- PHP ≥ 7.4
- MySQL / MariaDB
- Composer
- Git

### Étapes d'installation

```bash
# 1. Cloner le projet
git clone https://github.com/TON_USERNAME/nutrigoal.git
cd nutrigoal

# 2. Installer les dépendances
composer install

# 3. Copier la configuration
cp .env.example .env

# 4. Créer la base de données
mysql -u root -p < ScriptSQL/reset_complete.sql

# 5. Lancer le serveur
php spark serve