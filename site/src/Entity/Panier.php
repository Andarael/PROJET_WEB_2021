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
     * @ORM\Id @ORM\ManyToOne(targetEntity="Utilisateurs")
     * @ORM\JoinColumn(name="pk_utilisateurs", referencedColumnName="pk")
     * @ORM\Column(type="integer")
     */
    private $pk_utilisateurs;

    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="Produits")
     * @ORM\JoinColumn(name="pk_produits", referencedColumnName="code_produit")
     * @ORM\Column(type="integer")
     */
    private $code_produit;

    /**
     * @ORM\Column(type="integer")
     */
    private $qte_commande;

    public function getPkutilisateurs(): ?int
    {
        return $this->pk_utilisateurs;
    }

    public function getCodeProduit(): ?int
    {
        return $this->code_produit;
    }

    public function setCodeProduit(int $code_produit): self
    {
        $this->code_produit = $code_produit;

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
