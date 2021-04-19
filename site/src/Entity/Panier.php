<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="im2021_panier")
 *
 * @ORM\Entity(repositoryClass=PanierRepository::class)
 */
class Panier
{

    /**
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateurs;

    /**
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity=Produits::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $produits;

    /**
     * @ORM\Column(type="integer")
     */
    private $qte_commande;


    public function getUtilisateurs(): ?Utilisateurs
    {
        return $this->utilisateurs;
    }

    public function setUtilisateurs(?Utilisateurs $utilisateurs): self
    {
        $this->utilisateurs = $utilisateurs;

        return $this;
    }

    public function getProduits(): ?Produits
    {
        return $this->produits;
    }

    public function setProduits(?Produits $produits): self
    {
        $this->produits = $produits;

        return $this;
    }

    public function getQteCommande(): ?int
    {
        return $this->qte_commande;
    }

    public function setQteCommande(int $qte_commande): self
    {
        $this->qte_commande = $qte_commande;

        return $this;
    }
}
