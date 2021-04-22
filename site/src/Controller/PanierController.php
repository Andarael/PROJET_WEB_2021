<?php

namespace App\Controller;

use App\Service\UserAuth;
use App\Entity\LignePanier;
use App\Entity\Utilisateur;
use App\Repository\LignePanierRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller pour la gestion du panier
 * On n'a jamais accès aux paniers de tout les utilisateurs.
 * Les routes dirigent seulement vers le panier de l'utilisateur actuel
 *
 * @Route("/panier")
 */
class PanierController extends AbstractController
{
    /**
     * @var UserAuth
     * Pour gérer l'utilisateur actuellement authentifié
     */
    private $auth;

    /**
     * LignePanierController constructor.
     *
     * @param UserAuth $auth
     */
    public function __construct(UserAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Route pour visualiser son panier
     * On visualise bien tout un panier, pas une ligne de panier
     *
     * @Route("/", name="panier")
     */
    public function showAction(LignePanierRepository $lignePanierRepository): Response
    {
        if (!$this->auth->isLogged())
            return $this->redirectToRoute('error');

        return $this->render('panier/show.html.twig',
                             ['ligne_paniers' => $lignePanierRepository->findBy(['utilisateur' => $this->auth->getCurrentUser()])]);
    }

    /**
     * Ici on ajoute bien un panier entier, pas une ligne de panier
     * Le stock des produits est mis à jour en fonction de la requête de l'utilisateur     *
     *
     * @Route("/add", name="add_panier")
     *
     * Pour éviter qu'une erreur 500 symfony apparaisse, 'methods={"POST"}' est géré dans l'action
     * Ainsi si l'on arrive sur cette route sans avoir fourni de formulaire, on est redirigé
     */
    public function addAction(Request $request, LignePanierRepository $lignePanierRepository, ProduitRepository $produitRepository): Response
    {
        if (!$this->auth->isLogged())
            return $this->redirectToRoute('error');

        // Vérification que le formulaire a été posté
        if (!$request->isMethod('POST'))
            return $this->redirectToRoute('panier');

        $utilisateur = $this->auth->getCurrentUser();

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
                    return $this->redirectToRoute('panier');
                }

                // On crée une nouvelle ligne de panier
                $lignePanier = new LignePanier();
                $lignePanier->setUtilisateur($utilisateur);
                $lignePanier->setProduit($produit);
                $lignePanier->setQuantite($qte_commande);

                // maj du stock
                $produit->setQteStock($stockProduit - $qte_commande);

                // maj de la bdd
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($lignePanier);
                $manager->persist($produit);
                $manager->flush();
            }
        }

        return $this->redirectToRoute('produit_list');
    }

    /**
     * Route pour supprimer une ligne de panier
     * la variable {id} est automatiquement gérée par symfony et provoque une erreur 404 si l'id est invalide
     *
     * @Route("/delete/{id}", name="ligne_panier_delete")
     */
    public function deleteLignePanierAction(LignePanier $lignePanier): Response
    {
        if (!$this->auth->isLogged())
            return $this->redirectToRoute('error');

        $this->deleteLignePanier($lignePanier, $this->auth->getCurrentUser(), false);

        return $this->redirectToRoute('panier');
    }

    /**
     * Supprime la ligne panier d'un utilisateur.
     * Renvoie un bool pour tester si la suppression s'est bien passée
     * Dans le cas d'un achat le produit n'est pas remis en stock
     *
     * On ne vérifie pas si l'utilisateur ou la ligne sont nulls, c'est à la fonction qui appel de s'en charger
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

    /**
     * Supprime tout le panier d'un utilisateur.
     * On ne vérifie pas si l'utilisateur est null, c'est à la fonction qui appel de s'en charger
     */
    public function deletePanier(Utilisateur $utilisateur, bool $achat)
    {
        $panier = $utilisateur->getPanier();

        foreach ($panier as $lignePanier)
            $this->deleteLignePanier($lignePanier, $utilisateur, $achat);
    }

    /**
     * Route pour supprimer tout un panier.
     * On fait appel aux fonctions ci-dessus
     *
     * @Route("/delete/", name="panier_delete")
     */
    public function deletePanierAction(): Response
    {
        if (!$this->auth->isLogged())
            return $this->redirectToRoute('error');

        // l'authController ne peut pas fournir un utilisateur null
        $this->deletePanier($this->auth->getCurrentUser(), false);

        $this->addFlash('info', 'Panier vidé');

        return $this->redirectToRoute('panier');
    }

    /**
     * Route pour commander les produits dans le panier
     * Même principe pour la suppression
     *
     * @Route("/acheter", name="panier_acheter")
     */
    public
    function acheterPanierAction(): Response
    {
        if (!$this->auth->isLogged())
            return $this->redirectToRoute('error');

        // l'authController ne peut pas fournir un utilisateur null
        $this->deletePanier($this->auth->getCurrentUser(), true);

        $this->addFlash('info', 'Commande prise en compte');

        return $this->redirectToRoute('panier');
    }

}

/*Fichier par josué Raad et Florian Portrait*/
