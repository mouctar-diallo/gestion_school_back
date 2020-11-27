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
        $referentiel = $this->ref->find($id);
        if ($postman['option']=="delete") {
            for ($i=0; $i < count($postman['groupeCompetence']); $i++) { 
                if(isset($postman['groupeCompetence'][$i]['id']))
                {
                    $id = $postman['groupeCompetence'][$i]['id'];
                    $groupeCompetence = $this->repo->find($id);
                    $referentiel->removeGroupeCompetence($groupeCompetence);
                    $this->em->flush();
                }    
            }
        }else if($postman['option']== "add")
        {
            for ($i=0; $i < count($postman['groupeCompetence']); $i++) { 
                if(isset($postman['groupeCompetence'][$i]['id']))
                {
                    $id = $postman['groupeCompetence'][$i]['id'];
                    $groupeCompetence = $this->repo->find($id);
                    $referentiel->addGroupeCompetence($groupeCompetence);
                    $this->em->flush();
                }else{
                    $group = new GroupeCompetence();
                    $group->setLibelle($postman['groupeCompetence'][$i]['libelle']);
                    $group->setDescription($postman['groupeCompetence'][$i]['description']);
                    $group->setIsdeleted(0);
                    $erreur = $this->validator->validate($group);
                    
                    if($erreur && count($erreur) >0){
                        return $erreur;
                    }
                    $this->em->persist($group);
                    $referentiel->addGroupeCompetence($group);
                    $this->em->flush();
                }
            }
        }
    }
}