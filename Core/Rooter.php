<?php

namespace App\Core;

use App\Controllers\FormateurController;
use App\Controllers\RooterController;

//Rooter principal
class Rooter
{

    public function start()
    {
        // Désactive l'affichage des erreurs .
        ini_set('display_errors', 'off');
        set_error_handler(['\App\Core\CustomException', 'PhpErrors'], E_ALL);
        register_shutdown_function(['\App\Core\CustomException', 'PhpFatalErrors']);

        // Durée de vie du cookie de session (en secondes)
        $sessionLifetime = 900;

        // Définit la durée de vie du cookie de session afin qu'il expire après 10 minutes d'inactivité. 
        // Cela permet de gérer la persistance de la session dans le navigateur du client.
        session_set_cookie_params($sessionLifetime);

        // Définit la durée de vie maximale de la session côté serveur à 10 minutes. 
        // Cela permet de gérer la suppression automatique des sessions inactives par le garbage collector de PHP.
        ini_set('session.gc_maxlifetime', $sessionLifetime);

        // Démarre la session

        session_start();

        // Vérifie si la variable 'last_activity' de la session est définie
        if (isset($_SESSION['last_activity'])) {
            // Calcule le temps écoulé depuis la dernière activité de la session 
            // en soustrayant le timestamp actuel du timestamp de la dernière activité.
            $inactiveTime = time() - $_SESSION['last_activity'];

            // Vérifie si la session a expiré
            if ($inactiveTime > $sessionLifetime) {

                // La session a expiré, on la détruit
                session_unset();

                // Supprime toutes les variables de session enregistrées.
                // Détruit complètement la session en cours, y compris l'ID de session associé.
                session_destroy();

                // Optionnel : Réinitialise le tableau de session à un tableau vide. 
                // Cela permet de s'assurer que toutes les données de session sont supprimées.
                $_SESSION = array(); // Optional: Reset the session array
            }
        }
        // Met à jour la variable 'last_activity' de la session avec le timestamp actuel, 
        // indiquant que la session a été activement utilisée à ce moment précis.
        $_SESSION['last_activity'] = time();


        // On gère les paramètres d'URL
        // p=controller/method/parameters
        // On sépare les paramètres dans un tableau
        if (isset($_GET['p'])) {

            $params = explode('/', $_GET['p']);

            if ($params[0] != "") {
                // On a au moins 1 paramètre
                // On récupère le nom du controller à instancier
                // On met une majuscule en 1ère lettre, on ajoute le namepace complet avant et on ajouter "Controller" après
                $controller = '\\App\\Controllers\\' . ucfirst(array_shift($params)) . 'Controller';

                // On instancie le controller s'il existe
                if (class_exists($controller)) {
                    $controller = new $controller();

                    // On récupère le 2eme paramètre d'URL
                    $action = (isset($params[0])) ? array_shift($params) : 'index';
                } else {
                    http_response_code(404);
                    echo "<h1>La page recherchée n'existe pas</h1>";
                    exit;
                }

                if (method_exists($controller, $action)) {
                    // Si il reste des paramètres on les passe à la méthode
                    // call_user_func_array envoie a une fonction un tableau de paramètres
                    (isset($params[0])) ? call_user_func_array([$controller, $action], $params) : $controller->$action();
                } else {
                    http_response_code(404);
                    echo "<h1>La page recherchée n'existe pas</h1>";
                    exit;
                }

                // On a pas de paramètres donc on instancie le controller par défaut
            } else {
                // Si il y a déjà une session d'ouverte
                if (isset($_SESSION['formateur']) || isset($_SESSION['admin'])) {
                    $controller = new RooterController;
                    $controller->index();
                }
                // Si aucune session n'est ouverte
                else {
                    $formateur = new FormateurController;
                    $formateur->login();
                }
            }
        } else {
            $home = new RooterController;
            $home->index();
        }
    }
}
