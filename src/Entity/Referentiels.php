<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielsRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource(
 * 
 * collectionOperations={
 *      "get_referentiels_grpecompetences"={
 *          "normalization_context"={"groups"={"referentiels":"read"}},
 *          "method"= "GET",
 *          "path" = "/admin/referentiels",
 *           "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      },
 * 
 *      "get_grpecompetences_competences"={
 *          "normalization_context"={"groups"={"grpe_and_competences":"read"}},
 *          "method"= "GET",
 *          "path" = "/admin/referentiels/grpecompetences",
 *           "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      },
 * 
 *      "create_referentiels"={
 *          "method"= "POST",
 *          "path" = "/admin/referentiels",
 *           "security" = "is_granted('ROLE_ADMIN')",
 *      }
 * },
 * 
 * itemOperations={
 *      "get_grpecompetences_referentiels"={
 *          "normalization_context"={"groups"={"referentiels":"read"}},
 *          "method"= "GET",
 *          "path" = "/admin/referentiels/{id}",
 *           "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT'))",
 *      },
 * 
 *      "edit_grpecompetences_referentiels"={
 *          "method"= "PUT",
 *          "route_nam"="edit_referentiel",
 *           "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT'))",
 *      },
 * 
*      "competences_groupe_competences_ref"={
*              "method"= "GET",
*              "route_name"= "get_comp_gc_ref",
*              "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT'))",
*          },
 * }
 * ) * @ORM\Entity(repositoryClass=ReferentielsRepository::class)
 * 
 * @UniqueEntity("libelle",message="libelle du referentiel doit etre unique")
 */
class Referentiels
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"referentiels":"read","grpe_and_competences":"read","promo:read","grpe_principale:read","rfg:read","ref_promo_gc:read","gp_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiels":"read","grpe_and_competences":"read","ref:read","promo:read","grpe_principale:read","rfg:read","ref_promo_gc:read","gp_read"})
     * 
     * @Assert\NotBlank(message="libelle obligatoire")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $presentation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $programme;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, inversedBy="referentiels",cascade={"persist"})
     * @Groups({"referentiels":"read","grpe_and_competences":"read","ref:read","ref_promo_gc:read"})
     */
    private $groupeCompetences;

    /**
     * @ORM\OneToMany(targetEntity=Promos::class, mappedBy="referentiels")
     */
    private $promos;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default": 0})
     */
    private $archive;

    public function __construct()
    {
        $this->archive = 0;
        $this->groupeCompetences = new ArrayCollection();
        $this->promos = new ArrayCollection();
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getProgramme(): ?string
    {
        return $this->programme;
    }

    public function setProgramme(string $programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    /**
     * @return Collection|GroupeCompetence[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        $this->groupeCompetences->removeElement($groupeCompetence);

        return $this;
    }

    /**
     * @return Collection|Promos[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promos $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setReferentiels($this);
        }

        return $this;
    }

    public function removePromo(Promos $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiels() === $this) {
                $promo->setReferentiels(null);
            }
        }

        return $this;
    }

    public function getArchive(): ?int
    {
        return $this->archive;
    }

    public function setArchive(?int $archive): self
    {
        $this->archive = $archive;

        return $this;
    }
}
