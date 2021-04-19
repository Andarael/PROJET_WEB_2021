<?php

    namespace App\Controller;

    use App\Entity\Utilisateurs;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

    class SiteController extends AbstractController
    {

        /**
         * @Route("/", name="index")
         */
        public function indexAction(): Response
        {
            $userType = $this->getUserType();
            $arg = ['userType' => $userType];
            return $this->render("index.html.twig", $arg);
        }

        public function getUserType(): int
        {
            $usr = $this->getCurrentUser();

            if (is_null($usr))
                return 0;
            else if ($usr->getIsadmin())
                return 2;
            else
                return 1;
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

        public function getCurrentUser(): ?Utilisateurs
        {
            $identifiant = $this->getParameter('moi');
            $manager = $this->getDoctrine()->getManager();
            $utilisateurRepository = $manager->getRepository(Utilisateurs::class);

            /** @var Utilisateurs $usr */
            $usr = $utilisateurRepository->findOneBy(['identifiant' => $identifiant]);
            return $usr;
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
         * @Route("/header", name="header")
         */
        public function headerAction(): Response
        {
            $userType = $this->getUserType();
            return $this->render("_header.html.twig", ['userType' => $userType]);
        }

        /**
         * @Route("/login", name="login")
         */
        public function loginAction(): Response
        {
            if ($this->getUserType() != 0)
                return $this->redirectToRoute("error");

            return $this->render("login.html.twig");
        }

        /**
         * @Route("/logout", name="logout")
         */
        public function logoutAction(): Response
        {
            $userType = $this->getUserType();
            if ($userType == 0)
                return $this->redirectToRoute("error");

            return $this->redirectToRoute("index");
        }

        /**
         * @Route("/create_account", name="create_account")
         */
        public function createAccountAction()
        {
            if ($this->getUserType() != 0)
                return $this->redirectToRoute("error");

            return $this->render("create_account.html.twig");

        }

    }
