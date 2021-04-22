<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Service\UserAuth;
use App\Service\ReverseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SiteController extends AbstractController
{

    /**
     * @var UserAuth
     * Pour gérer l'utilisateur actuellement authentifié
     */
    private $auth;

    /**
     * SiteController constructor.
     *
     * @param UserAuth $auth
     */
    public function __construct(UserAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Route d'erreur du site. On ne lève pas une erreur 404 pour le moment.
     * Mais on redirige vers la page d'accueil avec un message explicatif.
     * Pour rediriger vers une erreur 404 comme demandé, il suffit de dé-commenter la première ligne de la fonction
     *
     * @Route ("/error", name="error")
     * Permet de générer une erreur 404 pour toutes les pages inconnues
     */
    public function errorAction(Session $session): Response
    {
        // throw new NotFoundHttpException("page inconnue");

        // si on a été redirigé ici sans message d'erreur c'est que l'on n'a pas les droits
        $errorFlashes = $session->getFlashBag()->get('error');

        if (empty($errorFlashes))
            $this->addFlash('error', "vous n'avez pas les droits pour cette page");

        return $this->redirectToRoute('index');
    }

    /**
     * Page d' accueil du site
     *
     * @Route("/", name="index")
     */
    public function indexAction(): Response
    {
        $arg = ['userType' => $this->auth->getUserType(), 'user' => $this->auth->getCurrentUser()];
        return $this->render("site/index.html.twig", $arg);
    }

    /**
     * Route pour afficher le menu du site
     * L'affichage du menu seul n'est pas conforme aux normes HTML, c'est un fragment de template
     *
     * @Route("/menu", name="menu")
     */
    public function menuAction(): Response
    {
        // j'interprète le nombre de produit dans la base de donnée comme le nombre de produit différents dans la base
        // Pour le nombre de produit en stock, il faudrait un findAll() et un Foreach()
        $nbProducts = $this->getDoctrine()
                           ->getManager()
                           ->getRepository(Produit::class)
                           ->count([]);

        $arg = ['nbProducts' => $nbProducts, 'userType' => $this->auth->getUserType()];
        return $this->render("default/_menu.html.twig", $arg);
    }

    /**
     * Route pour afficher le header du site
     * L'affichage du header seul n'est pas conforme aux normes HTML, c'est un fragment de template
     *
     * @Route("/header", name="header")
     */
    public function headerAction(): Response
    {
        return $this->render("default/_header.html.twig", ['userType' => $this->auth->getUserType()]);
    }

    /**
     * Route pour l'authentification
     *
     * @Route("/login", name="login")
     */
    public function loginAction(): Response
    {
        if (!$this->auth->isAnon())
            return $this->redirectToRoute('error');

        return $this->render("site/login.html.twig");
    }

    /**
     * Route pour la déconnexion
     *
     * @Route("/logout", name="logout")
     */
    public function logoutAction(): Response
    {
        if ($this->auth->isAnon())
            return $this->redirectToRoute("error");

        $this->addFlash('info', 'Déconnexion réussie');

        return $this->redirectToRoute("index");
    }

    /**
     * Route pour le service d'inversion de String avec valeur par défaut
     *
     * @Route("/reverse/{str}", name="reverse", defaults={"str" : "Nabuchodonosor"})
     */
    public function reverseAction(string $str, ReverseService $reverseService): Response
    {
        $reversed = $reverseService->reverseString($str);

        return $this->render('site/reverse.html.twig', ['str' => $str, 'reversed' => $reversed]);
    }

}

/*Fichier par josué Raad et Florian Portrait*/
