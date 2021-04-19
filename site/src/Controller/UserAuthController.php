<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserAuthController extends AbstractController
{
//    /**
//     * @var int
//     */
//    private $userType;
//
//    /**
//     * MyWebsiteController constructor.
//     */
//    public function __construct()
//    {
//        $this->userType = $this->computeUserType();
//    }


    public function computeUserType(): int
    {
        $usr = $this->getCurrentUser();

        if (is_null($usr))
            return 0;
        else if ($usr->getIsadmin())
            return 2;
        else
            return 1;
    }

    public function getCurrentUser(): ?Utilisateur
    {
        $identifiant = $this->getParameter('moi');
        $manager = $this->getDoctrine()->getManager();
        $utilisateurRepository = $manager->getRepository(Utilisateur::class);

        /** @var Utilisateur $usr */
        $usr = $utilisateurRepository->findOneBy(['identifiant' => $identifiant]);
        return $usr;
    }

    public function getUserType(): int
    {
        return $this->computeUserType();
    }
}
