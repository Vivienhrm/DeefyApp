<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthException;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\users\User;


class AuthnProvider {    
    public static function signin(string $email, string $passwd2check): void
    {
        $repository = DeefyRepository::getInstance();
        $user = $repository->getUser ($email);

        if (!password_verify($passwd2check, $user->password)) {
            throw new AuthException("Impossible de se connecter : nom d'utilisateur ou mot de passe incorrect.");
        }

        $_SESSION['user'] = serialize($user);
    }


    public static function getSignedInUser(): User {
    if (!isset($_SESSION['user']) || !is_string($_SESSION['user'])) {
        throw new AuthException("Vous n'avez pas l'autorisation d'accéder à cette fonctionnalité.");
    }

    // Deserialisation des données du user
    $user = unserialize($_SESSION['user']);

    // Verification du type de l'objet deserialise
    if (!$user instanceof User) {
        throw new AuthException("Données utilisateur invalides ou utilisateur non connecté.");
    }

    return $user;
}



    public static function register(string $email, string $password): void {
        $repo = DeefyRepository::getInstance();

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AuthException("L'adresse email est invalide");
        }

        if (self::checkPassword($password, 10)) {
            $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
            $repo->addUser ($email, $hash, User::ROLE_USER);
        } else {
            throw new AuthException("Le mot de passe doit contenir au moins 10 caractères, incluant une majuscule, une minuscule, un chiffre et un caractère spécial.");
        }
    }

       
    public static function checkPassword(string $pass, int $minimumLength = 8): bool
    {
        $length = (strlen($pass) >= $minimumLength);
        $digit = preg_match("#[\d]#", $pass);
        $special = preg_match("#[\W]#", $pass);
        $lower = preg_match("#[a-z]#", $pass);
        $upper = preg_match("#[A-Z]#", $pass);

        // Tous les criteres doivent etre vrais
        return $length && $digit && $special && $lower && $upper;
    }

}



?>