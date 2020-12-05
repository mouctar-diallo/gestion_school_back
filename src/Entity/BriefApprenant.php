<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BriefApprenantRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=BriefApprenantRepository::class)
 */
class BriefApprenant
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
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="briefApprenant")
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity=BriefDuPromo::class, inversedBy="briefApprenants")
     * @Groups({"br_app_ass"})
     */
    private $briefDuPromo;

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

    public function getApprenant(): ?Apprenant
    {
        return $this->apprenant;
    }

    public function setApprenant(?Apprenant $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    public function getBriefDuPromo(): ?BriefDuPromo
    {
        return $this->briefDuPromo;
    }

    public function setBriefDuPromo(?BriefDuPromo $briefDuPromo): self
    {
        $this->briefDuPromo = $briefDuPromo;

        return $this;
    }
}
