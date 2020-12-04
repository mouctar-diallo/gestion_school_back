<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\ApprenantLivrablePartielRepository;

/**
 * @ApiResource(
 *  itemOperations={
 *      "get_comments"={
 *          "method": "GET",
 *          "path": "/formateurs/livrablepartiels/{id}/commentaires",
 *          "normalization_context"={"groups"={"commentaire:read"}}
 *      }
 *  }
 * )
 * @ORM\Entity(repositoryClass=ApprenantLivrablePartielRepository::class)
 */
class ApprenantLivrablePartiel
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
    private $etat;

    /**
     * @ORM\Column(type="date")
     */
    private $delai;

    /**
     * @ORM\Column(type="date")
     */
    private $dataRendue;

    /**
     * @ORM\ManyToOne(targetEntity=LivrablePartiel::class, inversedBy="apprenantLivrablePartiels")
     */
    private $livrablePartiel;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="apprenantLivrablePartiels")
     */
    private $apprenant;

    /**
     * @ORM\OneToOne(targetEntity=FilDiscution::class, cascade={"persist", "remove"})
     * 
     * @Groups({"commentaire:read"})
     */
    private $filDiscution;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

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

    public function getDataRendue(): ?\DateTimeInterface
    {
        return $this->dataRendue;
    }

    public function setDataRendue(\DateTimeInterface $dataRendue): self
    {
        $this->dataRendue = $dataRendue;

        return $this;
    }

    public function getLivrablePartiel(): ?LivrablePartiel
    {
        return $this->livrablePartiel;
    }

    public function setLivrablePartiel(?LivrablePartiel $livrablePartiel): self
    {
        $this->livrablePartiel = $livrablePartiel;

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

    public function getFilDiscution(): ?FilDiscution
    {
        return $this->filDiscution;
    }

    public function setFilDiscution(?FilDiscution $filDiscution): self
    {
        $this->filDiscution = $filDiscution;

        return $this;
    }
}
