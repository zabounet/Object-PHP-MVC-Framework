<?php
namespace App\Core;

use PDO;
use PDOException;

class Db extends PDO{
    //Instance unique de la classe
    private static $instance;

    private const DBHOST = "localhost";
    private const DBNAME = "planning";
    private const DBUSER = "AN";
    private const DBPASS = "projetbadge";

    private function __construct(){
        //DSN
        $_dsn = 'mysql:dbname=' . self::DBNAME . ';host=' . self::DBHOST;

        try{
            //New PDO
            parent::__construct($_dsn, self::DBUSER, self::DBPASS);

            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');

            //Mode de fetch (fetch_obj = retourne les resultats en tant qu'objets)
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            //Déclenche une exception dés lors qu'il y a un problème
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e){
            die($e->getMessage());
        }
    }

    //Connexion utilisant le design pattern singleton
    public static function getInstance(){
        // Si aucune instance n'existe, alors créé une instance.
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

}
?>