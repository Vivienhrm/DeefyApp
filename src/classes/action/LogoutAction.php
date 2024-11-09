<?php

namespace iutnc\deefy\action;


class LogoutAction extends Action
{
    
    public function execute(): string
    {
        switch ($this->http_method) {
            case "GET":
                return $this->get();
            case "POST":
                return $this->post();
            default:
                return "Méthode non autorisée.";
        }
    }

    
    protected function get(): string
    {
        $_SESSION['user'] = null;
        $_SESSION['playlist'] = null;
        return <<<HTML
        <div class="alert alert-success mt-3 text-center" role="alert">
            Vous vous êtes déconnecté(e) avec succès !
        </div>
HTML;
    }

    
    protected function post(): string
    {
        // La deconnexion va GET uniquement
        return "";
    }
}