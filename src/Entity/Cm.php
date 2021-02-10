<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\CMRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=CMRepository::class)
 * 
 *  @ApiResource(
 * normalizationContext={"groups"={"u_read"}},
 * *   itemOperations={
 *          
 *          "get_one_cm"={
 *              "method"="GET",
 *              "path"="/cm/{id}",
*               "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or object==user)",
*               "security_message" = "access denied",
 *          }
 *    }
 * )
 */
class Cm extends User
{
    
}
