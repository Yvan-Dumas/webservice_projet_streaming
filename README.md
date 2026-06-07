# 🎵 API de Streaming Musical (Webservice)

Bienvenue sur la documentation de l'API de notre service de streaming musical développé avec Laravel 13. Cette API gère l'authentification des utilisateurs, la consultation du catalogue de musiques, le système d'achat, ainsi que la gestion complète des playlists.

---

## 🔒 Authentification & Sécurité

L'API utilise **Laravel Sanctum** pour sécuriser ses routes privées via des tokens d'accès. 
Pour toutes les requêtes nécessitant d'être authentifié, vous devez inclure le token dans les headers HTTP :
`Authorization: Bearer <votre_token_sanctum>`

---

## 🚀 Liste des Endpoints

### 🔓 Routes Publiques

#### Authentification

* **`POST /api/register`** : Créer un nouveau compte utilisateur.
    * **Body (JSON) :**
        ```json
        {
          "name": "John Doe",
          "email": "john@example.com",
          "password": "votre_mot_de_passe"
        }
        ```
    * **Réponse (201) :** `access_token`, `token_type`, données de l'utilisateur (`user`).

* **`POST /api/login`** : Connexion à un compte existant.
    * **Body (JSON) :**
        ```json
        {
          "email": "john@example.com",
          "password": "votre_mot_de_passe"
        }
        ```
    * **Réponse (200) :** `access_token`, `token_type`, données de l'utilisateur (`user`).

#### Catalogue des Musiques

* **`GET /api/musiques`** : Récupérer le catalogue.
    * *Comportement :* Retourne toutes les musiques si l'utilisateur est authentifié, ou **uniquement les musiques gratuites** si le visiteur est anonyme.
    * **Réponse (200) :** Un tableau contenant la liste des musiques et leurs styles.

* **`GET /api/musiques/{musique}`** : Consulter les détails d'un morceau spécifique.
    * **Paramètre :** `{musique}` = ID de la musique.
    * **Réponse (200) :** L'objet musique complet enrichi de ses styles et d'un booléen `deja_achete` (utile pour l'affichage dynamique des boutons côté Front-End).

---

### 🔐 Routes Privées (Requiert le Token Sanctum)

#### Compte & Sessions

* **`GET /api/user`** : Récupérer le profil de l'utilisateur actuellement connecté.
* **`GET /api/logout`** : Déconnecter l'utilisateur et révoquer son token actuel.

#### Gestion des Playlists (Enveloppes)

* **`GET /api/playlists`** : Lister toutes les playlists appartenant à l'utilisateur connecté.
    * **Réponse (200) :** Liste des playlists incluant leurs musiques associées et les styles de ces musiques.

* **`GET /api/playlists/{playlist}`** : Consulter une playlist précise.
    * **Paramètre :** `{playlist}` = ID de la playlist.
    * **Sécurité :** Renvoie une erreur `403` si la playlist n'appartient pas à l'utilisateur connecté.

* **`POST /api/playlists`** : Créer une nouvelle playlist vide.
    * **Body (JSON) :**
        ```json
        {
          "nom_playlist": "Mes favoris Rock"
        }
        ```
    * **Réponse (201) :** L'objet playlist créé.

* **`DELETE /api/playlists/{playlist}`** : Supprimer définitivement une playlist.
    * **Paramètre :** `{playlist}` = ID de la playlist à supprimer.
    * *Comportement :* Détache automatiquement toutes les musiques liées dans la table d'association avant de supprimer la playlist.

#### Contenu des Playlists (Relations)

* **`POST /api/playlists/{playlist}/musiques`** : Ajouter une musique dans une playlist.
    * **Paramètre :** `{playlist}` = ID de la playlist ciblée.
    * **Body (JSON) :**
        ```json
        {
          "musique_id": 4
        }
        ```
    * **Sécurité :** Vérifie si la musique est gratuite ou si elle a bien été achetée par l'utilisateur si elle est payante (`prix > 0`). Évite également les doublons.
    * **Réponse (200) :** La playlist rechargée et synchronisée avec sa liste complète de musiques.

* **`DELETE /api/playlists/{playlist}/musiques/{musique}`** : Retirer une musique d'une playlist.
    * **Paramètres :** `{playlist}` = ID de la playlist, `{musique}` = ID de la musique à retirer.
    * **Réponse (200) :** La playlist mise à jour (compteur `nb_titres` décrémenté et liste de morceaux rafraîchie).

#### Magasin / Achats

* **`POST /api/musiques/{musique}/acheter`** : Simuler l'achat d'un morceau payant.
    * **Paramètre :** `{musique}` = ID du morceau à acheter.
    * *Comportement :* Crée l'association dans la table pivot `achats`. Bloque la requête si le morceau est gratuit ou si l'utilisateur possède déjà le titre.
