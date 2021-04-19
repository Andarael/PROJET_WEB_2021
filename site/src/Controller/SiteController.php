<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{

    private $authController;

    /**
     * SiteController constructor.
     * @param UserAuthController $authController
     */
    public function __construct(UserAuthController $authController)
    {
        $this->authController = $authController;
    }

    /**
     * @Route("/", name="index")
     */
    public function indexAction(): Response
    {
        $utilisateur = $this->authController->getCurrentUser();

        $arg = ['userType' => $this->authController->getUserType(), 'user'  => $utilisateur];
        return $this->render("index.html.twig", $arg);
    }

    /**
     * @Route ("/error", name="error")
     * Permet de générer une erreur 404 pour toutes les pages inconnues
     *
     * @return Response
     */
    public function errorAction(): Response
    {
        throw new NotFoundHttpException("page inconnue : '' ");
    }

    /**
     * @Route("/menu", name="menu")
     */
    public function menuAction(): Response
    {
        $userType = $this->authController->getUserType();
        $nbProducts = 16; // todo query la bd pour l'info
        $arg = ['nbProducts' => $nbProducts, 'userType' => $userType];
        return $this->render("_menu.html.twig", $arg);
    }

    /**
     * @Route("/header", name="header")
     */
    public function headerAction(): Response
    {
        $userType = $this->authController->getUserType();
        return $this->render("_header.html.twig", ['userType' => $userType]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(): Response
    {
        if ($this->authController->getUserType() != 0)
            return $this->redirectToRoute("error");

        return $this->render("login.html.twig");
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(): Response
    {
        $userType = $this->authController->getUserType();
        if ($userType == 0)
            return $this->redirectToRoute("error");

        return $this->redirectToRoute("index");
    }

    /**
     * @Route("/create_account", name="create_account")
     */
    public function createAccountAction()
    {
        if ($this->authController->getUserType() != 0)
            return $this->redirectToRoute("error");

        return $this->render("create_account.html.twig");
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profileAction()
    {
        if ($this->authController->getUserType() != 1)
            return $this->redirectToRoute("error");

        return $this->render("profile.html.twig");
    }

    /**
     * @Route("/prduits/list", name="produits_list")
     */
    public function produitListAction()
    {
        if ($this->authController->getUserType() != 1)
            return $this->redirectToRoute("error");

        return $this->redirectToRoute("produits_index");
    }

    /**
     * @Route("/panier", name="panier")
     */
    public function panierAction()
    {
        if ($this->authController->getUserType() != 1)
            return $this->redirectToRoute("error");

        return $this->render("panier/show.html.twig");
    }

}
