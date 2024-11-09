<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\auth\Authz;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\users\User;

class DisplayPlaylistsAction extends Action
{


    public function execute(): string
    {
        $repository = DeefyRepository::getInstance();
        $res  = <<<HTML
<div class="playlist-container">
<h1 class="h3 mb-4 text-center">Vos playlists : </h1>
HTML;
        try {
            // Verifiez le role utilisateur
            $checkRoleUser = Authz::checkRole(User::ROLE_USER);
            $checkRoleAdmin = Authz::checkRole(User::ROLE_ADMIN);

            $user = AuthnProvider::getSignedInUser();
            if($checkRoleAdmin){
                $listPlaylist = $repository->listPlaylist();
            } else{
                $listPlaylist = $repository->listPlaylist2User($user->id);
            }

        } catch (AuthException $e){
            return $this->errorMessage($e->getMessage());
        }

        $compteur =1;
        foreach ($listPlaylist as $playlist){
            $res .= <<<HTML
<a class="nav-link" href="?action=display-playlist&id=$playlist->id">
<div class="track-item mb-3 "">
    <strong>$compteur - {$playlist->nom}</strong>
</div>
</a>
HTML;
            $compteur++;
        }
        $res .= <<<HTML
    <div class="text-center mt-4">
        <a href="?action=add-playlist" class="btn btn-primary">Creer une Playlist</a>
    </div>
</div>
HTML;
        return $res;
    }

    protected function get(): string
    {
        return ";";
    }

    protected function post(): string
    {
        return "";
    }


}