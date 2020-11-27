<?php

namespace App\Controller;

use App\Entity\Cm;
use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Helpers\UserHelper;
use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private $em;
    private $helper;
    private $repo;
    private $profilrepo;
    public function __construct(EntityManagerInterface $em,UserHelper $helper,UserRepository $repo,ProfilRepository $profilrepo)
    {
        $this->em = $em;
        $this->helper = $helper;
        $this->repo = $repo;
        $this->profilrepo = $profilrepo;
    }
    
    //creation d'un user
    public function add(UserHelper $helperUser, SerializerInterface $serializer, Request $request): Response
    {
        $userPostman = $request->request->all();
        $profil = $this->profilrepo->getProfil($userPostman['profil']);
        $profil = "/api/admin/profils/".$profil[0]->getId();
        $userPostman['profil'] = $profil;
        $profilUser = $serializer->denormalize($userPostman['profil'] , Profil::class);
        $user = $serializer->denormalize($userPostman,"App\Entity\\".ucfirst(strtolower($profilUser->getLibelle())));
        $helperUser->createUser($request,$user,$userPostman,$profilUser);
        
        return $this->json('create',Response::HTTP_OK);

    }
    
    //modification d'un user
    public function editUser($id,Request $request)
    {
        $data = $request->request->all();
        $user = $this->repo->find($id);
        
        foreach($data as $d=>$value) {
            if ($d !== "profil") {
                $setProperty = 'set'.ucfirst($d);
                $user->$setProperty($value);
            }
            $profil = $this->profilrepo->getProfil($data['profil']);
            $user->setProfil($profil[0]);
        }

        $image = $this->helper->traitementImage($request);
        $user->setAvatar($image);
        $this->em->flush();

        return $this->json("edited successfully",Response::HTTP_OK);
    }

}
