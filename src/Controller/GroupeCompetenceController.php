<?php

namespace App\Controller;

use App\Helpers\CompetenceHelper;
use App\Helpers\GroupeCompetenceHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeCompetenceController extends AbstractController
{
    //create groupe de competence
    public function create(Request $request, GroupeCompetenceHelper $helper): Response
    {
        $groupeCompetence = json_decode($request->getContent(), true);
        $helper->addGroupeCompetence($groupeCompetence);
        return $this->json('groupe added successfully');
    }


    //cree les  niveaux et rattache a une competence 
    public function addCompetenceAndLevels(Request $request, CompetenceHelper $helperCompetence)
    {
        $competence = json_decode($request->getContent(), true);
        $testValide = $helperCompetence->addCompetenceAndLevels($competence);
        if ($testValide != null) {
            return $this->json(404);
        }else{
            return $this->json("created", Response::HTTP_CREATED);
        }
        
    }


    //edit les niveaux d'un groupe de competence de
    public function editLevel($id,Request $request,CompetenceHelper $helper)
    {
        $niveaux = json_decode($request->getContent(), true);
        $helper->editNiveau($id,$niveaux);

        return $this->json("edited", Response::HTTP_OK);
    }

    //put groupe competence d'un groupe
    public function addOrRemoveCompetence($id,GroupeCompetenceHelper $helper,Request $request)
    {
        $postaman = json_decode($request->getContent());
        $helper->putGroupeCompetence($postaman,$id,$request);

        return $this->json("success");
    }
}
