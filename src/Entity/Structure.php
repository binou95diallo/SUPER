<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StructureRepository")
 */
class Structure
{
    const LIBELLESTRUCTURE="libelleStructure";
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelleStructure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Structure", inversedBy="structures")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Structure", mappedBy="parent")
     */
    private $structures;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BU", inversedBy="structure")
     */
    private $bU;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Utilisateur", mappedBy="structure")
     */
    private $utilisateur;

    public function __construct()
    {
        $this->structures = new ArrayCollection();
        $this->utilisateur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleStructure(): ?string
    {
        return $this->libelleStructure;
    }

    public function setLibelleStructure(string $libelleStructure): self
    {
        $this->libelleStructure = $libelleStructure;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getStructures(): Collection
    {
        return $this->structures;
    }

    public function addStructure(self $structure): self
    {
        if (!$this->structures->contains($structure)) {
            $this->structures[] = $structure;
            $structure->setParent($this);
        }

        return $this;
    }

    public function removeStructure(self $structure): self
    {
        if ($this->structures->contains($structure)) {
            $this->structures->removeElement($structure);
            // set the owning side to null (unless already changed)
            if ($structure->getParent() === $this) {
                $structure->setParent(null);
            }
        }

        return $this;
    }

    public function getBU(): ?BU
    {
        return $this->bU;
    }

    public function setBU(?BU $bU): self
    {
        $this->bU = $bU;

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getUtilisateur(): Collection
    {
        return $this->utilisateur;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateur->contains($utilisateur)) {
            $this->utilisateur[] = $utilisateur;
            $utilisateur->setStructure($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateur->contains($utilisateur)) {
            $this->utilisateur->removeElement($utilisateur);
            // set the owning side to null (unless already changed)
            if ($utilisateur->getStructure() === $this) {
                $utilisateur->setStructure(null);
            }
        }

        return $this;
    }
}
