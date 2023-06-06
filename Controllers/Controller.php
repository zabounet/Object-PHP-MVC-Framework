<?php
namespace App\Controllers;

// Classe ne pouvant être instanciée mais pouvant être héritée par d'autres classes.
abstract class Controller{

    public function render(string $fichier, array $data = [], string $template = 'default'): void{

        // On extrait le contenu de $donnees
        // extract démonte le tableau en transformant chaque etiquette/clé en variable 
        extract($data);

        // On démarre le buffer de sortie
        // A partir de maintenant, garde en mémoire tous les output et mets les dans une variable
        // Nécessaire, autrement le contenu du body est chargé en tout premier et déforme complêtement la page.
        ob_start();

        // On créé le chemin vers la vue
        require_once ROOT . '/Views/' . $fichier . '.php';

        // Stocke le contenu du buffer dans la variable
        $contenu = ob_get_clean();

        // Template de page
        require_once ROOT . '/Views/templates/' . $template . '.php';

    }
}
;?>