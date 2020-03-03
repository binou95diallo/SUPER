<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BURepository")
 */
class BU
{
    const LIBELLEBU="libelleBU";
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelleBU;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validationAuto;

    /**
     * @ORM\Column(type="boolean")
     */
    private $demandeReport;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Structure", mappedBy="bU")
     */
    private $structure;

    public function __construct()
    {
        $this->structure = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleBU(): ?string
    {
        return $this->libelleBU;
    }

    public function setLibelleBU(string $libelleBU): self
    {
        $this->libelleBU = $libelleBU;

        return $this;
    }

    public function getValidationAuto(): ?bool
    {
        return $this->validationAuto;
    }

    public function setValidationAuto(bool $validationAuto): self
    {
        $this->validationAuto = $validationAuto;

        return $this;
    }

    public function getDemandeReport(): ?bool
    {
        return $this->demandeReport;
    }

    public function setDemandeReport(bool $demandeReport): self
    {
        $this->demandeReport = $demandeReport;

        return $this;
    }

    /**
     * @return Collection|Structure[]
     */
    public function getStructure(): Collection
    {
        return $this->structure;
    }

    public function addStructure(Structure $structure): self
    {
        if (!$this->structure->contains($structure)) {
            $this->structure[] = $structure;
            $structure->setBU($this);
        }

        return $this;
    }

    public function removeStructure(Structure $structure): self
    {
        if ($this->structure->contains($structure)) {
            $this->structure->removeElement($structure);
            // set the owning side to null (unless already changed)
            if ($structure->getBU() === $this) {
                $structure->setBU(null);
            }
        }

        return $this;
    }
}
