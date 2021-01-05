<?php

namespace App\Helpers;

use App\Entity\Niveau;
use App\Entity\Competence;
use App\Repository\NiveauRepository;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CompetenceHelper 
{
    private $em;
    private $competenceRepository;
    private $niveauRepository;
    private $grpeRepo;
    private $validator;
    public function __construct(EntityManagerInterface $em,CompetenceRepository $competenceRepository,NiveauRepository $niveauRepository,SerializerInterface $serializer, GroupeCompetenceRepository $grpeRepo,ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->competenceRepository = $competenceRepository;
        $this->niveauRepository = $niveauRepository;
        $this->serializer = $serializer;
        $this->grpeRepo = $grpeRepo;
        $this->validator = $validator;
    }

    public function addCompetenceAndLevels($competences)
    {
        $competence = new Competence();
        $competence->setLibelle($competences['libelle']);
        if (isset($competences['groupeCompetences'])) {
            $groupeCompetence = $this->grpeRepo->find($competences['groupeCompetences']);
            $competence->addGroupeCompetence($groupeCompetence);
        }
        if (isset($competences['niveau'])) {
            for ($i=0; $i < count($competences['niveau']); $i++) { 
                $niveau = new Niveau();
                $niveau->setLibelle($competences['niveau'][$i]['libelle']);
                $niveau->setCritereEvaluation($competences['niveau'][$i]['critere_evaluation']);
                $niveau->setGroupeActions($competences['niveau'][$i]['groupe_actions']);

                $this->em->persist($niveau);
                $this->em->persist($competence);
                $niveau->setCompetence($competence);
            } 
       }
       $erreur = $this->validator->validate($competence);
       if (count($erreur) > 0) {  
         return $erreur;
       }else{
        $this->em->flush(); 
       }
    }

    //edit les niveaux de competence
    public function editNiveau($id,$niveaux)
    {
        $comp = $this->competenceRepository->find($id);
        if ($comp && isset($niveaux['niveau'])){
            $comp->setLibelle($niveaux['libelle']);
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