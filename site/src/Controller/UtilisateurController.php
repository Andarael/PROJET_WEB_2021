<?php

namespace App\Controller;

use App\Service\UserAuth;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller pour la gestion des utilisateurs. (Créer, éditer, supprimer)
 *
 * @Route("/utilisateur")
 */
class UtilisateurController extends AbstractController
{
    /** @var UserAuth
     * Pour gérer l'utilisateur actuellement authentifié
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
     * La route pour lister les utilisateurs à l'admin
     *
     * @Route("/list", name="utilisateur_list")
     */
    public function UtilisateurListAction(UtilisateurRepository $utilisateurRepository): Response
    {
        if (!$this->auth->isAdmin())
            return $this->redirectToRoute('error');

        return $this->render("utilisateur/list.html.twig", ['utilisateurs' => $utilisateurRepository->findAll()]);
    }

    /**
     * Route pour créer un nouveau compte
     *
     * Seul un anonyme peut créer un compte
     * La validation est faite automatiquement par les assertions dans la classe Utilisateur
     *
     * @Route("/create_account", name="create_account")
     */
    public function createAccountAction(Request $request): Response
    {
        if (!$this->auth->isAnon())
            return $this->redirectToRoute('error');

        $utilisateur = new Utilisateur();

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->add('valider', SubmitType::class, ['label' => 'valider']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $this->addFlash('info', 'Votre compte utilisateur a bien été créé');
            return $this->redirectToRoute('index');
        }

        $arg = ['utilisateur' => $utilisateur, 'form' => $form->createView()];
        return $this->render('utilisateur/create_account.html.twig', $arg);
    }

    /**
     * Route pour éditer son profil utilisateur
     *
     * On est automatiquement dirigé vers le profil de l'utilisateur actuellement authentifié
     * L'admin ne peut pas éditer son profil, conformément au sujet
     *
     * @Route("/edit", name="utilisateur_edit", methods={"GET","POST"})
     */
    public function editAction(Request $request): Response
    {
        if (!$this->auth->isLogged())
            return $this->redirectToRoute('error');

        $utilisateur = $this->auth->getCurrentUser();

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->add('valider', SubmitType::class, ['label' => 'valider']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('info', 'Profil édité avec succès');
            return $this->redirectToRoute('produit_list');
        }

        $arg = ['utilisateur' => $utilisateur, 'form' => $form->createView()];

        return $this->render('utilisateur/edit.html.twig', $arg);
    }

    /**
     * Route pour supprimer un utilisateur de la base, par son Id
     *
     * On suppr un utilistateur en prenant soin de vider son panier avant.
     * C'est pour cela qu'on a besoin d'un PanierController
     * L'appel à viderPanier s'assure que les produits sont remis en stock
     *
     * On ne peut pas supprimer l'utilisateur actuellement connecté
     * En particulier ici on vérifie l'id de l'utilisateur à supprimer.
     * Une redirection vers la route d'erreur est effectuée si l'utilisateur n'existe pas dans la base
     *
     * @Route("/delete/{id}", name="utilisateur_delete", requirements={"id" : "[1-9]\d*"})
     */
    public function deleteAction($id, PanierController $panierController): Response
    {
        if (!$this->auth->isAdmin())
            return $this->redirectToRoute('error');

        /** @var Utilisateur $utilisateur */
        $utilisateur = $this->getDoctrine()->getRepository(UtilisateurRepository::class)->find($id);

        if (is_null($utilisateur)) {
            $this->addFlash('info', "L'utilisateur n'existe pas");
            $this->redirectToRoute('error');
        }

        if ($this->auth->getCurrentUser()->getId() == $utilisateur->getId())
            $this->addFlash('error', 'Impossible de supprimer un utilisateur actuellement authentifié');
        else if (!empty($utilisateur->getPanier()))
            $panierController->deletePanier($utilisateur, false);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($utilisateur);
        $entityManager->flush();

        return $this->redirectToRoute('utilisateur_list');
    }

}

/*Fichier par josué Raad et Florian Portrait*/
