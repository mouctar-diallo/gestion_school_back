<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BriefDuPromoRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=BriefDuPromoRepository::class)
 */
class BriefDuPromo
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
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="briefDuPromos")
     * @Groups({"br:read"})
     */
    private $brief;

    /**
     * @ORM\ManyToOne(targetEntity=Promos::class, inversedBy="briefDuPromos")
     */
    private $promos;

    /**
     * @ORM\OneToMany(targetEntity=BriefApprenant::class, mappedBy="briefDuPromo")
     */
    private $briefApprenants;

    /**
     * @ORM\OneToMany(targetEntity=LivrablePartiel::class, mappedBy="briefDuPromo")
     */
    private $livrablePartiel;

    public function __construct()
    {
        $this->briefApprenants = new ArrayCollection();
        $this->livrablePartiel = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getBrief(): ?Brief
    {
        return $this->brief;
    }

    public function setBrief(?Brief $brief): self
    {
        $this->brief = $brief;

        return $this;
    }

    public function getPromos(): ?Promos
    {
        return $this->promos;
    }

    public function setPromos(?Promos $promos): self
    {
        $this->promos = $promos;

        return $this;
    }

    /**
     * @return Collection|BriefApprenant[]
     */
    public function getBriefApprenants(): Collection
    {
        return $this->briefApprenants;
    }

    public function addBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if (!$this->briefApprenants->contains($briefApprenant)) {
            $this->briefApprenants[] = $briefApprenant;
            $briefApprenant->setBriefDuPromo($this);
        }

        return $this;
    }

    public function removeBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if ($this->briefApprenants->removeElement($briefApprenant)) {
            // set the owning side to null (unless already changed)
            if ($briefApprenant->getBriefDuPromo() === $this) {
                $briefApprenant->setBriefDuPromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LivrablePartiel[]
     */
    public function getLivrablePartiel(): Collection
    {
        return $this->livrablePartiel;
    }

    public function addLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if (!$this->livrablePartiel->contains($livrablePartiel)) {
            $this->livrablePartiel[] = $livrablePartiel;
            $livrablePartiel->setBriefDuPromo($this);
        }

        return $this;
    }

    public function removeLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if ($this->livrablePartiel->removeElement($livrablePartiel)) {
            // set the owning side to null (unless already changed)
            if ($livrablePartiel->getBriefDuPromo() === $this) {
                $livrablePartiel->setBriefDuPromo(null);
            }
        }

        return $this;
    }
}
