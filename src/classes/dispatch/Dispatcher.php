<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayCurrentPlaylistAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\DisplayPlaylistsAction;
use iutnc\deefy\action\SigninAction;
use iutnc\deefy\action\LogoutAction;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthException;


class Dispatcher
{
    protected string $action;

    
    public function __construct()
    {
        $this->action = $_GET["action"] ?? "default";

    }

    
    public function run(): void
    {
        $action = null;
        switch ($this->action) {
            case "default":
                $action = new DefaultAction();
                break;
            case "playlist":
                $action = new DisplayPlaylistsAction();
                break;
            case "display-playlist":
                $action = new DisplayPlaylistAction();
                break;
            case "display-current-playlist":
                $action = new DisplayCurrentPlaylistAction();
                break;
            case "add-playlist":
                $action = new AddPlaylistAction();
                break;
            case "add-track":
                $action = new AddPodcastTrackAction();
                break;
            case "add-user":
                $action = new AddUserAction();
                break;
            case "signin":
                $action = new SigninAction();
                break;
            case "logout":
                $action = new LogoutAction();
                break;
            default:
                throw new AuthException("Action non reconnue");
        }

        $res = $action->execute();
        $this->renderPage($res);
    }

    
    private function renderPage(string $html): void
    {
        try {
            $user = AuthnProvider::getSignedInUser ();
            $connection = true;
        } catch (AuthException $e) {
            $user = null;
            $connection = false;
        }

        $nom = $connection ? $user->email : null;

        $optionsConnectionUser = $connection
            ? "<span class='me-3'>Bienvenue, $nom !</span><a class='btn btn-custom' href='index.php'>Accueil</a><a class='btn btn-custom' href='?action=logout'>Se déconnecter</a>"
            : "<a class='btn btn-custom' href='index.php'>Accueil</a><a class='btn btn-custom' href='?action=signin'>Se connecter</a><a class='btn btn-custom' href='?action=add-user'>S'inscrire</a>";

        $page = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deefy</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
</head>
<body class="bg-dark text-light">
    <header class="bg-black text-white p-3">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h4"><a class="nav-link" href="?action=default">Deefy</a></h1>
            <nav>
                $optionsConnectionUser
            </nav>
        </div>
    </header>

    <div class="d-flex flex-column flex-md-row">
        <aside class="col-md-3 sidebar">
            <h5 class="text-center mb-4">Bibliothèque
                <a class="btn btn-primary" href="?action=add-playlist" title="Ajouter une playlist">+</a>
            </h5>
            <ul class="nav flex-column text-center">
                <li class="nav-item">
                    <a class="nav-link" href="?action=playlist">Mes Playlists</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?action=add-playlist">Ajouter une playlist</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="?action=display-current-playlist">Afficher la playlist courante</a>
                </li>
            </ul>
        </aside>

        <main class="container mt-4">
            $html
            
        </main>
    </div>
</body>
</html>
HTML;
        echo $page;
    }
}
