<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * 
 * 
 * @ApiFilter(SearchFilter::class, properties={"archive": "partial"})

 * @ApiResource(
 *      attributes={
 *          "security"= "is_granted('ROLE_ADMIN')",
 *          "security_message"= "vous n'avez pas acces a cette ressource"
 *      },
 * 
 *      normalizationContext= {"groups"={"p_read"}},
 *      routePrefix="/admin",
 * 
 *  
 * itemOperations={
 *   "get"={},"put"={},"delete"={},
 *    "get_users_profil"={
 *          "method"="GET",
 *          "path"= "/profils/{id}/users",
 *          "normalization_context"={"groups"={"p_users_read"}}
 *     },
 *  },
 * )
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"p_read","p_users_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(message="le libelle est obligatoire")
     * @Groups({"p_read","p_users_read","p_write"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * 
     * @Groups({"p_users_read"})
     */
    private $users;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $archive;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

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
}
