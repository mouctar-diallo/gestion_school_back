<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\NiveauRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 */
class Niveau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"niveaux_read","brief:read","promo_gr_br"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"niveaux_read","brief:read","promo_gr_br"})
     * 
     * @Assert\NotBlank(message="le libelle est obligatoire")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"niveaux_read"})
     * 
     * 
     * @Assert\NotBlank(message="le critere d'evaluation est obligatoire")
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"niveaux_read"})
     * 
     * 
     * @Assert\NotBlank(message="groupe action obligatoire")
     */
    private $groupeActions;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="niveau",cascade={"persist"})
     */
    private $competence;

    /**
     * @ORM\ManyToMany(targetEntity=LivrablePartiel::class, mappedBy="niveaux")
     */
    private $livrablePartiels;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, mappedBy="niveaux")
     */
    private $briefs;

    public function __construct()
    {
        $this->livrablePartiels = new ArrayCollection();
        $this->briefs = new ArrayCollection();
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

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    public function getGroupeActions(): ?string
    {
        return $this->groupeActions;
    }

    public function setGroupeActions(string $groupeActions): self
    {
        $this->groupeActions = $groupeActions;

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

    /**
     * @return Collection|LivrablePartiel[]
     */
    public function getLivrablePartiels(): Collection
    {
        return $this->livrablePartiels;
    }

    public function addLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if (!$this->livrablePartiels->contains($livrablePartiel)) {
            $this->livrablePartiels[] = $livrablePartiel;
            $livrablePartiel->addNiveau($this);
        }

        return $this;
    }

    public function removeLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if ($this->livrablePartiels->removeElement($livrablePartiel)) {
            $livrablePartiel->removeNiveau($this);
        }

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
            $brief->addNiveau($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->removeElement($brief)) {
            $brief->removeNiveau($this);
        }

        return $this;
    }
}
