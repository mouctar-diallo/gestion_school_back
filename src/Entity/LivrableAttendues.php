<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivrableAttenduesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=LivrableAttenduesRepository::class)
 */
class LivrableAttendues
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, mappedBy="livrableAttendues", cascade={"persist"})
     */
    private $briefs;

    /**
     * @ORM\OneToMany(targetEntity=LivrableAttenduesApprenant::class, mappedBy="livrableAttendues")
     */
    private $livrableAttendues;

    public function __construct()
    {
        $this->briefs = new ArrayCollection();
        $this->livrableAttendues = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Brief[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
            $brief->addLivrableAttendue($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->removeElement($brief)) {
            $brief->removeLivrableAttendue($this);
        }

        return $this;
    }

    /**
     * @return Collection|LivrableAttenduesApprenant[]
     */
    public function getLivrableAttendues(): Collection
    {
        return $this->livrableAttendues;
    }

    public function addLivrableAttendue(LivrableAttenduesApprenant $livrableAttendue): self
    {
        if (!$this->livrableAttendues->contains($livrableAttendue)) {
            $this->livrableAttendues[] = $livrableAttendue;
            $livrableAttendue->setLivrableAttendues($this);
        }

        return $this;
    }

    public function removeLivrableAttendue(LivrableAttenduesApprenant $livrableAttendue): self
    {
        if ($this->livrableAttendues->removeElement($livrableAttendue)) {
            // set the owning side to null (unless already changed)
            if ($livrableAttendue->getLivrableAttendues() === $this) {
                $livrableAttendue->setLivrableAttendues(null);
            }
        }

        return $this;
    }
}
