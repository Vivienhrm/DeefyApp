<?php
namespace iutnc\deefy\exception;
class InvalidPropertyValueException extends \Exception{
    public function __construct($propertyName = "", $mess){
        parent::__construct("Valeur invalide pour la propriete: $propertyName car $mess");
    }
}
?>