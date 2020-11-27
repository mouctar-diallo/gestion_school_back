<?php

namespace App\Controller;

use App\Helpers\ReferentielHelper;
use App\Repository\ReferentielsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

    public function editReferentiels($id, Request $request,ReferentielHelper $help)
    {
      $referentiel = json_decode($request->getContent(),true);
      $test = $help->putReferentiels($id,$referentiel);
      if($test){
        return $this->json($test);
      }
      return $this->json("edited", Response::HTTP_CREATED);
    }
}
