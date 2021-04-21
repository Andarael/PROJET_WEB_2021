<?php

namespace App\Controller;

use App\Entity\LignePanier;
use App\Entity\Utilisateur;
use App\Repository\LignePanierRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/panier")
 */
class PanierController extends AbstractController
{
    private $userType;

    /**
     * LignePanierController constructor.
     *
     * @param UserAuthController $authController
     */
    public function __construct(UserAuthController $authController)
    {
        $this->userType = $authController->getUserType(); // on instancie le type d'utilisateur
    }

    /**
     * On visualise tout un panier, pas une ligne de panier
     * @Route("/", name="panier")
     */
    public function showAction(LignePanierRepository $lignePanierRepository, UserAuthController $authController): Response
    {
        if ($this->userType != 1)
            return $this->redirectToRoute('error');

        $currentUser = $authController->getCurrentUser();

        return $this->render('panier/show.html.twig',
                             ['ligne_paniers' => $lignePanierRepository->findBy(['utilisateur' => $currentUser])]);
    }

    /**
     * Ici on ajoute bien un panier, pas une ligne de panier
     * @Route("/add", name="add_panier")
     *
     * Pour éviter qu'une erreur symfony apparaisse, 'methods={"POST"}' est géré dans l'action
     */
    public function addAction(Request $request, LignePanierRepository $lignePanierRepository, ProduitRepository $produitRepository, UserAuthController $authController): Response
    {
        if ($this->userType != 1)
            return $this->redirectToRoute('error');

        // Vérification que le formulaire a été posté
        if ($request->get('submitted') === null)
            return $this->redirectToRoute('panier');

        $utilisateur = $authController->getCurrentUser();

        // On parcours tous les produits,
        // si on a commandé ce produit, et s'il est en stock
        // Et si on ne l'a pas déjà dans le panier
        // Alors on ajoute un nouvelle ligne au panier
        // et on met à jour la bdd en conséquence
        foreach ($produitRepository->findAll() as $produit) {
            $qte_commande = $request->get('qte_' . $produit->getId());
            $stockProduit = $produit->getQteStock();

            if ($stockProduit > 0 && $qte_commande > 0 && $qte_commande <= $stockProduit) {
                $count = $lignePanierRepository->count(['produit' => $produit, 'utilisateur' => $utilisateur]);

                // ne peut arriver que si la BDD est édité manuellement
                if ($count > 1) {
                    $this->addFlash('error', "Erreur fatal panier ! Videz votre panier");
                    $this->redirectToRoute('index');
                }

                // Si on voulait ajouter la qte voulue à une ligne déjà existante on pourrait le faire ici
                if ($count == 1) {
                    $this->addFlash('error', $produit->getLibelle() . " est déjà dans le panier");
                    return $this->redirectToRoute('produit_list');
                }

                // On crée une nouvelle ligne de panier
                $lignePanier = new LignePanier();
                $lignePanier->setUtilisateur($utilisateur);
                $lignePanier->setProduit($produit);
                $lignePanier->setQuantite($qte_commande);

                // maj de la bdd
                $produit->setQteStock($stockProduit - $qte_commande);

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($lignePanier);
                $manager->persist($produit);
                $manager->flush();
            }
        }

        return $this->redirectToRoute('produit_list');
    }

    /**
     * Supprime la ligne panier d'un utilisateur.
     * Renvoie un bool pour tester si la suppression s'est bien passée
     *
     * @param LignePanier $lignePanier La ligne à supprimer
     * @param Utilisateur $utilisateur L'utilisateur qui demande la suppression
     * @param bool $achat La suppression se fait-elle dans le cadre d'un achat ?
     *
     * @return bool Vrai si la ligne n'appartient pas à l'utilisateur
     */
    public function deleteLignePanier(LignePanier $lignePanier, Utilisateur $utilisateur, bool $achat): bool
    {
        // Vérification que la ligne correspond bien à l'utilisateur connecté
        if ($lignePanier->getUtilisateur()->getId() != $utilisateur->getId()) {
            $this->addFlash('error', "Vous ne pouvez pas supprimer cette article");
            return true;
        }

        $entityManager = $this->getDoctrine()->getManager();

        if (!$achat) {
            $produit = $lignePanier->getProduit();
            $produit->setQteStock($lignePanier->getQuantite() + $produit->getQteStock());
            $entityManager->persist($produit);
        }

        $entityManager->remove($lignePanier);
        $entityManager->flush();

        return false;
    }

    public function deletePanier(Utilisateur $utilisateur, bool $achat)
    {
        $panier = $utilisateur->getPanier();

        foreach ($panier as $lignePanier)
            $this->deleteLignePanier($lignePanier, $utilisateur, $achat);
    }

    /**
     * @Route("/delte/{id}", name="ligne_panier_delete")
     */
    public
    function deleteLignePanierAction(LignePanier $lignePanier, UserAuthController $authController): Response
    {
        if ($this->userType != 1)
            return $this->redirectToRoute('error');

        $this->deleteLignePanier($lignePanier, $authController->getCurrentUser(), false);

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/delte/", name="panier_delete")
     */
    public function deletePanierAction(UserAuthController $authController): Response
    {
        if ($this->userType != 1)
            return $this->redirectToRoute('error');

        $this->deletePanier($authController->getCurrentUser(), false);

        $this->addFlash('info', 'Panier vidé');

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/acheter", name="panier_acheter")
     */
    public
    function acheterPanierAction(UserAuthController $authController): Response
    {
        if ($this->userType != 1)
            return $this->redirectToRoute('error');

        $this->deletePanier($authController->getCurrentUser(), true);

        $this->addFlash('info', 'Commande prise en compte');

        return $this->redirectToRoute('panier');
    }

}
