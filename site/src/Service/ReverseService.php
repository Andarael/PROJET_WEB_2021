<?php

namespace App\Service;

/**
 * Class ReverseService
 *
 * Un service symfony qui se contente d'inverser une chaîne de caractères
 * L'autowiring s'occupe de passer ce service aux fonctions qui le demandent
 *
 */
class ReverseService
{
    /**
     * @param String $str la chaîne à inverser
     *
     * @return String la chaîne inversée
     */
    public function reverseString(string $str): string
    {
        return strrev($str);
    }
}

/*Fichier par josué Raad et Florian Portrait*/
