<?php

namespace App\Helpers;

use App\Entity\User;
use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserHelper 
{
    private $encode;
    private $manager;
    private $request;
    public function __construct(UserPasswordEncoderInterface $encode,EntityManagerInterface $manager)
    {
        $this->encode = $encode;
        $this->manager = $manager;
    }


    //add user
    public function createUser($request,$user,$postman,$profil)
    {

        $user->setFirstname($postman['firstname']);
        $user->setLastname($postman['lastname']);
        $user->setEmail($postman['email']);
        $user->setArchive(0);
        $user->setProfil($profil);
        $user->setPassword($this->encode->encodePassword($user,$postman['password']));
        //traitement image user
        $image = $this->traitementImage($request);
        $user->setAvatar($image);
        //data supplementaire de l'apprenants
        if($profil->getLibelle() == "APPRENANT"){
            $user->setAdresse($postman['adresse']);
            $user->setTelephone($postman['telephone']);
        }

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }

    public function traitementImage(Request $request)
    {
        $image = $request->files->get("image");
        $image = fopen($image->getRealPath(),"r+");

        return $image;
    }
}
