<?php

namespace App\Entity;

use App\Repository\LignePanierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LignePanierRepository::class)
 * @ORM\Table(name="im2021_lignes_paniers", options={"COMMENT":"Les paniers des utilisateurs"})
 */
class LignePanier
{
    /**
     * @var Produit
     *
     * @ORM\Id
     *
     * @ORM\OneToOne(targetEntity=Produit::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @var Utilisateur
     *
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="lignesPanier")
     * @ORM\JoinColumn(nullable=false)
     *
     * comme la clé primaire ne s'appelle pas 'id' dans les annotations de utilisateur, 'doctrine:schema:validate' indique une erreur
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite;

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }
}
