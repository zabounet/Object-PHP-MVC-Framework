<?php
use App\Autoloader;
use App\Core\Rooter;

// On défini une constante contenant le dossier racine du projet
define('ROOT', dirname(__DIR__));

// On importe l'autoloader
require_once ROOT.'/Autoloader.php';
Autoloader::register();

// On instancie Rooter 
$app = new Rooter;

// On démarre l'application
$app->start();
;?>