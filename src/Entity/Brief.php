<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BriefRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * collectionOperations={
 * 
 *      "GET"={
 *         "normalization_context"={"groups"={"brief:read"}},
*          "method"= "GET",
*          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') )"
*       },
*
*      "get_brief_groupe_dans_une_promo"= {
*               "method"= "GET",
*               "route_name" = "get_brief_groupe_dans_une_promo",
*               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
*               "security_message"= "vous n'avez pas accès",
*                
*          },
*           "get_brief_brouillon_formateur"= {
 *               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *               "security_message"= "vous n'avez pas accès",
 *               "route_name" = "get_brief_brouillon_formateur"
 *          },
 * 
*          "get_brief_valide_formateur"= {
 *               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *               "security_message"= "vous n'avez pas accès",
 *               "route_name" = "get_brief_valide_formateur"
 *          },
 * 
 *           "get_one_brief_dans_une_promo"= {
 *               "method"= "GET",
 *               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *               "route_name" = "get_one_brief_dans_une_promo"
 *          },
 * 
*           "get_brief_dans_une_promo"= {
 *               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *               "security_message"= "vous n'avez pas accès",
 *               "route_name" = "get_brief_dans_une_promo"
 *          },
 *      "create_brief"={
 *          "route_name" = "create_brief",
 *           "method" = "POST",
 *           "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *             "security_message"= "vous n'avez pas accès",
 *      },
 * 
 * *     "get_briefs_apprenants_dans_une_promo"= {
 *               "method"= "GET",
 *               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *               "security_message"= "vous n'avez pas accès",
 *                "route_name" = "get_briefs_apprenants_dans_une_promo"
 *          },
 * 
 *      "apprenant_add_livrable_url"={
 *          "method" = "POST",
 *          "route_name" = "add_url_livrable",
 * *         "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'), or is_granted('ROLE_APPRENANT'))",
 *            "security_message"= "vous n'avez pas accès",
 *      }
 * },
 * 
 * itemOperations={
 *      "get"={},
 *      "duplique_brief"={
 *          "method" = "POST",
 *          "route_name" = "dupliquer_brief",
 *           "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *            "security_message"= "vous n'avez pas accès",
 *      },
 * 
 *      "affecter_desaffecter_brief"={
 *          "method" = "PUT",
 *          "route_name" = "affecter_brief",
*            "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
*             "security_message"= "vous n'avez pas accès",
 *      },
 * 
 *      "modifier_brief"={
 *          "method" = "PUT",
 *          "route_name" = "editer_brief",
*            "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
*             "security_message"= "vous n'avez pas accès",
 *      }
 * }
 * )
 * @ORM\Entity(repositoryClass=BriefRepository::class)
 */
class Brief
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"brief:read","br:read","brouillon"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"br:read","brouillon"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read","br:read"})
     */
    private $nomBrief;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"br:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"br:read","brouillon"})
     */
    private $contexte;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"br:read","brouillon"})
     */
    private $modalitePedagogique;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"br:read","brouillon"})
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $archive;

    /**
     * @ORM\Column(type="date")
     * @Groups({"brief:read","br:read"})
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     * @Groups({"promo_gr_br"})
     */
    private $formateurs;

    /**
     * @ORM\ManyToMany(targetEntity=Tags::class, inversedBy="briefs")
     * @Groups({"brief:read","promo_gr_br"})
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=BriefDuPromo::class, mappedBy="brief")
     */
    private $briefDuPromos;

    /**
     * @ORM\OneToMany(targetEntity=EtatBriefGroupe::class, mappedBy="brief")
     */
    private $etatBriefGroupes;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendues::class, inversedBy="briefs", cascade={"persist"})
     * @Groups({"brief:read","promo_gr_br"})
     */
    private $livrableAttendues;

    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="brief")
     * @Groups({"brief:read","promo_gr_br"})
     */
    private $ressources;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups({"brouillon"})
     */
    private $etatBrief;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="briefs")
     * @Groups({"brief:read","promo_gr_br"})
     */
    private $niveaux;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->briefDuPromos = new ArrayCollection();
        $this->etatBriefGroupes = new ArrayCollection();
        $this->livrableAttendues = new ArrayCollection();
        $this->ressources = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getNomBrief(): ?string
    {
        return $this->nomBrief;
    }

    public function setNomBrief(string $nomBrief): self
    {
        $this->nomBrief = $nomBrief;

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

    public function getContexte(): ?string
    {
        return $this->contexte;
    }

    public function setContexte(string $contexte): self
    {
        $this->contexte = $contexte;

        return $this;
    }

    public function getModalitePedagogique(): ?string
    {
        return $this->modalitePedagogique;
    }

    public function setModalitePedagogique(string $modalitePedagogique): self
    {
        $this->modalitePedagogique = $modalitePedagogique;

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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getArchive(): ?string
    {
        return $this->archive;
    }

    public function setArchive(string $archive): self
    {
        $this->archive = $archive;

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

    public function getFormateurs(): ?Formateur
    {
        return $this->formateurs;
    }

    public function setFormateurs(?Formateur $formateurs): self
    {
        $this->formateurs = $formateurs;

        return $this;
    }

    /**
     * @return Collection|Tags[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection|BriefDuPromo[]
     */
    public function getBriefDuPromos(): Collection
    {
        return $this->briefDuPromos;
    }

    public function addBriefDuPromo(BriefDuPromo $briefDuPromo): self
    {
        if (!$this->briefDuPromos->contains($briefDuPromo)) {
            $this->briefDuPromos[] = $briefDuPromo;
            $briefDuPromo->setBrief($this);
        }

        return $this;
    }

    public function removeBriefDuPromo(BriefDuPromo $briefDuPromo): self
    {
        if ($this->briefDuPromos->removeElement($briefDuPromo)) {
            // set the owning side to null (unless already changed)
            if ($briefDuPromo->getBrief() === $this) {
                $briefDuPromo->setBrief(null);
            }
        }

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
            $etatBriefGroupe->setBrief($this);
        }

        return $this;
    }

    public function removeEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if ($this->etatBriefGroupes->removeElement($etatBriefGroupe)) {
            // set the owning side to null (unless already changed)
            if ($etatBriefGroupe->getBrief() === $this) {
                $etatBriefGroupe->setBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LivrableAttendues[]
     */
    public function getLivrableAttendues(): Collection
    {
        return $this->livrableAttendues;
    }

    public function addLivrableAttendue(LivrableAttendues $livrableAttendue): self
    {
        if (!$this->livrableAttendues->contains($livrableAttendue)) {
            $this->livrableAttendues[] = $livrableAttendue;
        }

        return $this;
    }

    public function removeLivrableAttendue(LivrableAttendues $livrableAttendue): self
    {
        $this->livrableAttendues->removeElement($livrableAttendue);

        return $this;
    }

    /**
     * @return Collection|Ressource[]
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources[] = $ressource;
            $ressource->setBrief($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getBrief() === $this) {
                $ressource->setBrief(null);
            }
        }

        return $this;
    }

    public function getEtatBrief(): ?string
    {
        return $this->etatBrief;
    }

    public function setEtatBrief(string $etatBrief): self
    {
        $this->etatBrief = $etatBrief;

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        $this->niveaux->removeElement($niveau);

        return $this;
    }
}
