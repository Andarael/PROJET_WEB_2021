<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /** @var UserAuthController */
    private $authController;

    /**
     * UtilisateurController constructor.
     *
     * @param UserAuthController $authController
     */
    public function __construct(UserAuthController $authController)
    {
        $this->authController = $authController;

        $authController->initialize();
    }

    /**
     * @Route("/list", name="produit_list", methods={"GET"})
     */
    public function listAction(ProduitRepository $produitRepository): Response
    {
        if(! $this->authController->isLogged())
            return $this->redirectToRoute('error');


        return $this->render('produit/list.html.twig', ['produits' => $produitRepository->findAll()]);
    }

    /**
     * @Route("/listAdmin", name="produit_list_admin", methods={"GET"})
     */
    public function listAdminAction(ProduitRepository $produitRepository): Response
    {
        if(! $this->authController->isAdmin())
            return $this->redirectToRoute('error');

        return $this->render('produit/list_admin.html.twig', ['produits' => $produitRepository->findAll()]);
    }

    /**
     * @Route("/new", name="produit_new", methods={"GET","POST"})
     */
    public function newAction(Request $request): Response
    {
        if(! $this->authController->isAdmin())
            return $this->redirectToRoute('error');

        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            $this->addFlash('info', 'Produit ajouté');
            return $this->redirectToRoute('index');
        }

        return $this->render('produit/new.html.twig', ['produit' => $produit, 'form' => $form->createView(),]);
    }

    /**
     * @Route("/edit/{id}", name="produit_edit", methods={"GET","POST"})
     * @todo check id
     */
    public function editAction(Request $request, Produit $produit): Response
    {
        if(! $this->authController->isAdmin())
            return $this->redirectToRoute('error');

        $form = $this->createForm(ProduitType::class, $produit);
        $form->remove("libelle");
        $form->remove("id");
        $form->remove("prix");
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_list_admin');
        }

        return $this->render('produit/edit.html.twig', ['produit' => $produit, 'form' => $form->createView(),]);
    }

    /**
     * On a la possibilité de supprimer des produits en tant qu'admin.
     * Mais il n'y a pas de liens direct dans le site pour effectuer cette action
     * todo check id
     * @Route("/delete/{id}", name="produit_delete")
     */
    public function deleteAction(Produit $produit): Response
    {
        if(! $this->authController->isAdmin())
            return $this->redirectToRoute('error');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('produit_list');
    }
}
