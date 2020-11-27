<?php

namespace App\Entity;

use App\Entity\User;
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
    
}
