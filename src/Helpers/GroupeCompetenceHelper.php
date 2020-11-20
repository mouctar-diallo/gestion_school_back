<?php

namespace App\Helpers;
use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GroupeCompetenceHelper 
{
    private $repo;
    private $em;
    public function __construct(EntityManagerInterface $em, CompetenceRepository $repo)
    {
        $this->em = $em;
        $this->repo = $repo;
    }

    public function addGroupeCompetence($groupejson)
    {
        $groupeCompetence = (new GroupeCompetence())
                ->setIsdeleted(0)
                ->setLibelle($groupejson['libelle'])
                ->setDescription($groupejson['description']);
        //add competence relier
        if (isset($groupejson['competence']))
        {
            for ($i= 0; $i<count($groupejson['competence']);$i++){
                $competence = new Competence();
                //add
                if(isset($groupejson['competence'][$i]['libelle'])){
                    $competence->setLibelle($groupejson['competence'][$i]['libelle']);
                    $groupeCompetence->addCompetence($competence);
                }else{
                    //affectations
                    $c =  $this->repo->find($groupejson['competence'][$i]['id']);
                    if($c){
                        $competence->setLibelle($c->getLibelle());
                        $groupeCompetence->addCompetence($competence);
                    }

                }
                $this->em->persist($groupeCompetence);
                $this->em->flush();
            }
        }
    }
}