<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Service\UserAuth;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @var UserAuth
     * Pour gérer l'utilisateur actuellement authentifié.
     */
    private $auth;

    /**
     * UtilisateurController constructor.
     *
     * @param UserAuth $auth
     */
    public function __construct(UserAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Route pour la liste des produits pour un utilisateur authentifié
     *
     * @Route("/list", name="produit_list", methods={"GET"})
     */
    public function listAction(ProduitRepository $produitRepository): Response
    {
        if (!$this->auth->isLogged())
            return $this->redirectToRoute('error');

        return $this->render('produit/list.html.twig', ['produits' => $produitRepository->findAll()]);
    }

    /**
     * Route pour la liste des produits pour un administrateur
     * Le template a été modifié par rapport à la liste des produits de l'utilisateur
     * On peut seulement modifier la quantité de stock d'un produit
     *
     * @Route("/listAdmin", name="produit_list_admin", methods={"GET"})
     */
    public function listAdminAction(ProduitRepository $produitRepository): Response
    {
        if (!$this->auth->isAdmin())
            return $this->redirectToRoute('error');

        return $this->render('produit/list_admin.html.twig', ['produits' => $produitRepository->findAll()]);
    }

    /**
     * Route pour que l'admin crée un nouveau produit
     * La validation des données est faite automatiquement par les assertions dans la classe Produit
     *
     * @Route("/new", name="produit_new", methods={"GET","POST"})
     */
    public function newAction(Request $request): Response
    {
        if (!$this->auth->isAdmin())
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

        return $this->render('produit/new.html.twig', ['produit' => $produit,'form' => $form->createView()]);
    }

    /**
     * Route pour éditer un produit en tant qu'admin
     * On ne peut que changer la quantité de stock
     *
     * @Route("/edit/{id}", name="produit_edit", methods={"GET","POST"})
     */
    public function editAction(Request $request, Produit $produit): Response
    {
        if (!$this->auth->isAdmin())
            return $this->redirectToRoute('error');

        $form = $this->createForm(ProduitType::class, $produit);

        // on retire les champs que l'on ne veut pas modifier
        $form->remove("libelle");
        $form->remove("id");
        $form->remove("prix");

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_list_admin');
        }

        return $this->render('produit/edit.html.twig', ['produit' => $produit, 'form' => $form->createView()]);
    }

    /**
     * On a la possibilité de supprimer des produits en tant qu'admin.
     * Mais il n'y a pas de liens direct dans le site pour effectuer cette action
     * la variable {id} est automatiquement gérée par symfony et provoque une erreur 404 si l'id est invalide
     *
     * @Route("/delete/{id}", name="produit_delete")
     */
    public function deleteAction(Produit $produit): Response
    {
        if (!$this->auth->isAdmin())
            return $this->redirectToRoute('error');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('produit_list');
    }
}

/*Fichier par josué Raad et Florian Portrait*/
