<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * 
 * @ApiResource(
 *  collectionOperations={
 *      "attributs"={
 *          "security"="is_Granted('ROLE_ADMIN')",
 *          "security_message"="vous n'avez pas acces a cette ressource"
 *      },
 * 
 *      "get_profils"={
 *          "route_name"="get_profils",
 *          "method"="GET"
 *       },
 * 
 *      "add_profils"={
 *          "route_name"="add_profils",
 *          "method"="POST"
 *       },
 *  },
 * 
 *  itemOperations={
 *   "get"={},
 *    "get_users_profil"={
 *          "route_name"="get_users_profil",
 *          "method"="GET"
 *     },
 * 
 *    "get_profil"={
 *          "route_name"="get_profil",
 *          "method"="GET"
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
     * @Groups({"p_read","p_users_read"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * 
     * @Groups({"p_users_read"})
     */
    private $users;

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
}
