# 🌤️ Application_Web_De_Meteo — Test technique Four Data

Application météo full-stack développée dans le cadre d'un test technique pour un stage chez **Four Data**.

---

## 🚀 Fonctionnalités

- Recherche météo par ville (via l’API [Open-Meteo](https://open-meteo.com/))
- Affichage des données météo actuelles : température, vent, heure, condition
- Sauvegarde de villes météo (CRUD) dans une base de données via un backend PHP (Symfony)
- Interface responsive et agréable avec Vue.js 3 (Composition API)
- Design dynamique : icône météo et fond évolutif selon la condition
- Historique des villes consultées, avec possibilité de suppression

---

## 🛠️ Technologies utilisées

### Frontend
- [Vue.js 3](https://vuejs.org/)
- [Axios](https://axios-http.com/)
- [Tailwind CSS](https://tailwindcss.com/) pour le style
- Vite pour le bundling

### Backend
- PHP 8.x
- Symfony (API Platform ou REST classique)
- SQLite (ou MySQL selon ton setup)

---

## 📦 Installation

### 1. Clone du projet

```bash
git clone https://github.com/Anna-Rafaella/Application_web_de_Meteo.git

cd Application_web_de_Meteo
