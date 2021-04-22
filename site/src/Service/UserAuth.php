<?php

namespace App\Service;

use Exception;
use App\Entity\Utilisateur;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Cette classe sert à l'authentification de l'utilisateur actuel depuis le fichier 'services.yaml'
 * Si l'on veut changer la manière dont on défini l'utilisateur actuel par la suite,
 * il suffit d'éditer cette classe sans toucher au reste du code du site
 *
 * On fournit l'utilisateur actuel extrait de la bdd, ou un nouvel utilisateur vide s'il n'exite pas
 *
 * On a des méthodes pour récupérer le niveau d'authentification de l'utilisateur (anonyme, authentifié, ou admin)
 *
 * @package App\Service
 */
class UserAuth
{

    /**
     * L'utilisateur n'est jamais null (au pire il sera vide)
     * @var Utilisateur
     */
    private $currentUser;

    /**
     * UserAuth constructor.
     * On initialise l'utilisateur actuel.
     * On récupère l' entityManager et le repository via l'autoWiring
     */
    public function __construct(ParameterBagInterface $parameterBag, ManagerRegistry $manager)
    {
        // on try au cas où "currentUser" n'est pas renseigné dans "services.yaml"
        try {
            $identifiant = $parameterBag->get('currentUser');
        } catch (Exception $exception) {
            $identifiant = -1;
        }

        /** @var Utilisateur $usr */
        $usr = $manager->getManager()->getRepository(Utilisateur::class)->find($identifiant);

        if (is_null($usr))
            $usr = new Utilisateur();

        $this->currentUser = $usr;
    }

    public function getCurrentUser(): Utilisateur
    {
        return $this->currentUser;
    }

    /**
     * Si l'utilisateur n'a pas d'id, on est dans où on ne l'a pas trouvé dans la bdd
     * Donc c'est un nouvel utilisateur vide qui a été créé dans le constructeur
     *
     * @return bool
     */
    public function isAnon(): bool
    {
        return is_null($this->currentUser->getId());
    }

    /**
     * Si l'utilisateur est enregistré dans la bdd et qu'il est admin
     * Le constructeur de utilisateur met 'admin' à false par défaut,
     * donc on ne peut pas être dans le cas où un nouvel utilisateur anonyme est admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->currentUser->getIsAdmin();
    }

    /**
     * Un utilisateur est authentifié s'il n'est pas anonyme, mais pas admin non plus
     *
     * @return bool
     */
    public function isLogged(): bool
    {
        return (!$this->isAnon()) && (!$this->isAdmin());
    }

    /**
     * Renvoie un entier correspondant au niveau de l'authentification, utile pour les templates twig
     *
     * @return int
     */
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

/*Fichier par josué Raad et Florian Portrait*/
