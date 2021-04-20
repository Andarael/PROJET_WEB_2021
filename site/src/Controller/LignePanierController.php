<?php

namespace App\Controller;

use App\Entity\LignePanier;
use App\Form\LignePanierType;
use App\Repository\LignePanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/panier")
 */
class LignePanierController extends AbstractController
{
    /**
     * @Route("/", name="ligne_panier_index", methods={"GET"})
     */
    public function index(LignePanierRepository $lignePanierRepository): Response
    {
        return $this->render('ligne_panier/index.html.twig', [
            'ligne_paniers' => $lignePanierRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ligne_panier_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $lignePanier = new LignePanier();
        $form = $this->createForm(LignePanierType::class, $lignePanier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lignePanier);
            $entityManager->flush();

            return $this->redirectToRoute('ligne_panier_index');
        }

        return $this->render('ligne_panier/new.html.twig', [
            'ligne_panier' => $lignePanier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_panier_show", methods={"GET"})
     */
    public function show(LignePanier $lignePanier): Response
    {
        return $this->render('ligne_panier/show.html.twig', [
            'ligne_panier' => $lignePanier,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ligne_panier_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LignePanier $lignePanier): Response
    {
        $form = $this->createForm(LignePanierType::class, $lignePanier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ligne_panier_index');
        }

        return $this->render('ligne_panier/edit.html.twig', [
            'ligne_panier' => $lignePanier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_panier_delete", methods={"POST"})
     */
    public function delete(Request $request, LignePanier $lignePanier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lignePanier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($lignePanier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ligne_panier_index');
    }
}
