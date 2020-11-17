<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    private $profilRepository;
    public function __construct(ProfilRepository $profilRepository)
    {
        $this->profilRepository = $profilRepository;
    }
    //recuperer les profils
    public function getProfils(): Response
    {
        $profils = $this->profilRepository->findAll();

        if ($profils) {
            return $this->json($profils,Response::HTTP_OK,[],['groups'=>"p_read"]);
        }
        return $this->json(Response::HTTP_BAD_REQUEST);
    }

    //recuperer les users d'un profil
    public function get_users_du_profis($id): Response
    {
        $profil = $this->profilRepository->find($id);

        if ($profil) {
            return $this->json($profil,Response::HTTP_OK,[],['groups'=>"p_users_read"]);
        }
        return $this->json("profil inexistant");
    }

    //recupere un profil
    public function getProfil($id): Response
    {
        $profil = $this->profilRepository->find($id);
        return $this->json($profil,Response::HTTP_OK,[],['groups'=>"p_read"]);
    }

    //cree un profil
    public function create(EntityManagerInterface $em,Request $request): Response
    {
        $json = json_decode($request->getContent(),true);
        
        $profil = new Profil();
        $profil->setLibelle($json['libelle']);
        $em->persist($profil);
        $em->flush();

        return $this->json("created",Response::HTTP_OK);
    }
}
