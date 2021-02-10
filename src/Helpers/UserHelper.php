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
    private $mailer;
    public function __construct(UserPasswordEncoderInterface $encode,EntityManagerInterface $manager,\Swift_Mailer $mailer)
    {
        $this->encode = $encode;
        $this->manager = $manager;
        $this->mailer = $mailer;
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
        if ($this->traitementImage($request)==false) {
            $user->setAvatar(null);
        }else{ 
            $image = $this->traitementImage($request);
            $user->setAvatar($image);
        }
        
        //data supplementaire de l'apprenants
        if($profil->getLibelle() == "APPRENANT"){
            $user->setAdresse($postman['adresse']);
            $user->setTelephone($postman['telephone']);
            $this->sendMail($postman['email']);
        }

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }
    //traitementImage
    public function traitementImage(Request $request)
    {
        if ($request->files->get("image")) {
            $image = $request->files->get("image");
            $image = fopen($image->getRealPath(),"r+");
            return $image;
        }
        return false;
    }

     //fonction qui gere l'envoie de mail
     public function sendMail($email)
     {
         $mail = (new \Swift_Message('candidature SONATEL ACADEMY'))
         ->setFrom('moucfady@gmail.com')
         ->setTo($email)
         ->setBody(
                     'Après les différentes étapes de sélection que tu as passé avec brio, 
                     nous t’informons que ta candidature a été retenue pour intégrer la troisième 
                     promotion de la première école de codage gratuite du Sénégal.
                     Veuillez confirmez le mail pour etre retenu dans la plateforme
                 ');
 
         $this->mailer->send($mail);
     }
}
