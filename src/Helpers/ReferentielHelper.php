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

    public function putReferentiels($id,$postman)
    {
        $ref = $this->ref->find($id);
        if ($postman['option']=="delete") {
            for ($i=0; $i < count($postman['groupeCompetences']); $i++) { 
                if(isset($postman['groupeCompetences'][$i]['id']))
                {
                    $id = $postman['groupeCompetences'][$i]['id'];
                    $groupeCompetence = $this->repo->find($id);
                    $ref->removeGroupeCompetence($groupeCompetence);
                    $this->em->flush();
                }    
            }
        }else if($postman['option'] == "add")
        {
            for ($i=0; $i < count($postman['groupeCompetences']); $i++) { 
                if(isset($postman['groupeCompetences'][$i]['id']))
                {
                    $id = $postman['groupeCompetences'][$i]['id'];
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
}