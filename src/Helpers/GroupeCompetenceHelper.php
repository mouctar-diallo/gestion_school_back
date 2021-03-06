<?php

namespace App\Helpers;
use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class GroupeCompetenceHelper 
{
    private $repo;
    private $em;
    private $grouperepo;
    private $validator;
    public function __construct(EntityManagerInterface $em, CompetenceRepository $repo,GroupeCompetenceRepository $grouperepo, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->repo = $repo;
        $this->grouperepo = $grouperepo;
        $this->validator = $validator;
    }

    public function addGroupeCompetence($groupejson)
    {
        $groupeCompetence = (new GroupeCompetence())
                ->setIsdeleted(0)
                ->setLibelle($groupejson['libelle'])
                ->setDescription($groupejson['description']);
        //add competence 
        if (isset($groupejson['competence']))
        {
            for ($i= 0; $i<count($groupejson['competence']);$i++){
                //affectations
                $c =  $this->repo->find($groupejson['competence'][$i]['id']);
                if($c){
                    $groupeCompetence->addCompetence($c);
                }
            }
            //validation
            $erreur = $this->validator->validate($groupeCompetence);
            if (count($erreur) > 0) {  
                return $erreur;
                }else{
                $this->em->persist($groupeCompetence);
                $this->em->flush(); 
            }
        }
    }


    //put groupe competence into
    public function putGroupeCompetence($postaman,$id,$request)
    {
        $groupecompetence = $this->grouperepo->find($id);
        if ($groupecompetence) {
            $groupecompetence->setLibelle($postaman['libelle']);
            $groupecompetence->setDescription($postaman['description']);
        }
        if(isset($postaman['option']) && $postaman['option'] == "add")
        {
            if ($postaman['competence'])
            {
                for ($i=0;$i<count($postaman['competence']); $i++)
                {
                    if (isset($postaman['competence'][$i]['id'])){
                        $comp = $this->repo->find($postaman['competence'][$i]['id']);
                        $groupecompetence->addCompetence($comp);
                    }else{
                        $c = new Competence();
                        if (isset($postaman['competence'][$i]['libelle'])) {
                            $c->setLibelle($postaman['competence'][$i]['libelle']);
                            $groupecompetence->addCompetence($c);
                            $this->em->persist($c);
                        }
                    }
                }
                $this->em->flush();
                return "success";
            }
        }else{
            for ($i=0;$i<count($postaman['competence']); $i++)
            {
                if (isset($postaman['competence'][$i]['id'])){
                    $comp = $this->repo->find($postaman['competence'][$i]['id']);
                    $groupecompetence->removeCompetence($comp);
                    $this->em->flush();
                }
            }
            return "edit success";
        }
       
    }
}