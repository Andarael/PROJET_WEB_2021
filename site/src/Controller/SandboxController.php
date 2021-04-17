<?php

    namespace App\Controller;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    /**
     * Class SandboxController
     *
     * @package App\Controller
     * @Route("/sandbox")
     */
    class SandboxController extends AbstractController
    {
        /**
         * @Route("/", name="sandbox")
         */
        public function index(): Response
        {
            return $this->render('sandbox/index.html.twig', ['controller_name' => 'SandboxController',]);
        }
    }
