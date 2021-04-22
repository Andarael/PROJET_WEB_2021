<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 * @ORM\Table (name="im2021_produits", options={"COMMENT"="table des produits"})
 *
 * Les assertions sont là pour vérifier automatiquement les données des formulaires
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Libellé du produit requis")
     */
    private $libelle;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Prix du produit requis")
     * @Assert\PositiveOrZero(message="prix doit être un nombre positif")
     *
     */
    private $prix;

    /**
     * @ORM\Column(name="quantite_stock", type="integer" )
     * @Assert\NotBlank(message="Quantité en stock requise")
     * @Assert\PositiveOrZero (message="Quantité négative non autorisée")
     */
    private $qteStock;

    /**
     * Produit constructor.
     */
    public function __construct()
    {
        $this->qteStock = 0;
        $this->prix = 0;
        $this->libelle = null; // pour le __toString (qu'on n'utilise pas au final ...)
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQteStock(): ?int
    {
        return $this->qteStock;
    }

    public function setQteStock(int $qteStock): self
    {
        $this->qteStock = $qteStock;

        return $this;
    }

    public function __toString()
    {
        if (is_null($this->libelle))
            return "";

        return $this->libelle;
    }

}

/*Fichier par josué Raad et Florian Portrait*/
