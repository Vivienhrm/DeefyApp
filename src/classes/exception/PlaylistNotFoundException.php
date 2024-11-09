<?php

namespace iutnc\deefy\exception;

use Exception;

class PlaylistNotFoundException extends Exception
{
    public function __construct($mess = ""){
        parent::__construct($mess);
    }
}