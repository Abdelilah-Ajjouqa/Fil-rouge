# 📘 Cahier des Charges – Projet YouVed

## 1. Présentation du projet

**Titre du projet :** Création d'un site web **YouVed**

**Contexte :**  
Développement d'une plateforme web permettant aux utilisateurs de consulter et de publier des photos et des vidéos. L’objectif est de proposer une expérience intuitive et attrayante tout en assurant une gestion fluide des contenus multimédias.

**Objectif principal :**
- Visualiser des photos et des vidéos publiées par d'autres utilisateurs
- Poster leurs propres contenus multimédias

**Public cible :**
- Toute personne souhaitant partager ou découvrir du contenu visuel (photos et vidéos)

---

## 2. Fonctionnalités

### Frontend (HTML, CSS, Blade (from Laravel) & JavaScript)

#### Affichage des contenus
- Grille dynamique pour l'affichage des médias
- Miniatures cliquables pour affichage plein écran
- Chargement infini (*infinite scrolling*)

#### Publication de contenu
- Formulaire d’importation de photo/vidéo
- Prévisualisation avant la publication

#### Interactions utilisateur
- Boutons de recherche, tri et filtrage (photo/vidéo)
- Design responsive (PC, mobile, tablette)

### Backend (Laravel)

#### Gestion des utilisateurs
- Authentification (inscription, connexion)

#### Gestion des contenus
- Sauvegarde des médias sur le serveur
- Stockage des métadonnées (titre, description, date, utilisateur)

### Base de données (PostgreSQL)

#### Tables principales
- users
- posts
- comments
- media
- tags
- saved_posts

---

## 3. Arborescence du site

- **Page d’accueil**
  - Grille des contenus récents
  - Barre de recherche

- **Page de détail**
  - Affichage plein écran
  - Informations : titre, description, auteur, date

- **Page de publication**
  - Formulaire de dépôt de média

- **Page de profil utilisateur**
  - Liste des contenus publiés
  - Informations de profil

- **Page de connexion/inscription**
  - Formulaire d’authentification

---

## 4. Contraintes techniques

**Langages et technologies :**
- HTML5, CSS3 (Tailwind CSS)
- Blade (from Laravel) & JavaScript ES6
- Laravel
- PostgreSQL

**Hébergement :**
- Serveur supportant Laravel & PostgreSQL
- Stockage adapté aux fichiers médias

**Compatibilité :**
- Tous navigateurs modernes (Chrome, Firefox, Edge, Safari)
- Design responsive (smartphones & tablettes)

---

## 5. Design

**Charte graphique :**
- Palette minimaliste (blanc, gris, couleurs d’accent)
- Typographie moderne et lisible

**Wireframes :**
- Maquettes pour valider les mises en page des pages principales

---

## 6. Contraintes fonctionnelles

**Sécurité :**
- Validation des fichiers uploadés
- Protection des données : mot de passe hashé, HTTPS

**Accessibilité :**
- Navigation clavier
- Support des lecteurs d’écran

---

## 7. Planning et livrables

### Phase 1 : Conception (2 semaines)
- Rédaction du cahier des charges

### Phase 2 : Développement (6 semaines)
- Frontend : structure + design
- Backend : gestion des utilisateurs et des contenus

### Livrables :
- Site web fonctionnel et documenté
- Code source + base de données
- Documentation utilisateur
