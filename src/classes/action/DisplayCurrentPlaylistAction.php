<?php

namespace iutnc\deefy\action;

use iutnc\deefy\render\AudioListRenderer;

class DisplayCurrentPlaylistAction extends Action
{

    protected function get(): string {
        // Verifie si une playlist est stockee dans la session
        if (isset($_SESSION['playlist'])) {
            // Recupere et deserialise la playlist depuis la session
            $playlist = unserialize($_SESSION['playlist']);
            $renderer = new AudioListRenderer($playlist);
            $resultat = $renderer->render();
            $resultat = <<<HTML
<div class="playlist-container">
    $resultat
    <div class="text-center mt-4">
        <a href="?action=add-track" class="btn btn-primary">Ajouter une piste</a>
    </div>
</div>
HTML;
        } else {
            $resultat = $this->errorMessage("Vous avez pas de playliste en sessions");
        }
        return $resultat;
    }

    // Methode non utilise ici
    protected function post(): string
    {
       return "";
    }


}