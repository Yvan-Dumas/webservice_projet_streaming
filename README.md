# 🎵 API de Streaming Musical

Webservice développé avec Laravel gérant les musiques, les playlists et les achats.

---

## 🔒 Authentification

Les routes privées nécessitent l'envoi du token généré lors du login ou du register dans le header HTTP :
`Authorization: Bearer <votre_token>`

---

## 🚀 Liste des Endpoints

### 🔓 Routes Publiques

#### 🔹 Authentification
* **`POST /api/register`** | Crée un compte.
  * **Body :** `[ "name": "...", "email": "...", "password": "..." ]`
  * **Retourne :** `access_token`, `token_type`, `user`
* **`POST /api/login`** | Connecte un utilisateur.
  * **Body :** `[ "email": "...", "password": "..." ]`
  * **Retourne :** `access_token`, `token_type`, `user`

#### 🔹 Catalogue Musiques
* **`GET /api/musiques`** | Liste le catalogue (complet si connecté, musiques gratuites uniquement si anonyme).
* **`GET /api/musiques/{id}`** | Détails d'une musique (inclut ses styles et le statut `deja_achete`).

---

### 🔐 Routes Privées (Token requis)

#### 🔹 Compte & Bibliothèque
* **`GET /api/user`** | Récupère le profil de l'utilisateur connecté.
* **`GET /api/logout`** | Déconnexion (révoque le token actuel).
* **`GET /api/mes-achats`** | Liste toutes les musiques achetées (historique des prix et dates d'achat).

#### 🔹 Gestion des Playlists
* **`GET /api/playlists`** | Liste les playlists de l'utilisateur.
* **`GET /api/playlists/{id}`** | Détails d'une playlist (bloqué si elle ne vous appartient pas).
* **`POST /api/playlists`** | Crée une playlist.
  * **Body :** `[ "nom_playlist": "..." ]`
* **`PUT /api/playlists/{id}`** | Renomme une playlist.
  * **Body :** `[ "nom_playlist": "..." ]`
* **`DELETE /api/playlists/{id}`** | Supprime une playlist (nettoie automatiquement les musiques associées).

#### 🔹 Contenu des Playlists & Magasin
* **`POST /api/playlists/{id}/musiques`** | Ajoute une musique à une playlist (vérifie l'achat si payante, évite les doublons).
  * **Body :** `[ "musique_id": ... ]`
* **`DELETE /api/playlists/{playlist_id}/musiques/{musique_id}`** | Retire une musique d'une playlist.
* **`POST /api/musiques/{id}/acheter`** | Achète une musique payante (ajoute le prix et la date du jour dans la table pivot).
