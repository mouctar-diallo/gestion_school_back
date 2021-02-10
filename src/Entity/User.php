<?php

namespace App\Entity;

use App\Entity\Apprenant;
use App\Entity\Formateur;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * 
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name = "type", type = "string")
 * @ORM\DiscriminatorMap({"formateur"="Formateur","CM"= "Cm", "apprenant"="Apprenant","admin"="User"})
 * 
 * @ApiResource(
 *      collectionOperations={
 *          "get_users"={
 *              "method"="GET",
 *              "path"="/admin/users",
 *              "normalization_context"= {"groups"={"u_read"}},
 *          },
 * 
 *          "add_users"={
 *              "method"="POST",
 *              "route_name"="add_users",
 *          },
 *      
 *         "edit_users"={
 *              "method"="POST",
 *              "route_name"="edit_users",
 *          },
 *         
 *      },
 * 
 *      itemOperations={
 *          "get_one_user"={
 *              "method"="GET",
 *              "path"="/admin/users/{id}",
 *         },
 * 
 * 
 *          "archive_user"={
 *              "method"="PUT",
 *              "path"="/admin/users/{id}"
 *          },
 * 
 *      
 *      }
 * )
 * 
 * @ApiFilter(SearchFilter::class, properties={"email": "partial"})
 * @UniqueEntity("email",message="l'adresse email doit etre unique")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"u_read","promo:read","rfg:read","gp_read","promo_one_br","promo_gr_br","br_app_ass","student"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="l'adresse email est obligatoire")
     * @Assert\Email(message="l'adresse email pas valide")
     * 
     * @Groups({"p_users_read","u_read","grp:read","apprenants:read","promo:read","grpe_principale:read","rfg:read","gp_read","promo_ref_app","promo_gr_br","br_app_ass","student"})
     */
    private $email;

    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank(message="le mot de passe est obligatoire")
     *
     **/
    protected $password;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(message="le firstname est obligatoire")
     * 
     * @Groups({"p_users_read","u_read","grp:read","apprenants:read","promo:read","grpe_principale:read","rfg:read","gp_read","promo_ref_app","promo_gr_br","promo_one_br","br_app_ass","student"})
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(message="le lastname est obligatoire")
     * 
     * @Groups({"p_users_read","u_read","grp:read","apprenants:read","promo:read","grpe_principale:read","rfg:read","gp_read","promo_ref_app","promo_gr_br","promo_one_br","br_app_ass","student"})
     */
    protected $lastname;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * 
     * @Assert\NotBlank(message="veuillez choisir un profil")
     * 
     *  @Groups({"grp:read","u_read"})
     */
    protected $profil;

    /**
     * @ORM\Column(type="integer" , options={"default": 0})
     * 
     */
    protected $archive;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * 
     * @Groups({"u_read","p_users_read"})
     * 
     */
    private $avatar;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

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

    public function getAvatar()
    {
        $avatar = $this->avatar;
        if(!empty($avatar))
        {
            return (base64_encode(stream_get_contents($avatar)));
        }
        return $avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
