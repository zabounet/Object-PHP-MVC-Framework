<?php 
namespace App;

class Autoloader{
    static function register(){
        //Detecte les chargements de classe (new qqch)
        spl_autoload_register([
            //Retourne le nom complet + le namespace de la classe actuelle
            __CLASS__,
            //Lance la fonction 'autoload'
            'autoload'
        ]);
    }

    static function autoload($class){
        //On récupère dans $class la totalité du namespace de la classe concernée
        // On retire App\
        $class = str_replace(__NAMESPACE__ . '\\', '', $class);

        // On remplace les \ par des / 
        $class = str_replace('\\', '/', $class);

        $fichier = __DIR__ . '/' . $class . '.php';

        // On vérifie si le ficher existe
        if(file_exists($fichier)){
            require_once $fichier;
        }
    }
}
?>
