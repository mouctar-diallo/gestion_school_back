<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\CMRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=CMRepository::class)
 * 
 *  @ApiResource()

 */
class Cm extends User
{
    
}
