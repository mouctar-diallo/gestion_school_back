<?php

namespace App\Controller;

use App\Helpers\CompetenceHelper;
use App\Helpers\GroupeCompetenceHelper;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeCompetenceController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }
    //create groupe de competence
    public function create(Request $request, GroupeCompetenceHelper $helper): Response
    {
        $groupeCompetence = json_decode($request->getContent(), true);
        $testValide = $helper->addGroupeCompetence($groupeCompetence);
        if ($testValide != null) {
            return $this->json(404);
        }else{
            return $this->json("groupe added successfully", Response::HTTP_CREATED);
        }
    }


    //cree les  niveaux et rattache a une competence 
    public function addCompetenceAndLevels(Request $request, CompetenceHelper $helperCompetence)
    {
        $competence = json_decode($request->getContent(), true);
        $competence = $helperCompetence->addCompetenceAndLevels($competence);
        if ($competence) {
            $this->em->flush();
            return $this->json("created", Response::HTTP_CREATED);
        }else{
            return $this->json(404);
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
        $postaman = json_decode($request->getContent(), true);
        $helper->putGroupeCompetence($postaman,$id,$request);

        return $this->json("success");
    }

    //archiver
    public function archiverCompetence($id, CompetenceRepository $repo,EntityManagerInterface $em)
    {
        $competence = $repo->find($id);
        if ($competence) {
            $competence->setArchive(1);
            foreach ($competence->getGroupeCompetences() as $gp){
                $gp->removeCompetence($competence);
            }
            $em->flush();
            return $this->json(Response::HTTP_OK);
        }
        return $this->json(Response::HTTP_BAD_REQUEST);
    }
}
