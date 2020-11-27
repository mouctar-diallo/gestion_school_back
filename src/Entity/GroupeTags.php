<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeTagsRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * 
 * attributes={
 *      "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *      "security_message" = "vous n'avez pas accès a cette resource"
 *   },
 *
 *  collectionOperations={
 *      "get_grpe_tags"={
 *          "normalization_context"={"groups"={"grpeTags:read"}},
 *          "method" = "GET",
 *          "path" = "/admin/grptags"
 *      },
 * 
 *      "create_grpe_tags"={
 *          "route_name" = "create_grpe_tags",
 *          "method" = "POST"
 *      }
 * },
 * 
 * itemOperations={
 *      "get_one_grpe_tags"={
 *          "normalization_context"={"groups"={"grpeTags:read"}},
 *          "method" = "GET",
 *          "path"  = "/admin/grptags/{id}"
 *      },
 * 
 *      "edit_tags"={
 *          "method" = "PUT",
 *          "route_name" = "edit_grpe_tags"
 *      },
 * 
 *     "tags_get_subresource"={
 *          "method" = "GET",
 *          "path"  = "/admin/grptags/{id}/tags",
 *          "normalization_context"={"groups"={"tags_read"}}
 *      }
 * }
 * )
 * @ORM\Entity(repositoryClass=GroupeTagsRepository::class)
 */
class GroupeTags
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"grpeTags:read","t_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpeTags:read","t_read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Tags::class, inversedBy="groupeTags", cascade={"persist"})
     * 
     * @ApiSubresource
     * 
     * @Groups({"tags_read"})
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
}
