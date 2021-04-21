<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class UserAuthController extends AbstractController
{

    /** @var Utilisateur */
    private $currentUser;



    /**
     * Je passe par une méthode initialize plutôt que le constructeur.
     * Car sinon je n'arrive pas à érécupérer l'entityManager ni à récupérer les paramètres dans 'services.yaml'
     *
     * @return Utilisateur
     */
    public function initialize(): Utilisateur
    {
        // on try au cas où 'currentUser' n'est pas renseigné dans "services.yaml"
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
            $usr = new Utilisateur();

        $this->currentUser = $usr;

        return $usr;
    }

    public function getCurrentUser(): Utilisateur
    {
        return $this->currentUser;
    }

    public function isAdmin(): ?bool
    {
        return $this->currentUser->getIsAdmin();
    }

    public function isAnon(): bool
    {
        return is_null($this->currentUser->getId());
    }

    public function isLogged(): bool
    {
        return (!$this->isAnon()) && (!$this->isAdmin());
    }

    public function getUserType(): int
    {
        if ($this->isAdmin())
            return 2;
        elseif ($this->isLogged())
            return 1;
        elseif ($this->isAnon())
            return 0;
        else
            return -1;
    }

}
