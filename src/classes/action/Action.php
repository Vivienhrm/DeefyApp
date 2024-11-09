<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\Authz;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\users\User;

abstract class Action {

    protected ?string $http_method = null;
    protected ?string $hostname = null;
    protected ?string $script_name = null;

    public function __construct(){

        // Initialise les attributs HTTP et script à partir des superglobaux
        $this->http_method = $_SERVER['REQUEST_METHOD'] ?? null;
        $this->hostname = $_SERVER['HTTP_HOST'] ?? null;
        $this->script_name = $_SERVER['SCRIPT_NAME'] ?? null;
    }
    
    public function execute() : string
    {
        try {
            // Verifie si l'utilisateur est autorise
            $checkRoleUser = Authz::checkRole(User::ROLE_USER);
            $checkRoleAdmin = Authz::checkRole(User::ROLE_ADMIN);
        } catch (AuthException $e){

            // Retourne un message d'erreur en cas d'exception d'autorisation
            return $this->errorMessage($e->getMessage());
        }
        // Exécute la méthode appropriée en fonction de la requête HTTP (GET ou POST)
        switch ($this->http_method) {
            case "GET":
                return $this->get(); 
            case "POST":
                return $this->post();
            default:
                return "Methode non autorisée";
        }
    }


    // Methodes abtraites
    abstract protected function get() :string;

    abstract protected function post() :string;


    
    protected function errorMessage(string $message): string
    {
        return "<div class='alert alert-danger'>$message</div>";
    }
}