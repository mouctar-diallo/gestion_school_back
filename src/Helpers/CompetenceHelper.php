<?php

namespace App\Helpers;

use App\Entity\Niveau;
use App\Entity\Competence;
use App\Repository\NiveauRepository;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class CompetenceHelper 
{
    private $em;
    private $competenceRepository;
    private $niveauRepository;
    private $serializer;
    public function __construct(EntityManagerInterface $em,CompetenceRepository $competenceRepository,NiveauRepository $niveauRepository,SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->competenceRepository = $competenceRepository;
        $this->niveauRepository = $niveauRepository;
        $this->serializer = $serializer;
    }

    public function addCompetenceAndLevels($competences)
    {
        $competence = new Competence();
        $competence->setLibelle($competences['libelle']);
        if (isset($competences['niveau'])) {
            for ($i=0; $i < count($competences['niveau']); $i++) { 
                $niveau = new Niveau();
                $niveau->setLibelle($competences['niveau'][$i]['libelle']);
                $niveau->setCritereEvaluation($competences['niveau'][$i]['critere_evaluation']);
                $niveau->setGroupeActions($competences['niveau'][$i]['groupe_actions']);
               
                $this->em->persist($niveau);
                $niveau->setCompetence($competence);
                $this->em->persist($competence);
            } 
       }
        $this->em->flush();
    }

    //edit les niveaux de competence
    public function editNiveau($id,$niveaux)
    {
        $comp = $this->competenceRepository->find($id);
        if ($comp && isset($niveaux['niveau'])){
            for ($i=0; $i < count($niveaux['niveau']); $i++) { 
                $niveau = $this->niveauRepository->find($niveaux['niveau'][$i]['id']);
                if ($niveau) {
                    $niveau->setLibelle($niveaux['niveau'][$i]['libelle']);
                    $niveau->setCritereEvaluation($niveaux['niveau'][$i]['critere_evaluation']);
                    $niveau->setGroupeActions($niveaux['niveau'][$i]['groupe_actions']);
                    $niveau->setCompetence($comp);
                    $this->em->flush();
                }
            }
        }
    }
}