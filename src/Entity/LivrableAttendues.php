<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivrableAttenduesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @Groups({"brief:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read","promo_gr_br"})
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
    private $livrableAttenduesApprenant;




    public function __construct()
    {
        $this->briefs = new ArrayCollection();
        $this->livrableAttenduesApprenant = new ArrayCollection();
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
    public function getLivrableAttenduesApprenant(): Collection
    {
        return $this->livrableAttenduesApprenant;
    }

    public function addLivrableAttenduesApprenant(LivrableAttenduesApprenant $livrableAttenduesApprenant): self
    {
        if (!$this->livrableAttenduesApprenant->contains($livrableAttenduesApprenant)) {
            $this->livrableAttenduesApprenant[] = $livrableAttenduesApprenant;
            $livrableAttenduesApprenant->setLivrableAttendues($this);
        }

        return $this;
    }

    public function removeLivrableAttenduesApprenant(LivrableAttenduesApprenant $livrableAttenduesApprenant): self
    {
        if ($this->livrableAttenduesApprenant->removeElement($livrableAttenduesApprenant)) {
            // set the owning side to null (unless already changed)
            if ($livrableAttenduesApprenant->getLivrableAttendues() === $this) {
                $livrableAttenduesApprenant->setLivrableAttendues(null);
            }
        }

        return $this;
    }

}
