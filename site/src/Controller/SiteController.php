<?php

    namespace App\Controller;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

    class SiteController extends AbstractController
    {

        public function getUserType(): int
        {
            // todo query la bd pour l'info
            return 1;
        }

        /**
         * @Route("/", name="index")
         */
        public function indexAction(): Response
        {
            $userType = $this->getUserType();
            $arg = ['userType' => $userType];
            return $this->render("index.html.twig", $arg);
        }

        /**
         * @Route("/menu", name="menu")
         */
        public function menuAction(): Response
        {
            $userType = $this->getUserType();
            $nbProducts = 16; // todo query la bd pour l'info
            $arg = ['nbProducts' => $nbProducts, 'userType' => $userType];
            return $this->render("_menu.html.twig", $arg);
        }

        /**
         * @Route("/login", name="login")
         */
        public function loginAction(): Response
        {
            $userType = $this->getUserType();
            $arg = ['userType' => $userType];

            if ($userType != 0) {
                return $this->redirectToRoute("error");
            }

            return $this->render("login.html.twig", $arg);
        }

        /**
         * @Route("/logout", name="logout")
         */
        public function logoutAction(): Response
        {
            dump("test");
            $userType = $this->getUserType();
            $arg = ['userType' => $userType];
            if ($userType == 0) {
                return $this->redirectToRoute("error");
            }

            return $this->redirectToRoute("index");
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


    }
