<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="im2021_utilisateurs", options={"COMMENT":"Table des utilisateurs du site"})
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 */
class Utilisateur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="pk", type="integer", length=4)
     */
    private $id;

    /**
     * @ORM\Column(name="identifiant", type="string", length=30, unique=true, options={"COMMENT":"sert de login (doit etre unique)"})
     *
     * Je n'ai pas réussi à trouver pourquoi les commentaires ne s'exportent pas en sql ?
     */
    private $identifiant;

    /**
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
     */
    private $anniversaire;

    /**
     * @ORM\Column(name="isadmin", type="boolean", options={"COMMENT":"type booléen"})
     *
     * Selon 'doctrine-project.org' tinyInt(1) se mappe vers un bool
     */
    private $isAdmin;

    /**
     * Utilisateur constructor.
     * @param $nom
     * @param $prenom
     * @param $anniversaire
     * @param $isAdmin
     */
    public function __construct($nom, $prenom, $anniversaire, $isAdmin)
    {
        $this->nom = null;
        $this->prenom = null;
        $this->anniversaire = null;
        $this->isAdmin = false;
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
}
