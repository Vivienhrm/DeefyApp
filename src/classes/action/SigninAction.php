<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthException;


class SigninAction extends Action
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
        return <<<HTML
<div class="form-signin w-100 m-auto">
    <form method="post" action="?action=signin">
        <h1 class="h3 mb-3 fw-normal text-center">Connexion</h1>

        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
            <label for="email">Email</label>
        </div>
        
        <div class="form-floating mb-3">
            <input type="password" class="form-control" id="pwd" name="password" placeholder="Password" required>
            <label for="pwd">Mot de passe</label>
        </div>

        <div class="form-check text-start my-3">
            <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
            <label class="form-check-label" for="flexCheckDefault">
                Se souvenir de moi
            </label>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit">Connexion</button>
    </form>
</div>
HTML;
    }

    
    protected function post(): string
    {
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST["password"], FILTER_SANITIZE_SPECIAL_CHARS);

        try {
            // Appel a la methode signin() pour authentifier l'utilisateur
            AuthnProvider::signin($email, $password);
            return <<<HTML
        <div class="alert alert-success mt-3 text-center" role="alert">
            Bonjour, vous êtes connecté(e) avec succès !
        </div>
HTML;
        } catch (AuthException $e) {
            // Gestion des exceptions d'authentification
            return $this->errorMessage($e->getMessage());
        }
    }

    
    protected function errorMessage(string $message): string
    {
        $formHtml = $this->get();
        return str_replace(
            '<button class="btn btn-primary w-100 py-2" type="submit">Se connecter</button>',
            '<button class="btn btn-primary w-100 py-2" type="submit">Se connecter</button><br>' .
            "<div class='alert alert-danger mt-3 text-center' role='alert'>$message</div>",
            $formHtml
        );
    }
}