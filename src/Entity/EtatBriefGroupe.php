<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EtatBriefGroupeRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=EtatBriefGroupeRepository::class)
 */
class EtatBriefGroupe
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
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="etatBriefGroupes")
     * 
     * @Groups({"promo_gr_br"})
     */
    private $brief;

    /**
     * @ORM\ManyToOne(targetEntity=Groupes::class, inversedBy="etatBriefGroupes")
     * 
     * @Groups({"promo_gr_br","promo_one_br","br_app_ass"})
     */
    private $groupe;

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

    public function getGroupe(): ?Groupes
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupes $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }
}
