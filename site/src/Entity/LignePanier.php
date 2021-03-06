<?php

namespace App\Entity;

use App\Repository\LignePanierRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass=LignePanierRepository::class)
 * @ORM\Table(name="im2021_lignes_panier",
 *     uniqueConstraints={@UniqueConstraint(name="usr_prod", columns={"utilisateur","produit"})})
 *
 * Les contraintes sont là pour que les couples (utilisateur, produit) soient uniques
 * On garde la clé primaire 'id' pour plus de simplicité dans la gestion des lignes dans Symfony
 */
class LignePanier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class)
     * @ORM\JoinColumn(name="produit")
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="panier")
     * @ORM\JoinColumn(name="utilisateur", nullable=false, referencedColumnName="pk" )
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite;

    /**
     * LignePanier constructor.
     */
    public function __construct()
    {
        $this->utilisateur = null;
        $this->produit = null;
        $this->quantite = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getUtilisateur(): utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }
}

/*Fichier par josué Raad et Florian Portrait*/
