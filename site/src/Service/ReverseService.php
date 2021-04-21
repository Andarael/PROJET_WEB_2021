<?php

namespace App\Service;

class ReverseService
{
    /**
     * @param String $str
     *
     * @return String
     */
    public function reverseString(string $str): String
    {
        return strrev($str);
    }
}
