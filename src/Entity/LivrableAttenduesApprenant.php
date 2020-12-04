<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\LivrableAttenduesApprenantRepository;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=LivrableAttenduesApprenantRepository::class)
 */
class LivrableAttenduesApprenant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="livrableAttenduesApprenants")
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity=LivrableAttendues::class, inversedBy="livrableAttenduesApprenant")
     */
    private $livrableAttendues;

  

    public function __construct()
    {
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getApprenant(): ?Apprenant
    {
        return $this->apprenant;
    }

    public function setApprenant(?Apprenant $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    public function getLivrableAttendues(): ?LivrableAttendues
    {
        return $this->livrableAttendues;
    }

    public function setLivrableAttendues(?LivrableAttendues $livrableAttendues): self
    {
        $this->livrableAttendues = $livrableAttendues;

        return $this;
    }
}
