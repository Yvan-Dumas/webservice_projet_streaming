**API ENDPOINTS**

/register
POST
- name
- email
- password
retourne : access token, token_type, user


/login
POST
- name
- email
retourne : access token, token_type, user


/logout
GET


/musiques
GET
retourne : toutes les musiques ou uniquement les musiques gratuites si l'utilisateur n'est pas authentifié.


/playlists
GET
retourne : les playlists de l'utilisateur.
POST
- nom_playlist
retourne : playlist

/playlists/{playlist}/musiques
POST
- musique_id
retourne : playlist
