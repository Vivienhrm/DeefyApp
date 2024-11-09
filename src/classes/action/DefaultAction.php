<?php
namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthException;


class DefaultAction extends Action
{
    
    public function execute(): string
    {
        try {
            $user = AuthnProvider::getSignedInUser();
            $message = <<<HTML

<p>Bonjour $user->email, Vous avez accès à toutes vos playlists dans la rubrique : Mes Playlists! </p>
HTML;

        } catch (AuthException $e){
            $message = <<<HTML
<p></p>
<p class="centered-title">Connectez-vous pour accéder à votre contenu, découvrir de nouveaux artistes, et personnaliser votre écoute.</p>
HTML;

        }

        return <<<HTML

<div class="main-container mt-4">
<h2 class="centered-title" class="">Bienvenue sur Deefy!</h2>
    $message
</div>
HTML;
    }
    
    protected function get() : string
    {
        return "";
    }


    
    protected function post() : string
    {

        return "";
    }
}