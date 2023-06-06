<?php

namespace App\Core;

use Exception;

class CustomException extends Exception
{
    public function __construct(string $message='',int $code=0,$previous=null)
    {
        parent::__construct($message, $code, $previous);
    }

	// Nécessaire lors de la création d'un gestionnaire d'exception personnalisé afin de pouvoir gérer les erreur fatales.
    static function PhpFatalErrors()
	{
		$lastError = error_get_last();
		if(!is_null($lastError))
        static::PhpErrors($lastError['type'],$lastError['message'],$lastError['file'],$lastError['line']);
    }
    
	/* Gestionnaire d'erreurs */
	static function PhpErrors($errno,$errstr,$errfile,$errline)
	{
        $Action = [];
        $Action['msg'] 		=''; // Le message renvoyé par l'erreur
		$Action['exit'] 	=true; // Arrête ou non de l'execution du script
		$Action['display']	=false;	
		$Action['notify']	=false;	// Apparition ou non de l'erreur
		
		switch ($errno)
		{
			// Notice
			case E_USER_NOTICE :
			case E_NOTICE :
			{
			    $Action['exit'] 	= false;
			    $Action['display'] 	= false;
			    $Action['notify'] 	= true;
			    $Action['msg'] 	= "<h1> Désolé, une erreur est survenue </h1>";
				break;
			}

			// Warnings
			case E_COMPILE_WARNING :
			case E_CORE_WARNING :
			case E_USER_WARNING :
			case E_WARNING :
			case E_DEPRECATED :
			case E_USER_DEPRECATED :
			{
			    $Action['exit'] 	= false;
			    $Action['display'] 	= false;
			    $Action['notify'] 	= true;
			    $Action['msg'] 	= "<h1> Désolé, une erreur est survenue </h1>";
			    break;
			}

			// Erreur de syntaxe
			case E_PARSE :
			{
			   	$Action['exit'] 	= false;
			    $Action['display'] 	= false;
			    $Action['notify'] 	= true;
			    $Action['msg'] 	= "<h1 style='padding: 0; margin: 0 ;font-size: 3rem; color: red; margin-top: 45vh; text-align: center;'>Oups ! Il semblerait que les développeurs aient fait une bourde. Veuillez contacter l'administrateur du site afin de résoudre le problème.</h1>";
			    break;
			}
			
			// Erreur fatales
			case E_COMPILE_ERROR :
			case E_CORE_ERROR :
			case E_USER_ERROR :
			case E_ERROR :
			{
			   	$Action['exit'] 	= true;
			    $Action['display'] 	= false;
			    $Action['notify'] 	= true;
			    $Action['msg'] 	= "<h1 style='padding: 0; margin: 0 ;font-size: 3rem; color: red; margin-top: 45vh; text-align: center;'>/!\ Une erreur fatale s'est produite /!\</h1> <p style='text-align: center; font-size:1.25rem;'>Si le problème persiste, veuillez contacter l'admnistrateur.</p>";
			    break;
			}

			// Autres erreurs
			default :
			{
			    $Action['exit'] 	= false;
			    $Action['display'] 	= false;
			    $Action['notify'] 	= true;
			    $Action['msg'] 	= "<h1> Désolé, une erreur est survenue </h1>";
			    break;
			}
	    }
	    
	    if($Action['notify'] === true)
	    {
		    echo $Action['msg'];
		}
	    if($Action['exit'] === true)
	    {
		    exit();
		}
	}
}