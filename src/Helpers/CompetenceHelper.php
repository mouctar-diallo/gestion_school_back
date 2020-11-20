<?php

namespace App\Helpers;

use App\Entity\Niveau;
use App\Entity\Competence;
use App\Repository\NiveauRepository;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;

class CompetenceHelper 
{
    private $em;
    private $competenceRepository;
    private $niveauRepository;
    public function __construct(EntityManagerInterface $em,CompetenceRepository $competenceRepository,NiveauRepository $niveauRepository)
    {
        $this->em = $em;
        $this->competenceRepository = $competenceRepository;
        $this->niveauRepository = $niveauRepository;
    }

    public function addCompetenceAndLevels($niveaux)
    {
        if (isset($niveaux['niveau'])) {
            for ($i=0; $i < count($niveaux['niveau']); $i++) { 
                $niveau = new Niveau();
                $niveau->setLibelle($niveaux['niveau'][$i]['libelle']);
                $niveau->setCritereEvaluation($niveaux['niveau'][$i]['critere_evaluation']);
                $niveau->setGroupeActions($niveaux['niveau'][$i]['groupe_actions']);
                //affectation
                if (isset($niveaux['competence'])) {
                    $competence = $this->competenceRepository->find($niveaux['competence'][0]['id']);
                    $competence->addNiveau($niveau);
                }

                $this->em->persist($niveau);
                $this->em->flush();
            }
       }
    }

    //edit les niveaux de competence
    public function editNiveau($niveaux)
    {
        if (isset($niveaux['niveau'])) {
            for ($i=0; $i < count($niveaux['niveau']); $i++) { 
                $niveau = $this->niveauRepository->find($niveaux['niveau'][$i]['id']);
                if ($niveau) {
                    $niveau->setLibelle($niveaux['niveau'][$i]['libelle']);
                    $niveau->setCritereEvaluation($niveaux['niveau'][$i]['critere_evaluation']);
                    $niveau->setGroupeActions($niveaux['niveau'][$i]['groupe_actions']);
                    $this->em->flush();
                }
            }
        }
    }
}