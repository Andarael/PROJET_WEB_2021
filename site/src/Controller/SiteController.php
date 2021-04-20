<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{

    /** @var UserAuthController */
    private $authController;

    /** @var int */
    private $userType;

    /**
     * SiteController constructor.
     *
     * @param UserAuthController $authController
     */
    public function __construct(UserAuthController $authController)
    {
        $this->authController = $authController;
        $this->userType = $authController->getUserType();
    }

    /**
     * @Route ("/error", name="error")
     * Permet de générer une erreur 404 pour toutes les pages inconnues
     *
     * @return Response
     */
    public function errorAction(): Response
    {
        throw new NotFoundHttpException("page inconnue");
    }

    /**
     * @Route("/", name="index")
     */
    public function indexAction(): Response
    {
        $utilisateur = $this->authController->getCurrentUser();

        $arg = ['userType' => $this->userType, 'user' => $utilisateur];
        return $this->render("index.html.twig", $arg);
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

        return $this->render("login.html.twig");
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(): Response
    {
        $userType = $this->userType;
        if ($userType == 0)
            return $this->redirectToRoute("error");

        $this->addFlash('info', 'Déconnexion réussie');

        return $this->redirectToRoute("index");
    }

    /**
     * @Route("/panier", name="panier")
     */
    public function panierAction()
    {
        if ($this->userType != 1)
            return $this->redirectToRoute("error");

        return $this->render("panier/show.html.twig");
    }

}
