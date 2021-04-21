<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /** @var int */
    private $userType;

    /**
     * SiteController constructor.
     *
     * @param UserAuthController $authController
     */
    public function __construct(UserAuthController $authController)
    {
        $this->userType = $authController->getUserType();
    }

    /**
     * @Route ("/error", name="error")
     * Permet de générer une erreur 404 pour toutes les pages inconnues
     */
    public function errorAction(Session $session): Response
    {
        // si on a été redirigé ici sans message d'erreur c'est que l'on n'a pas les droits
        $errorFlashes = $session->getFlashBag()->get('error');
        if (empty($errorFlashes))
            $this->addFlash('error', "vous n'avez pas les droits pour cette page");

        return $this->redirectToRoute('index');
//        throw new NotFoundHttpException("page inconnue");
    }

    /**
     * @Route("/", name="index")
     */
    public function indexAction(UserAuthController $authController): Response
    {
        $arg = ['userType' => $this->userType, 'user' => $authController->getCurrentUser()];
        return $this->render("site/index.html.twig", $arg);
    }

    /**
     * @Route("/menu", name="menu")
     */
    public function menuAction(): Response
    {
        $userType = $this->userType;

        // j'interprète le nombre de produit dans la base de donnée comme le nombre de produit différents dans la base
        // Pour le nombre de produit en stock, il faudrait un findAll() et un Foreach()
        $nbProducts = $this->getDoctrine()
                           ->getManager()
                           ->getRepository(Produit::class)
                           ->count([]);

        $arg = ['nbProducts' => $nbProducts, 'userType' => $userType];
        return $this->render("default/_menu.html.twig", $arg);
    }

    /**
     * @Route("/header", name="header")
     */
    public function headerAction(): Response
    {
        $userType = $this->userType;
        return $this->render("default/_header.html.twig", ['userType' => $userType]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(): Response
    {
        if ($this->userType != 0)
            return $this->redirectToRoute("error");

        return $this->render("site/login.html.twig");
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(): Response
    {
        if ($this->userType == 0)
            return $this->redirectToRoute("error");

        $this->addFlash('info', 'Déconnexion réussie');

        return $this->redirectToRoute("index");
    }

}
