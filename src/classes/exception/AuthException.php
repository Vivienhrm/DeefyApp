<?php

namespace iutnc\deefy\exception;

class AuthException extends \Exception {

    public function __construct($message = ""){
        parent::__construct($message);
    }
}