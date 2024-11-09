<?php

namespace iutnc\deefy\exception;

class InvalidPropertyNameException extends \Exception{
    public function __construct($propertyName = ""){
        parent::__construct("Invalid property: $propertyName");
    }
}
?>