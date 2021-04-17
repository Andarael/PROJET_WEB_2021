<?php

    namespace App\Controller;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

    /**
     * @Route("/sandbox")
     */
    class SandboxController extends AbstractController
    {
        /**
         * @Route("/", name="sandbox_index")
         */
        public function indexAction(): Response
        {
            return $this->render("accueil.html.twig");
        }







        /**
         * @Route ("/{path}", name="sandbox_error")
         *
         *
         * Permet de générer une erreur 404 pour toutes les pages inconnues
         */
        public function errorAction($path): Response
        {
            throw new NotFoundHttpException("page inconnue : '$path' ");
        }

    }
