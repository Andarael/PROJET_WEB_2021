<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/utilisateur")
 */
class UtilisateurController extends AbstractController
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
     * @Route("/list", name="utilisateur_list")
     */
    public function UtilisateurListAction(UtilisateurRepository $utilisateurRepository): Response
    {
        if(! $this->authController->isAdmin())
            return $this->redirectToRoute('error');

        return $this->render("utilisateur/list.html.twig", ['utilisateurs' => $utilisateurRepository->findAll()]);
    }

    /**
     * @Route("/create_account", name="create_account")
     */
    public function createAccountAction(Request $request): Response
    {
        if(! $this->authController->isAdmin())
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
     * @Route("/new", name="utilisateur_new", methods={"GET","POST"})
     */
    public function newAction(): Response
    {
        return $this->redirectToRoute("create_account");
    }

    /**
     * @Route("/edit", name="utilisateur_edit", methods={"GET","POST"})
     */
    public function editAction(Request $request): Response
    {
        if(! $this->authController->isLogged())
            return $this->redirectToRoute('error');

        $utilisateur = $this->authController->getCurrentUser();

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
     * @Route("/delete/{id}", name="utilisateur_delete")
     * todo check id
     */
    public function deleteAction(Utilisateur $utilisateur, PanierController $panierController): Response
    {
        if(! $this->authController->isAdmin())
            return $this->redirectToRoute('error');

        if ($this->authController->getCurrentUser()->getId() == $utilisateur->getId())
            $this->addFlash('error', 'Impossible de supprimer un utilisateur actuellement authentifié');
        else if (!empty($utilisateur->getPanier()))
            $panierController->deletePanier($utilisateur, false);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($utilisateur);
        $entityManager->flush();

        return $this->redirectToRoute('utilisateur_list');
    }

}
