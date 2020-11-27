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
     * @Groups({"u_read"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
    *
     * @Groups({"u_read"})
     */
    private $adresse;

    /**
     * @ORM\ManyToMany(targetEntity=Groupes::class, mappedBy="apprenants")
     */
    private $groupes;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        
        $this->firstname = "firstname";
        $this->lastname = "lastname";
        $this->archive = 0;
        $this->telephone = "telephone";
        $this->adresse = "adresse";
        $this->password = "password";
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
}
