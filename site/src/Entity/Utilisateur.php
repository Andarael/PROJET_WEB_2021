<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="im2021_utilisateurs", options={"COMMENT":"Table des utilisateurs du site"})
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 *
 * @UniqueEntity("identifiant", message="Ce login est déjà pris") // on pourait le mettre avec un assert sur la variable également
 */
class Utilisateur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="pk", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="identifiant", type="string", length=30, unique=true, options={"COMMENT":"sert de login (doit etre unique)"})
     * @Assert\NotBlank (message="identifiant requis")
     * @Assert\Type (type="alnum", message="Identifiant alphanumérique uniquement")
     *
     * Je n'ai pas réussi à trouver pourquoi les commentaires ne s'exportent pas en sql ?
     */
    private $identifiant;

    /**
     * @Assert\NotBlank (message="mdp requis")
     * @ORM\Column(name="motdepasse", type="string", length=64, options={"comment":"mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer"})
     */
    private $motDePasse;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Type("\DateTimeInterface")
     */
    private $anniversaire;

    /**
     * @ORM\Column(name="isadmin", type="boolean", options={"COMMENT":"type booléen"})
     * @Assert\Type("bool")
     * Selon 'doctrine-project.org' tinyInt(1) se mappe vers un bool
     */
    private $isAdmin;

    /**
     * j'appel la collection de lignes_panier un 'panier', donc la variable n'est pas au pluriel.
     *
     * @ORM\OneToMany(targetEntity=LignePanier::class, mappedBy="utilisateur", orphanRemoval=true)
     */
    private $panier;

    /**
     * Utilisateur constructor.
     */
    public function __construct()
    {
        $this->id = null;
        $this->nom = null;
        $this->prenom = null;
        $this->anniversaire = null;
        $this->isAdmin = false;
        $this->panier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(string $identifiant): self
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = sha1($motDePasse);

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAnniversaire(): ?DateTimeInterface
    {
        return $this->anniversaire;
    }

    public function setAnniversaire(?DateTimeInterface $anniversaire): self
    {
        $this->anniversaire = $anniversaire;

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function __toString()
    {
        return $this->identifiant;
    }

    /**
     * @return Collection|LignePanier[]
     */
    public function getPanier(): Collection
    {
        return $this->panier;
    }

    public function addPanier(LignePanier $panier): self
    {
        if (!$this->panier->contains($panier)) {
            $this->panier[] = $panier;
            $panier->setUtilisateur($this);
        }

        return $this;
    }

    public function removePanier(LignePanier $panier): self
    {
        if ($this->panier->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getUtilisateur() === $this) {
                $panier->setUtilisateur(null);
            }
        }

        return $this;
    }

}

/*Fichier par josué Raad et Florian Portrait*/
