<?php

namespace iutnc\deefy\users;

use InvalidArgumentException;
use iutnc\deefy\exception\InvalidPropertyNameException;

class User {

    public const ROLE_ADMIN = 100;
    public const ROLE_USER = 1;
    public const ROLE_GUEST = 0;

    private int $id; 
    private string $email; 
    private string $password;
    private int $role; 

    
    public function __construct(string $email, string $password, int $role=self::ROLE_GUEST) {
        $this->email = $email;
        $this->password = $password;

        // Definir le role
        $this->setRole($role); 
    }

    
    public function setId(int $id): void {
        $this->id = $id;
    }

    
    public function __get(string $param) {
        if (property_exists($this, $param)) {
            return $this->$param;
        } else {
            throw new InvalidPropertyNameException($param);
        }
    }

    
    public function setRole(int $role): void
    {
        if (!in_array($role, [self::ROLE_ADMIN, self::ROLE_USER, self::ROLE_GUEST])) {
            throw new InvalidArgumentException("Invalid role: $role");
        }
        $this->role = $role;
    }
}