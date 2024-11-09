# DeefyApp
Deefy project in PHP

- []
Pour ce connecter via les fichiers du dépôts faut ajouter un fichier deefy.db.ini pour créer la liaison avec votre base de donnée.
exemple de fichier db.config.ini

```ini
[database]
host = localhost
user = root
password = root
dbname = test
```

Les données utilisées sont celles disponibles sur Arches.

## Fonctionnalitées

    Créer une Playlist vide :
Affiche un formulaire pour créer une playlist de zéro avec un nom. Elle sera ajoutée à vos playlists (stoqués en bd) et     deviendra playlist courante.

    Mes Playlists :
Accédez à toutes vos playlists. Chaque playlist peut être éditée en un clic elle devient alors playlist courante, stockée en session.
    
    Ma Playlist Courante :
On peut afficher la playlist sélectionnée pour une simplicité d'accès à la lecture. Cette fonctionnalité permet également d’ajouter une piste (podcast ou musique), avec extraction de métadonnées.

    Sécurité de données :
Les données utilisateurs et playlists sont sécurisées, avec des communications chiffrées avec notre serveur pour protéger les données sensibles tel que mots de passes. Des exceptions comme AuthException et InvalidPropertyValueException garantissent l’accès aux fonctionnalités privées et l’intégrité des données. Et des méthodes comme chaeckPlaylistOwner vérifie les droits d’accès de l’utilisateur connecté (vis-à-vis des playlists ici).

    Inscription :
Créez un compte utilisateur normal, rôle (STANDARD) non-administrateur.

    Connexion :
Connectez-vous en tant qu'utilisateur enregistré pour retrouver vos playlists, gérer vos préférences.

    Déconnexion :
À tout moment, vous pouvez vous déconnecter de votre compte pour sécuriser l'accès à vos informations. La déconnexion efface les informations de session en cours.
