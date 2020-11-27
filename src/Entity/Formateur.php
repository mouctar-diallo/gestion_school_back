<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource(
 *      normalizationContext= {"groups"={"u_read"}},
 *   itemOperations={
 *          
 *          "get_one_formateur"={
 *              "method"="GET",
 *              "path"="/formateurs/{id}",
*               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or object==user)",
*               "security_message" = "access deni      le       ed",
 *          },
 * 
 *          "put_one_formateur"={
 *              "method"="PUT",
 *              "path"="/formateurs/{id}",
*               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or object==user)",
*               "security_message" = "access denied",
 *          },
 *    }
 * 
 * )
 */
class Formateur extends User
{
    /**
     * @ORM\ManyToMany(targetEntity=Groupes::class, mappedBy="formateurs")
     */
    private $groupes;

    /**
     * @ORM\ManyToMany(targetEntity=Promos::class, mappedBy="formateurs", cascade={"persist"})
     */
    private $promos;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->promos = new ArrayCollection();
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
            $groupe->addFormateur($this);
        }

        return $this;
    }

    public function removeGroupe(Groupes $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeFormateur($this);
        }

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
            $promo->addFormateur($this);
        }

        return $this;
    }

    public function removePromo(Promos $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            $promo->removeFormateur($this);
        }

        return $this;
    }
}
