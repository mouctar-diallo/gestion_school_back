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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $em;
    private $helper;
    private $repo;
    private $profilrepo;
    private $encode;
    public function __construct(EntityManagerInterface $em,UserHelper $helper,UserRepository $repo,ProfilRepository $profilrepo, UserPasswordEncoderInterface $encode)
    {
        $this->em = $em;
        $this->helper = $helper;
        $this->repo = $repo;
        $this->profilrepo = $profilrepo;
        $this->encode = $encode;
    }
    
    //creation d'un user
    public function add(UserHelper $helperUser, SerializerInterface $serializer, Request $request): Response
    {
        $userPostman = $request->request->all();
        $profil = $this->profilrepo->getProfil($userPostman['profil']);
        $profil = "/api/admin/profils/".$profil[0]->getId();
        $userPostman['profil'] = $profil;
        $profilUser = $serializer->denormalize($userPostman['profil'] , Profil::class);
        if ($profilUser->getLibelle()!=="ADMIN") {
            $user = $serializer->denormalize($userPostman,"App\Entity\\".ucfirst(strtolower($profilUser->getLibelle())));
        }else{
            $user = $user = $serializer->denormalize($userPostman,'App\Entity\User');
        }
        $helperUser->createUser($request,$user,$userPostman,$profilUser);
        
        return $this->json('create',Response::HTTP_OK);

    }
    
    //modification d'un user
    public function editUser($id,Request $request)
    {
        $data = $request->request->all();
        $user = $this->repo->find($id);  
        foreach($data as $d=>$value) {
            if ($d !== "profil" && $d!=="image" && $d!=="confirm") {
                $setProperty = 'set'.ucfirst($d);
                if ($value !== "") {
                    //encode password
                    if ($d=="password") {
                        $user->setPassword($this->encode->encodePassword($user,$value));
                    }else{
                        $user->$setProperty($value);
                    }
                }
            }
        }
        if(isset($data['profil'])){
            $profil = $this->profilrepo->getProfil($data['profil']);
            $user->setProfil($profil[0]);
        }
        $image = $this->helper->traitementImage($request);
        if (!$image) {
            //$user->setAvatar(null);
           /*si image n'existe pas il le met a null (definit sur l'entité user nullable=true)
            si user a deja une image et que ya pas d'image sur la requette il laisse l'image existante
           */
        }else{ $user->setAvatar($image);}
        
        $this->em->flush();

        return $this->json("edited successfully",Response::HTTP_OK);
    }

}
