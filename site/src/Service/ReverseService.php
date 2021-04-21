<?php

namespace App\Service;

class ReverseService
{
    /**
     * @param String $str
     *
     * @return String
     */
    public function reverseString(string $str): string
    {
        return strrev($str);
    }
}

/*Fichier par josué Raad et Florian Portrait*/
