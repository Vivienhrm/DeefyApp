<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\users\User;

class AddPlaylistAction extends Action
{


    // Formulaire pour ajouter une nouvelle playlist (utilise lors de la requete GET)
    protected function get(): string
    {
        return <<<HTML
        <div class="form-playlist">
            <h1 class="h3 mb-3 fw-normal text-center">Ajouter une Playlist</h1>
            <form method="post" action="?action=add-playlist">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nomPlaylist" name="nomPlaylist" placeholder="Entrez le nom de la playlist" required>
                    <label for="nomPlaylist">Nom de la playlist</label>
                </div>
                <button class="btn btn-primary w-100" type="submit" name="valider">Valider</button>
            </form>
        </div>
        HTML;
    }


    // Traiter ajout apres soumission du formulaire
    protected function post(): string
    {
//         // Récupère et nettoie le nom de la playlist
//         $nomPlaylist = filter_var($_POST["nomPlaylist"], FILTER_SANITIZE_SPECIAL_CHARS);
//         $nomPlaylist = trim($nomPlaylist); // Supprime les espaces avant et après

//         // Vérifie si le nom de la playlist est valide
//         if (empty($nomPlaylist)) {
//             return "Le nom de la playlist ne peut pas être vide."; // Message d'erreur
//         }

//         // Crée une nouvelle playlist et la stocke dans la session
//         $playlist = new Playlist($nomPlaylist);

//         $repository = DeefyRepository::getInstance();
//         $playlist = $repository->saveEmptyPlaylist($playlist);

//         $user = AuthnProvider::getSignedInUser();

//         $repository->assignPlaylite2User($user->id,$playlist->id);
//         $_SESSION["playlist"] = serialize($playlist);
//         $render = new AudioListRenderer($playlist);
//         $res = $render->render();


//         // Retourne le rendu HTML de la playlist
//         return <<<HTML
// <div class="playlist-container">
//     $res
//     <div class="text-center mt-4">
//         <a href="?action=add-track" class="btn btn-primary">Ajouter une piste</a>
//     </div>
// </div>
// HTML;
        // Recupere et nettoie le nom de la playlist
        $nomPlaylist = filter_var($_POST['nomPlaylist'], FILTER_SANITIZE_STRING);
        // Cree une nouvelle instance de playlist avec le nom fourni
        $playlist = new Playlist($nomPlaylist);

        // Sauvegarde la playlist dans depot et l'associe au user connecte
        $repository = DeefyRepository::getInstance();
        $playlist = $repository->saveEmptyPlaylist($playlist);
        $user = AuthnProvider::getSignedInUser();

        // Stocke la playlist dans la session pour la rendre accessible
        $repository->assignPlaylite2User($user->id,$playlist->id);
        $_SESSION['playlist'] = serialize($playlist);

        // Rendu de la playlist nouvellement creee
        $renderer = new AudioListRenderer($playlist);
        $res = $renderer->render();
        return <<<HTML
<div class="playlist-container">
    $res
    <div class="text-center mt-4">
        <a href="?action=add-track" class="btn btn-primary">Ajouter une piste</a>
    </div>
</div>
HTML;
    }

}