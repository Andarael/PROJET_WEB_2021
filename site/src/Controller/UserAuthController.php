<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserAuthController extends AbstractController
{
    /**
     * Il y a 3 types d'utilisateurs :
     * 0 -> anonyme
     * 1 -> identifié
     * 2 -> admin
     * Le type de d' utilisateur est utilisé dans les autres controllers pour vérifier si l' utilisateur
     * actuel a bien le droit d' effectuer  l' action demandée
     *
     * @return int
     */
    public function getUserType(): int
    {
        return $this->computeUserType();
    }

    /**
     * Calcul le type d' utilisateur actuel
     * On utilise l' id de l'utilisateur actuel pour déterminer s'il est authentifié
     *
     * @return int
     */
    public function computeUserType(): int
    {
        $usr = $this->getCurrentUser();

        if (is_null($usr->getId()))
            return 0;
        else if ($usr->getIsadmin())
            return 2;
        else
            return 1;
    }

    /**
     * L' utilisateur actuel n' est jamais null.
     * C'est pour éviter des potentiels problèmes avec les templates ou les accès aux fields de la classe
     *
     * @return Utilisateur
     */
    public function getCurrentUser(): Utilisateur
    {
        // dans le cas où 'currentUser' n'est pas renseigné dans "services.yaml"
        try {
            $identifiant = $this->getParameter('currentUser');
        } catch (Exception $exception) {
            $identifiant = -1;
        }


        $manager = $this->getDoctrine()->getManager();
        $utilisateurRepository = $manager->getRepository(Utilisateur::class);

        /** @var Utilisateur $usr */
        $usr = $utilisateurRepository->find($identifiant);

        if (is_null($usr))
            return new Utilisateur();

        return $usr;
    }
}
