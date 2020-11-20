<?php

namespace App\Controller;

use App\Entity\CM;
use App\Entity\User;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    
    //creation d'un user
    public function add(Request $request,ProfilRepository $repoProfil,UserRepository $repoUser, EntityManagerInterface $manager): Response
    {
        $userObject = $request->request->all();
        
        $profilUser = $repoProfil->getLibelleProfil($userObject['profil']);
        if ($userObject['profil'] == "FORMATEUR") {
            $user = new Formateur();
            $user = $repoUser->DataCommunUser($request,$user,$userObject,$profilUser[0]);
        }else if ($userObject['profil'] == "CM"){
            $user = new CM();
            $user = $repoUser->DataCommunUser($request,$user,$userObject,$profilUser[0]);
        }else if ($userObject['profil'] == "ADMIN"){
            $user = new User();
            $user = $repoUser->DataCommunUser($request,$user,$userObject,$profilUser[0]);
        }else{
            $user = new Apprenant();
            $user = $repoUser->DataCommunUser($request,$user,$userObject,$profilUser[0]);
            $user->setAdresse($userObject['adresse']);
            $user->setTelephone($userObject['telephone']);
        }
        
        $manager->persist($user);
        $manager->flush();

        return $this->json("created",Response::HTTP_OK);
    }
}
