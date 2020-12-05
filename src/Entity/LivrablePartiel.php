<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivrablePartielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  
 *  collectionOperations={
 *          "initialisation_apprenat_competence"={
*               "route_name"= "init_apprenat_competence",
 *               "path"="/promo/{id}/apprenants/initialisation",
*               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *               "security_message"= "vous n'avez pas accès",
 *          },
 * 
 *          "get_apprenant_collection_competence"= {
 *               "method"= "GET",
 *               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *               "security_message"= "vous n'avez pas accès",
 *                "route_name" = "get_apprenant_collection_competence"
 *           },
 * 
 * *        "get_apprenant_competences"= {
 *               "method"= "GET",
 *               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *               "security_message"= "vous n'avez pas accès",
 *                "route_name" = "get_apprenant_competences"
 *          },
 * *       
 *          "get_apprenant_statistiques"= {
 *               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') )",
 *               "security_message"= "vous n'avez pas accès",
 *                "route_name" = "get_apprenant_statistiques"
 *          },
 * 
 *          "add_fil_discution_commntaire_formateur"={
*               "method"= "POST",
*               "route_name"= "add_fil_discution",
*               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *               "security_message"= "vous n'avez pas accès",
 *          },
 * 
 *         "get_collection_competence_statistiques"= {
 *              "method"= "GET",
 *               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') )",
 *               "security_message"= "vous n'avez pas accès",
 *                "route_name" = "get_collection_competence_statistiques"
 *          },
 * 
 *          "add_fil_discution_commntaire_apprenant"={
*               "method"= "POST",
*               "route_name"= "add_fil_discution_apprenant",
*               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT'))",
 *               "security_message"= "vous n'avez pas accès",
 *          },
 *   },
 * 
 * itemOperations={
 *          "get"={},
 *          "edit_statut_livrable_partiel"={
*               "method"= "PUT",
*               "route_name" = "edit_statut_livrable_partiel",
*               "path"= "/apprenants/{idA}/livrablepartiels/{idL}",
*               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT'))",
 *              "security_message"= "vous n'avez pas accès",
 *          },
 * 
 *          "add_edit_liv_partiel_brief"={
*               "method"= "PUT",
*               "route_name"= "add_edit_liv_partiel_brief",
*               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT'))",
 *               "security_message"= "vous n'avez pas accès",
 *          },
 * }
 * )
 * @ORM\Entity(repositoryClass=LivrablePartielRepository::class)
 */
class LivrablePartiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"promo_one_br"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo_one_br"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"promo_one_br"})
     */
    private $nbreRendue;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"promo_one_br"})
     */
    private $nbreCorrige;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="livrablePartiels")
     */
    private $niveaux;

    /**
     * @ORM\OneToMany(targetEntity=ApprenantLivrablePartiel::class, mappedBy="livrablePartiel")
     */
    private $apprenantLivrablePartiels;

    /**
     * @ORM\Column(type="date")
     * @Groups({"promo_one_br"})
     */
    private $delai;

    /**
     * @ORM\ManyToOne(targetEntity=BriefDuPromo::class, inversedBy="livrablePartiel")
     */
    private $briefDuPromo;

    public function __construct()
    {
        $this->niveaux = new ArrayCollection();
        $this->apprenantLivrablePartiels = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNbreRendue(): ?int
    {
        return $this->nbreRendue;
    }

    public function setNbreRendue(int $nbreRendue): self
    {
        $this->nbreRendue = $nbreRendue;

        return $this;
    }

    public function getNbreCorrige(): ?int
    {
        return $this->nbreCorrige;
    }

    public function setNbreCorrige(int $nbreCorrige): self
    {
        $this->nbreCorrige = $nbreCorrige;

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

    /**
     * @return Collection|ApprenantLivrablePartiel[]
     */
    public function getApprenantLivrablePartiels(): Collection
    {
        return $this->apprenantLivrablePartiels;
    }

    public function addApprenantLivrablePartiel(ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        if (!$this->apprenantLivrablePartiels->contains($apprenantLivrablePartiel)) {
            $this->apprenantLivrablePartiels[] = $apprenantLivrablePartiel;
            $apprenantLivrablePartiel->setLivrablePartiel($this);
        }

        return $this;
    }

    public function removeApprenantLivrablePartiel(ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        if ($this->apprenantLivrablePartiels->removeElement($apprenantLivrablePartiel)) {
            // set the owning side to null (unless already changed)
            if ($apprenantLivrablePartiel->getLivrablePartiel() === $this) {
                $apprenantLivrablePartiel->setLivrablePartiel(null);
            }
        }

        return $this;
    }

    public function getDelai(): ?\DateTimeInterface
    {
        return $this->delai;
    }

    public function setDelai(\DateTimeInterface $delai): self
    {
        $this->delai = $delai;

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
