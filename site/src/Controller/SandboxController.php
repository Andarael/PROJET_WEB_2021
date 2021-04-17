<?php

    namespace App\Controller;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    /**
     * @Route("/sandbox")
     */
    class SandboxController extends AbstractController
    {
        /**
         * @Route("/", name="sandbox")
         */
        public function index(): Response
        {
            return $this->render("Sandbox/index.html.twig");
        }


    }
