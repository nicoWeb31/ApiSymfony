<?php

namespace App\Entity;

use App\Entity\Livre;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuteursRepository")
 */
class Auteurs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"listeAuteursSimple"})
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listeAuteursFull","listeAuteursSimple"})
     * 
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listeAuteursFull","listeAuteursSimple"})
     * 
     */
    private $prenom;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Nationalite", inversedBy="auteurs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listeAuteursFull","listeAuteursSimple"})
     * 
     */
    private $Nationalite;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Livre", mappedBy="auteur")
     * 
     */
    private $livres;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNationalite(): ?Nationalite
    {
        return $this->Nationalite;
    }

    public function setNationalite(?Nationalite $Nationalite): self
    {
        $this->Nationalite = $Nationalite;

        return $this;
    }

    /**
     * @return Collection|Livre[]
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres[] = $livre;
            $livre->setAuteur($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->contains($livre)) {
            $this->livres->removeElement($livre);
            // set the owning side to null (unless already changed)
            if ($livre->getAuteur() === $this) {
                $livre->setAuteur(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return (string) $this->nom." ".$this->prenom;
    }
}
