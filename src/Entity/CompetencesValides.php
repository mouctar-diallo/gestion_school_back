<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CompetencesValidesRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CompetencesValidesRepository::class)
 */
class CompetencesValides
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * 
     * @Groups({"promo_ref_app"})
     */
    private $niveau1;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo_ref_app"})
     */
    private $niveau2;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo_ref_app"})
     */
    private $niveau3;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiels::class, inversedBy="competencesValides")
     */
    private $referentiels;

    /**
     * @ORM\ManyToOne(targetEntity=Promos::class, inversedBy="competencesvalides")
     */
    private $promos;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="competencesvalides")
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="competencesvalides")
     * @Groups({"promo_ref_app"})
     */
    private $competence;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNiveau1(): ?string
    {
        return $this->niveau1;
    }

    public function setNiveau1(string $niveau1): self
    {
        $this->niveau1 = $niveau1;

        return $this;
    }

    public function getNiveau2(): ?string
    {
        return $this->niveau2;
    }

    public function setNiveau2(string $niveau2): self
    {
        $this->niveau2 = $niveau2;

        return $this;
    }

    public function getNiveau3(): ?string
    {
        return $this->niveau3;
    }

    public function setNiveau3(string $niveau3): self
    {
        $this->niveau3 = $niveau3;

        return $this;
    }

    public function getReferentiels(): ?Referentiels
    {
        return $this->referentiels;
    }

    public function setReferentiels(?Referentiels $referentiels): self
    {
        $this->referentiels = $referentiels;

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

    public function getApprenant(): ?Apprenant
    {
        return $this->apprenant;
    }

    public function setApprenant(?Apprenant $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

        return $this;
    }
}
