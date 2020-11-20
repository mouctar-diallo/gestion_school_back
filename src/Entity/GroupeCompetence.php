<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 * 
 * @ApiResource(
 *   collectionOperations={
 *      "get_competences"={
 *          "method"="GET",
 *          "path"="/admin/grpecompetences",
 *          "normalization_context"={"groups"={"c_read"}}
 *      },
 * 
 *      "get_groupeCompetences_competences"={
 *          "method"="GET",
 *          "path"="/admin/grpecompetences/competences",
 *          "normalization_context"={"groups"={"grp_read"}}
 *      },
 * 
 *     "add_grp_competence"={
*           "method"="POST",
 *          "route_name"="add_grp_competence"
 *     },
 *   },
 * 
 *      itemOperations={
 *          "get_groupe"={
 *              "method"="GET",
 *              "path"="/admin/grpecompetences/{id}",
 *              "normalization_context"={"groups"={"g_read"}}
 *          },
 * 
 *           "get_competences"={
 *              "method"="GET",
 *              "path"="/admin/grpecompetences/{id}/competences",
 *              "normalization_context"={"groups"={"grp_read"}}
 *          },
 * 
 *          "edit_groupe"={
 *              "method"="PUT",
 *              "path"="/admin/grpecompetences/{id}",
 *              "normalization_context"={"groups"={"grp_read"}}
 *          },
 *      }
 * )
 */
class GroupeCompetence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"grp_read","g_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups({"grp_read","g_read"})
     * 
     * @Assert\NotBlank(message = "le libelle est obligatoire")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups({"grp_read"})
     * 
     * 
     * @Assert\NotBlank(message = "la description est obligatoire")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, options={"default": 0})
     */
    private $isdeleted;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupeCompetences", cascade={"persist"})
     * 
     * 
     * @Groups({"c_read","grp_read"})
     */
    private $competence;

    public function __construct()
    {
        $this->competence = new ArrayCollection();
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

    public function getIsdeleted(): ?string
    {
        return $this->isdeleted;
    }

    public function setIsdeleted(string $isdeleted): self
    {
        $this->isdeleted = $isdeleted;

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetence(): Collection
    {
        return $this->competence;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competence->contains($competence)) {
            $this->competence[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        $this->competence->removeElement($competence);

        return $this;
    }
}
