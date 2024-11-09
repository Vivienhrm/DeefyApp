<?php

namespace iutnc\deefy\exception;

class TracksNotFoundException extends \Exception
{
    public function __construct($mess = ""){
        parent::__construct($mess);
    }
}