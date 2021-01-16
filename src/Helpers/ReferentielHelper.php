<?php

namespace App\Helpers;

use App\Entity\GroupeCompetence;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielsRepository;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReferentielHelper
{
    private $ref;
    private $em;
    private $repo;
    private $validator;
    public function __construct(ReferentielsRepository $ref,EntityManagerInterface $em,GroupeCompetenceRepository $repo,ValidatorInterface $validator)
    {
        $this->ref = $ref;
        $this->em = $em;
        $this->repo = $repo;
        $this->validator = $validator;
    }

    public function putReferentiels($id,$postman,$request)
    {
        $ref = $this->ref->find($id);
        $ref->setLibelle($postman['libelle']);
        $ref->setPresentation($postman['presentation']);
        $ref->setCritereEvaluation($postman['critereEvaluation']);
        $ref->setCritereAdmission($postman['critereAdmission']);
        //add or remove groupe de competence
        if ($postman['option']=="delete") {
            $programme = $this->traitementProgrammeFile($request);
            if ($programme){
                $ref->setProgramme($programme);
            }
            for ($i=0; $i < count($postman['groupeCompetences']); $i++) { 
                if(isset($postman['groupeCompetences'][$i]))
                {
                    $id = $postman['groupeCompetences'][$i];
                    $groupeCompetence = $this->repo->find($id);
                    $ref->removeGroupeCompetence($groupeCompetence);
                    $this->em->flush();
                }    
            }
        }else if($postman['option'] == "add")
        {
            $programme = $this->traitementProgrammeFile($request);
            if ($programme){
                $ref->setProgramme($programme);
            }
            for ($i=0; $i < count($postman['groupeCompetences']); $i++) { 
                if(isset($postman['groupeCompetences'][$i]))
                {
                    $id = $postman['groupeCompetences'][$i];
                    $groupeCompetence = $this->repo->find($id);
                    $ref->addGroupeCompetence($groupeCompetence);
                    $this->em->flush();
                }else{
                    $group = new GroupeCompetence();
                    $group->setLibelle($postman['groupeCompetences'][$i]['libelle']);
                    $group->setDescription($postman['groupeCompetences'][$i]['description']);
                    $group->setIsdeleted(0);
                    $erreur = $this->validator->validate($group);
                    
                    if($erreur && count($erreur) >0){
                        return $erreur;
                    }
                    $this->em->persist($group);
                    $ref->addGroupeCompetence($group);
                    $this->em->flush();
                }
            }
        }
    }


    public function traitementProgrammeFile($request)
    {
        if ($request->files->get("programme")) {
            $programme = $request->files->get("programme");
            $programme = fopen($programme->getRealPath(),"r+");
            return $programme;
        }

        return null;
    }
}