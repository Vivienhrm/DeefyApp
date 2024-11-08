<?php
require_once 'vendor/autoload.php';
use \iutnc\deefy\audio\tracks as tracks;
use \iutnc\deefy\render as R;
use \iutnc\deefy\audio\lists as L;
use \iutnc\deefy\exception as E;
use \iutnc\deefy\action as A;
use \iutnc\deefy\dispatch as D;
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
\iutnc\deefy\repository\DeefyRepository::setConfig(__DIR__ . '/config/deefy.db.ini');
} catch (Exception $e){
    echo $e->getMessage();
}

$action = new D\Dispatcher();
$action->run();

