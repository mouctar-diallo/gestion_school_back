<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * 
 * @ApiResource(
 *  normalizationContext= {"groups"={"u_read"}},
 *   collectionOperations={
 *          "get_apprenants"={
 *              "method"="GET",
 *              "path"="/apprenants",
 *              "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *              "security_message"= "vous n'avez pas acces a cette ressource",
 *          },
 *    },
 * 
 *     itemOperations={
 *         "get_one_apprenant"={
 *              "method"="GET",
 *              "path"="/apprenants/{id}",
 *              "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or object==user)",
 *          },
 * 
 * *          "put_one_apprenant"={
 *              "method"="PUT",
 *              "path"="/apprenants/{id}",
*               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_APPRENANT') or object==user)",
*               "security_message" = "access denied",
 *          },
 *  }
 * )
 */
class Apprenant extends User
{
    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups({"u_read","grp:read","apprenants:read","promo:read","gp_read"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
    *
     * @Groups({"u_read","grp:read","apprenants:read","promo:read","gp_read"})
     */
    private $adresse;

    /**
     * @ORM\ManyToMany(targetEntity=Groupes::class, mappedBy="apprenants")
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity=Promos::class, inversedBy="apprenants")
     */
    private $promos;

    /**
     * @ORM\OneToMany(targetEntity=ApprenantLivrablePartiel::class, mappedBy="apprenant")
     */
    private $apprenantLivrablePartiels;

    /**
     * @ORM\OneToMany(targetEntity=CompetencesValides::class, mappedBy="apprenant")
     * 
     * 
     * @Groups({"promo_ref_app"})
     */
    private $competencesvalides;

    /**
     * @ORM\OneToMany(targetEntity=BriefApprenant::class, mappedBy="apprenant")
     */
    private $briefApprenant;

    /**
     * @ORM\OneToMany(targetEntity=LivrableAttenduesApprenant::class, mappedBy="apprenant")
     */
    private $livrableAttenduesApprenants;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        
        $this->firstname = "firstname";
        $this->lastname = "lastname";
        $this->archive = 0;
        $this->telephone = "telephone";
        $this->adresse = "adresse";
        $this->password = "password";
        $this->apprenantLivrablePartiels = new ArrayCollection();
        $this->competencesvalides = new ArrayCollection();
        $this->briefApprenant = new ArrayCollection();
        $this->livrableAttenduesApprenants = new ArrayCollection();
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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
            $groupe->addApprenant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupes $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeApprenant($this);
        }

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
            $apprenantLivrablePartiel->setApprenant($this);
        }

        return $this;
    }

    public function removeApprenantLivrablePartiel(ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        if ($this->apprenantLivrablePartiels->removeElement($apprenantLivrablePartiel)) {
            // set the owning side to null (unless already changed)
            if ($apprenantLivrablePartiel->getApprenant() === $this) {
                $apprenantLivrablePartiel->setApprenant(null);
            }
        }

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
            $competencesvalide->setApprenant($this);
        }

        return $this;
    }

    public function removeCompetencesvalide(CompetencesValides $competencesvalide): self
    {
        if ($this->competencesvalides->removeElement($competencesvalide)) {
            // set the owning side to null (unless already changed)
            if ($competencesvalide->getApprenant() === $this) {
                $competencesvalide->setApprenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BriefApprenant[]
     */
    public function getBriefApprenant(): Collection
    {
        return $this->briefApprenant;
    }

    public function addBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if (!$this->briefApprenant->contains($briefApprenant)) {
            $this->briefApprenant[] = $briefApprenant;
            $briefApprenant->setApprenant($this);
        }

        return $this;
    }

    public function removeBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if ($this->briefApprenant->removeElement($briefApprenant)) {
            // set the owning side to null (unless already changed)
            if ($briefApprenant->getApprenant() === $this) {
                $briefApprenant->setApprenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LivrableAttenduesApprenant[]
     */
    public function getLivrableAttenduesApprenants(): Collection
    {
        return $this->livrableAttenduesApprenants;
    }

    public function addLivrableAttenduesApprenant(LivrableAttenduesApprenant $livrableAttenduesApprenant): self
    {
        if (!$this->livrableAttenduesApprenants->contains($livrableAttenduesApprenant)) {
            $this->livrableAttenduesApprenants[] = $livrableAttenduesApprenant;
            $livrableAttenduesApprenant->setApprenant($this);
        }

        return $this;
    }

    public function removeLivrableAttenduesApprenant(LivrableAttenduesApprenant $livrableAttenduesApprenant): self
    {
        if ($this->livrableAttenduesApprenants->removeElement($livrableAttenduesApprenant)) {
            // set the owning side to null (unless already changed)
            if ($livrableAttenduesApprenant->getApprenant() === $this) {
                $livrableAttenduesApprenant->setApprenant(null);
            }
        }

        return $this;
    }
}
