<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromosRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * collectionOperations={
 * 
 *      "get_admin_promo"={
 *          "normalization_context"={"groups"={"promo:read"}},
 *          "method" = "GET",
 *          "path"= "/admin/promo",
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') )"
 *          
 *      },
 * 
 *      "Ajouter_promo"={
 *          "normalization_context"={"groups"={"promo:read"}},
 *          "method"= "POST",
 *          "route_name"="add_promo",
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))"
 *          
 *      },
 * },
 * 
 *  itemOperations={
* 
*       "referentiels_get_subresource" = {
*               "method"= "GET",
*               "normalization_context"={"groups"={"ref_promo_gc:read"}},
*                "path" = "/admin/promo/{id}/referentiels",
*                "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
*                "security_message"= "vous n'avez pas accès"
*          },
*       "get_admin_promo_principal"={
 *          "normalization_context"={"groups"={"grpe_principale:read"}},
 *          "method" = "GET",
 *          "path"= "/admin/promo/{id}/principal",
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') )" 
 *      },
 *     "get_admin_promo_id"={
 *           "normalization_context"={"groups"={"rfg:read"}},
 *          "method"= "GET",
 *          "path"= "/admin/promo/{id}",
 *          "security" = "(is_granted('ROLE_ADMIN'))"
 *      },
 *      
 *      "modifier_promo_et_referentiel"={
 *          "method"= "PUT",
 *          "path"= "/admin/promo/{id}/referentiels",
*            "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *       },
 * 
 *      "Ajout_supprimer_formateurs"={
 *          "method"= "PUT",
 *          "route_name" = "formateur_promo",
*            "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *      },
 * 
 *      "Ajouter_supprimer_apprenant"={
 *          "method"= "PUT",
 *          "path"= "/admin/promo/{id}/apprenants",
 *          "route_name"="apprenant_promo",
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *      },
 *      "Modifier_statut_groupe"={
 *            "method"= "PUT",
 *             "route_name"="edit_statut",
 *            "path"= "/admin/promo/{id}/groupes/{idg}",
 *           "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))"
 *      },
 *       
 *       "formateurs_get_subresource"= {
 *              "normalization_context"={"groups"={"rfg:read"}},
 *               "method"= "GET",
 *               "path" = "/admin/promo/{id}/formateurs",
 *                "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *                "security_message"= "vous n'avez pas accès"
 *          } ,
 * 
 *          "get_apprenant_groupe_dans_un_promo"= {
 *               "method"= "GET",
 *               "route_name"="app_groupe_promo",
 *               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *               "security_message"= "vous n'avez pas accès",
 *          },
 * 
 *          "get_apprenant_attente_dans_un_promo"= {
 *               "method"= "GET",
 *               "path" = "/admin/promo/{id}/apprenants/attente",
 *               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *               "security_message"= "vous n'avez pas accès",
 *          },
 * }, 
 * )
 * @ORM\Entity(repositoryClass=PromosRepository::class)
 */
class Promos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"promo:read","grpe_principale:read","rfg:read","ref_promo_gc:read","gp_read","promo_ref_app","promo_gr_br"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255) 
     * 
     * @Groups({"grp:read"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *  @Groups({"grp:read","promo:read"})
     */
    private $lieu;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"grp:read","promo:read","grpe_principale:read","rfg:read","ref_promo_gc:read","gp_read","promo_ref_app","promo_gr_br"})
     */
    private $fabrique;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFinProvisoire;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFinReel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=Groupes::class, mappedBy="promos")
     * 
     * @Groups({"promo:read","rfg:read"})
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiels::class, inversedBy="promos")
     * 
     * @Groups({"promo:read","grpe_principale:read","rfg:read","ref_promo_gc:read","gp_read"})
     */
    private $referentiels;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="promos", cascade={"persist"})
     * 
     * @Groups({"promo:read","grpe_principale:read","rfg:read"})
     */
    private $formateurs;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="promos")
     * 
     * @Groups({"grpe_principale:read","promo_ref_app"})
     */
    private $apprenants;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default": 0})
     */
    private $archive;

    /**
     * @ORM\OneToMany(targetEntity=CompetencesValides::class, mappedBy="promos")
     */
    private $competencesvalides;

    /**
     * @ORM\OneToMany(targetEntity=BriefDuPromo::class, mappedBy="promos")
     */
    private $briefDuPromos;

    public function __construct()
    {
        $this->archive = 0;
        $this->groupes = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
        $this->competencesvalides = new ArrayCollection();
        $this->briefDuPromos = new ArrayCollection();
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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFinProvisoire(): ?\DateTimeInterface
    {
        return $this->dateFinProvisoire;
    }

    public function setDateFinProvisoire(\DateTimeInterface $dateFinProvisoire): self
    {
        $this->dateFinProvisoire = $dateFinProvisoire;

        return $this;
    }

    public function getDateFinReel(): ?\DateTimeInterface
    {
        return $this->dateFinReel;
    }

    public function setDateFinReel(\DateTimeInterface $dateFinReel): self
    {
        $this->dateFinReel = $dateFinReel;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection|Groupes[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupes $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->setPromos($this);
        }

        return $this;
    }

    public function removeGroupe(Groupes $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getPromos() === $this) {
                $groupe->setPromos(null);
            }
        }

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
            $apprenant->setPromos($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getPromos() === $this) {
                $apprenant->setPromos(null);
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

    /**
     * @return Collection|CompetencesValides[]
     */
    public function getCompetencesvalides(): Collection
    {
        return $this->competencesvalides;
    }

    public function addCompetencesvalide(CompetencesValides $competencesvalide): self
    {
        if (!$this->competencesvalides->contains($competencesvalide)) {
            $this->competencesvalides[] = $competencesvalide;
            $competencesvalide->setPromos($this);
        }

        return $this;
    }

    public function removeCompetencesvalide(CompetencesValides $competencesvalide): self
    {
        if ($this->competencesvalides->removeElement($competencesvalide)) {
            // set the owning side to null (unless already changed)
            if ($competencesvalide->getPromos() === $this) {
                $competencesvalide->setPromos(null);
            }
        }

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
            $briefDuPromo->setPromos($this);
        }

        return $this;
    }

    public function removeBriefDuPromo(BriefDuPromo $briefDuPromo): self
    {
        if ($this->briefDuPromos->removeElement($briefDuPromo)) {
            // set the owning side to null (unless already changed)
            if ($briefDuPromo->getPromos() === $this) {
                $briefDuPromo->setPromos(null);
            }
        }

        return $this;
    }
}
