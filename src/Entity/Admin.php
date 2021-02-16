<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *  normalizationContext= {"groups"={"u_read"}},
 *   collectionOperations={
 *          "get_admins"={
 *              "method"="GET",
 *              "path"="/admins",
 *              "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *              "security_message"= "vous n'avez pas acces a cette ressource",
 *          },
 *    },
 * )
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 */
class Admin extends User
{

}
