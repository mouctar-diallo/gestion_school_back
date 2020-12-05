<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilSortieRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
  * collectionOperations={
 *      "get_profils_sortie"={
 *          "method"= "GET",
 *          "path" = "/admin/profilsorties",
 *          "normalization_context"={"groups"={"profil_sortie:read"}},
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      },
 * 
 *     "get_apprenants_promo_profils_sortie"={
 *          "method"= "GET",
 *          "normalization_context"={"groups"={"student"}},
 *          "path" =  "/admin/promo/{id}/profilsorties(promo)",
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      },
 * 
 *      "create_profils_sortie"={
 *          "method"= "POST",
 *          "path" = "/admin/profilsorties",
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *      }
 * },
 * 
 * itemOperations={
 *  
 *      "get_one_profils_sortie"={
 *          "normalization_context"={"groups"={"student"}},
 *          "method"= "GET",
 *          "path" = "/admin/profilsorties/{id}",
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      },
 * 
 *      "edit_profils_sortie"={
 *          "method"= "PUT",
 *          "path" = "/admin/profilsortie/{id}",
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *      },
 * 
 *   "apprenant_promo_psortie"={ 
 *          "normalization_context"={"groups"={"student"}},
 *          "method"= "GET",
 *          "path" = "/admin/promo/{idpromo}/profilsortie/{id}(Promo)",
 *          "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *      },
 * 
 * }
 * )
 * @ORM\Entity(repositoryClass=ProfilSortieRepository::class)
 */
class ProfilSortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"profil_sortie:read","student"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil_sortie:read","student"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $archive;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="profilSortie")
     * @Groups({"student"})
     */
    private $apprenants;

    public function __construct()
    {
        $this->archive = 0;
        $this->apprenants = new ArrayCollection();
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

    public function getArchive(): ?int
    {
        return $this->archive;
    }

    public function setArchive(int $archive): self
    {
        $this->archive = $archive;

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
            $apprenant->setProfilSortie($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getProfilSortie() === $this) {
                $apprenant->setProfilSortie(null);
            }
        }

        return $this;
    }
}
