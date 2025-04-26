# 🌤️ Application Météo – Vue.js & Symfony

Bienvenue dans le projet **Application Météo** ! Ce projet full stack utilise un backend Symfony et un frontend Vue.js pour afficher la météo en temps réel d’une ville de votre choix via l’API Open-Meteo.

---

## 🌟 Objectifs du projet

- Développer une **application météo fonctionnelle**.
- Récupérer les données via **l’API Open-Meteo**.
- Implémenter un backend PHP (Symfony) permettant de gérer des enregistrements météo (**CRUD**).
- Créer un frontend en Vue.js pour afficher et interagir avec les données.

---

## 📑 Architecture de l'application

L'application est composée de deux parties principales :

1. **Frontend (Vue.js)** :
   - Interface intuitive pour rechercher une ville et consulter la météo.
   - Affichage dynamique selon la condition météo (avec fond et icône).
   - Historique des recherches sauvegardées (lecture + suppression).

2. **Backend (Symfony)** :
   - Point d'entrée d’API pour récupérer les données météo via Open-Meteo.
   - Gestion des sauvegardes (création, lecture, suppression) en BDD.

---

## 🚀 Fonctionnalités principales

- Rechercher la météo d’une ville.
- Affichage détaillé : température, vent, heure, latitude, longitude, condition météo.
- Sauvegarder et gérer un historique des recherches.
- Affichage dynamique selon les conditions météo (ex: pluie =  icône adaptée).
- Gestion d’erreur (ville introuvable, champs vides).
- Indicateur de chargement lors de la requête (ex: chargement..).

---

## 🛠️ Technologies utilisées

- **Vue.js 3** (Composition API)
- **Tailwind CSS** (design responsive)
- **Axios** (requêtes HTTP)
- **Symfony 6** (PHP)
- **Doctrine ORM** (base de données)
- **API Open-Meteo** : https://open-meteo.com/

---

## 🏗️ Étapes réalisées

### 1️⃣ Développement du Backend Symfony

- Configuration d’une route `/api/search?city=...` qui interroge l’API Open-Meteo.
- Conversion des codes météo en texte lisible.
- Création d’un endpoint `POST /api/save` pour sauvegarder une météo.
- Endpoint `GET /api` pour lire toutes les villes enregistrées.
- Endpoint `DELETE /api/{id}` pour supprimer un enregistrement.

### 2️⃣ Développement du Frontend Vue.js

- Saisie utilisateur via un champ de recherche.
- Requête vers le backend pour récupérer les données météo.
- Utilisation de `v-if` pour afficher conditionnellement les infos météo.
- Sauvegarde et suppression via boutons + requêtes Axios.
- Ajout d’un **loader** pendant les recherches (`Chargement...`).
- Gestion des erreurs : si la ville est introuvable, afficher un message.

### 3️⃣ Dynamisme UX / UI

- Dégradé par défaut en fond : `linear-gradient(to bottom, #0369a1, #312e81)`.
- Rendu dynamique avec icônes en fonction de la météo.

---

## 📸 Aperçu visuel

![Aperçu](frontend/assets/images/demo.jpg)


---


## ⚙️ Instructions pour lancer le projet

### Prérequis

- PHP 8.1+, Composer, Symfony CLI
- Node.js + npm
- MySQL / SQLite pour Symfony

### Backend Symfony

```bash
cd backend/
composer install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
symfony server:start
```
API : `http://localhost:8000/weather/api`

### Frontend Vue

```bash
cd frontend/
npm install
npm run dev
```

> Le projet utilise par défaut `http://localhost:8000` comme base API.


App : `http://localhost:5173`
---

## 📂 Structure du projet

```plaintext
weather-app/
├── backend/           → Projet Symfony
│   ├── src/Controller/WeatherController.php
│   ├── Entity/Weather.php
│   └── ...
├── frontend/          → Projet Vue.js
│   ├── App.vue
│   ├── components/
│   ├── public/icons/
│   └── ...
└── README.md
```

---

## 🐞 Gestion des erreurs et logs

- Ville vide ou invalide ? → Message `Ville introuvable` s'affiche.
- Requête lente ? → Affiche `Chargement...` pendant la recherche.
- Log utile en dev : `console.log('🔍 condition:', weatherData.value?.condition)`

---

## 🔍 Exemple d’utilisation

- Tapez une ville dans la barre de recherche.
- Attendez quelques secondes (loader visible).
- Les données météo s’affichent : température, vent, heure, condition, etc.
- Cliquez sur 💾 pour la sauvegarder.
- L’historique des villes s’affiche plus bas.
- Vous pouvez supprimer une entrée.

---

## ✨ Exemple de résultats

- 🌤️ Ville : Paris
- 🌡️ Température : 17°C
- 💨 Vent : 12 km/h
- 🕒 Heure : 22/04/2025 à 14:45
- 🧭 Latitude / Longitude : 48.85 / 2.35

---

## ✅ À venir / Améliorations possibles

- 📍 Géolocalisation automatique
- 🌙 Mode sombre clair
- 🌐 Choix de la langue
- 📈 Prévision à 5 jours

---
## 🙋‍♀️ Développé par

**Anna Rafaella**  
💼 Test technique — Four Data  
📅 Date limite : 02/05/2025

---

## 🏁 Conclusion

Ce projet met en œuvre les bases solides d’une application full stack moderne, en combinant des outils puissants comme Vue.js, Symfony et Tailwind. Il démontre la capacité à interagir avec des APIs tierces, manipuler des données dynamiquement, gérer des erreurs, et proposer une expérience utilisateur agréable.
