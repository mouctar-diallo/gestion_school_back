<?php

namespace App\Controller;

use App\Entity\Referentiels;
use App\Helpers\ReferentielHelper;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielsRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReferentielController extends AbstractController
{
    //competencess d'un groupe de competences d'un referentiel
    public function getCompetences_gc_referentiels(ReferentielsRepository $referentiel,$id,$idgc)
    {
       $test = $referentiel->ifGroupeInReferentiel($id,$idgc);
       if ($test) {
           return $this->json($test,200,[],["groups"=>"ref:read"]);
       }else{
         return $this->json("refrentiel ou groupe competence inexistant");
       }
    }

    public function edit($id,Request $request, ReferentielHelper $help)
    {
      $json = $request->request->all();
      $test = $help->putReferentiels($id,$json,$request);
      if($test){
        return $this->json($test);
      }
      return $this->json("edited", Response::HTTP_CREATED);
    }

    //ajout d'un referentiel
    public function addReferentiel(EntityManagerInterface $em, ValidatorInterface $validator,Request $request, GroupeCompetenceRepository $grpRepo, ReferentielHelper $help)
    {
      $json = $request->request->all();
      $referentiel = (new Referentiels())
          ->setLibelle($json['libelle'])
          ->setPresentation($json['presentation'])
          ->setCritereEvaluation($json['critereEvaluation'])
          ->setCritereAdmission($json['critereAdmission']);
          //gerons le programme file
          $programme = $help->traitementProgrammeFile($request);
          if ($programme) {
            $referentiel->setProgramme($programme);
          }
           
      if (isset($json['groupeCompetences'])) {
        for ($i=0; $i < count($json['groupeCompetences']); $i++) { 
          $groupe = $grpRepo->find($json['groupeCompetences'][$i]);
          $referentiel->addGroupeCompetence($groupe);
        }
      }
     
      $erreur = $validator->validate($referentiel);
      if (count($erreur) > 0) {
        return $this->json($erreur);
      }
      $em->persist($referentiel);
      $em->flush();
      return $this->json('added successfully',Response::HTTP_CREATED);
    }

    public function ArchiverReferentiel(EntityManagerInterface $em,ReferentielsRepository $repo, $id)
    {
      $referentiel = $repo->find($id);
      
      if ($referentiel) {
        $referentiel->setArchive(1);
        //archive promos associées
        foreach ($referentiel->getPromos() as $p){
          $p->setArchive(1);
        }
        $em->flush();
        return $this->json(Response::HTTP_OK);
      }
      return $this->json("referentiel innexistant");
    }
}
