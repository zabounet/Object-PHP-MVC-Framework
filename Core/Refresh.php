<?php

namespace App\Core;

class Refresh
{
    // Renvoie vers un lien donné. Utilisé principalement pour prévenir le double envoi d'un formulaire.
    static public function refresh(string $location)
    {
        header('Refresh:0.1;url=' . $location);
        exit;
    }
}
