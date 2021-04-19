<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * The type of the user
     * 0 anon
     * 1 logged
     * 2 admin
     *
     * @return int
     */
    public function getUserType(): int
    {
        return 0;
    }
}
