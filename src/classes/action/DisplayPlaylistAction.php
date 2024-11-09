<?php
namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\auth\Authz;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\exception\PlaylistNotFoundException;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\users\User;


class DisplayPlaylistAction extends Action
{



    protected function get(): string
    {
        $repository = DeefyRepository::getInstance();

        // Recupere l'ID de la playlist à partir de l'URL
        $playlistId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        // Validation de l'ID de la playlist
        if ($playlistId === false || $playlistId <= 0) {
            return $this->errorMessage("ID de playlist invalide.");
        }

        try {
            // Verifie si il s'agit de sa playlist
            if (!Authz::checkPlaylistOwner($playlistId)) {
                return $this->errorMessage("Vous n'avez pas l'autorisation d'accéder à cette playlist.");
            }

            // Recupere la playlist par son ID
            $playlist = $repository->findPlaylistById($playlistId);
            $renderer = new AudioListRenderer($playlist);
            $resultat = $renderer->render();
        } catch (AuthException | PlaylistNotFoundException $e) {
            return $this->errorMessage($e->getMessage());
        }

        // Stocke la playlist courante en session
        $_SESSION["playlist"] = serialize($playlist);

        // Affiche le contenu HTML de la playlist avec l'option d'ajout de pistes
        return <<<HTML
<div class="playlist-container">
    $resultat
    <div class="text-center mt-4">
        <a href="?action=add-track" class="btn btn-primary">Ajouter une piste</a>
    </div>
</div>
HTML;
    }

    
    protected function post(): string
    {
        return "";
    }


}