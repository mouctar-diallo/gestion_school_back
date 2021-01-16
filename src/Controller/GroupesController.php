<?php

namespace App\Controller;

use App\Entity\Groupes;
use App\Repository\PromosRepository;
use App\Repository\GroupesRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupesController extends AbstractController
{
   
    public function retireApprenantGroupe($id,$idA,GroupesRepository $repo,EntityManagerInterface $em,ApprenantRepository $aprepo)
    {
        $groupe = $repo->find($id);
        $apprenant = $aprepo->find($idA);
        if ($groupe && $apprenant) {
           $groupe->removeApprenant($apprenant);
           $em->flush();
           return $this->json('remove succesfully');
        }
        return $this->json('groupe ou apprenant inexistant');
    }
    //add apprenant group
    public function addApprenanatGroupe(Request $request,$id,GroupesRepository $repo,EntityManagerInterface $em,ApprenantRepository $aprepo)
    {
        $json = json_decode($request->getContent(),true);
        $groupe = $repo->find($id);
        if ($groupe && isset($json['apprenants'])) {
            for ($i= 0; $i < count($json['apprenants']); $i++) {
                $apprenant = $aprepo->find($json['apprenants'][$i]['id']);
                $groupe->addApprenant($apprenant);
            }
        }
        $em->flush();
        return $this->json('added succesfully');
    }
    //add groupe
    public function addGroupe(Request $request,EntityManagerInterface $em,ApprenantRepository $ap, FormateurRepository $for,PromosRepository $pro,ValidatorInterface $validator)
    {
        $json = json_decode($request->getContent(),true);
        $groupe = (new Groupes)
                    ->setNom($json['nom'])
                    ->setDateCreation(new \DateTime())
                    ->setStatut($json['statut'])
                    ->setType($json['type']);
        //add groupe in promos
        if (isset($json['promo']['id'])) {
            $promo = $pro->find($json['promo']['id']);
            $groupe->setPromos($promo);
        }
        if (isset($json['apprenants'])) {
            for ($i= 0; $i < count($json['apprenants']); $i++) {
                $apprenant = $ap->find($json['apprenants'][$i]['id']);
                $groupe->addApprenant($apprenant);
            }
        }
        if (isset($json['formateurs'])) {
            for ($i= 0; $i < count($json['formateurs']); $i++) {
                $formateur = $for->find($json['formateurs'][$i]['id']);
                $groupe->addFormateur($formateur);
            }
        }
        $errors = $validator->validate($groupe);
        if ($errors) {
            return $this->json($errors,Response::HTTP_BAD_REQUEST);
        }
        $em->persist($groupe);
        $em->flush();

        return $this->json($groupe,Response::HTTP_CREATED);
    }
}
