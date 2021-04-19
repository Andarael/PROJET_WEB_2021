<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="im2021_produits")
 *
 * @ORM\Entity(repositoryClass=ProduitsRepository::class)
 */
class Produits
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="code_produit", type="integer")
     */
    private $code_produit;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $libelle;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix_u;

    /**
     * @ORM\Column(type="integer")
     */
    private $qte_stock;

    /**
     * Produits constructor.
     */
    public function __construct()
    {
        $this->prix_u = null;
    }

    public function getCodeproduit(): ?int
    {
        return $this->code_produit;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPrixU(): ?float
    {
        return $this->prix_u;
    }

    public function setPrixU(?float $prix_u): self
    {
        $this->prix_u = $prix_u;

        return $this;
    }

    public function getQteStock(): ?int
    {
        return $this->qte_stock;
    }

    public function setQteStock(int $qte_stock): self
    {
        $this->qte_stock = $qte_stock;

        return $this;
    }
}
