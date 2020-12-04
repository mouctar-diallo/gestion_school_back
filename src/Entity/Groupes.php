<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupesRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=GroupesRepository::class)
 * 
 * @ApiResource(
 * 
 * collectionOperations={
 * 
 *      "get_admin_groupes"={
 *          "normalization_context"={"groups"={"grp:read"}},
 *          "method"= "GET",
 *          "path"= "/admin/groupes",
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))"
 *          
 *      },
 * 
 *     "get_admin_groupes_apprenants"={
 *          "method"= "GET",
 *           "normalization_context"={"groups"={"apprenants:read"}},
 *          "path"= "/admin/groupes/apprenants",
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') )"
 *          
 *      },
 * 
 *     "Create_groupes_apprennant_formateur"={
 *          "method"= "POST",
 *          "path"= "/admin/groupes",
 *          "route_name"="add_groupe"          
 *      }
 * },
 * 
 * itemOperations={
 * 
 *      "get_admin_groupes_id"={
 *          "method"= "GET",
 *          "path"= "/admin/groupes/{id}",
 *          "security" = "(is_granted('ROLE_ADMIN') )"
 *      },
 * 
 *      "Ajouter_apprenant_groupe"={
 *          "method"= "PUT",
 *          "path"= "/admin/groupes/{id}",
 *          "route_name"="add_app_groupe",
 *          "security" = "(is_granted('ROLE_ADMIN') )"
 *      },
 * 
 *      "delete_apprenant_groupe"={
 *          "method"= "DELETE",
 *          "route_name"="retire_apprenant"
 *       }
 *  }
 * )
 * 
 * @UniqueEntity("nom",message="le nom du groupe existe dejà")
 */
class Groupes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"promo:read","rfg:read","gp_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(message = "le nom est obligatoire")
     * 
     * @Groups({"grp:read","apprenants:read","promo:read","rfg:read","gp_read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     * 
     * @Groups({"grp:read","promo:read"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grp:read"})
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Promos::class, inversedBy="groupes")
     * 
     * @Groups({"grp:read","gp_read"})
     */
    private $promos;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes")
     * 
     *  @Groups({"grp:read"})
     */
    private $formateurs;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes")
     * 
     *  @Groups({"grp:read","apprenants:read","promo:read","gp_read","gp_read"})
     */
    private $apprenants;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default": 0})
     */
    private $archive;

    /**
     * @ORM\OneToMany(targetEntity=EtatBriefGroupe::class, mappedBy="groupe")
     */
    private $etatBriefGroupes;

    public function __construct()
    {
        $this->archive = 0;
        $this->formateurs = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
        $this->etatBriefGroupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        $this->formateurs->removeElement($formateur);

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        $this->apprenants->removeElement($apprenant);

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

    /**
     * @return Collection|EtatBriefGroupe[]
     */
    public function getEtatBriefGroupes(): Collection
    {
        return $this->etatBriefGroupes;
    }

    public function addEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if (!$this->etatBriefGroupes->contains($etatBriefGroupe)) {
            $this->etatBriefGroupes[] = $etatBriefGroupe;
            $etatBriefGroupe->setGroupe($this);
        }

        return $this;
    }

    public function removeEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if ($this->etatBriefGroupes->removeElement($etatBriefGroupe)) {
            // set the owning side to null (unless already changed)
            if ($etatBriefGroupe->getGroupe() === $this) {
                $etatBriefGroupe->setGroupe(null);
            }
        }

        return $this;
    }
}
