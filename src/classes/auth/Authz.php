<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthException;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\users\User;

class Authz
{
    
    public static function checkRole(int $role) : bool
    {
        $user = AuthnProvider::getSignedInUser();
        if ($user->role === $role){
            return true;
        }
        return false;
    }

    
    public static function checkPlaylistOwner(int $id) : bool
    {
        $user = AuthnProvider::getSignedInUser();

        $repository = DeefyRepository::getInstance();
        $role = $user->role;

        if($role===User::ROLE_ADMIN){
            return true;
        }

        if($repository->isPlaylistOwner($id,$user->id)){
            return true;
        }

        return false;
    }
}