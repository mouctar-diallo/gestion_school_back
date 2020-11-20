<?php

namespace App\DataFixtures;

use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class GroupeCompetenceFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        for ($g=0; $g <3; $g++)
        {
            $groupeCompetence = (new GroupeCompetence())
                        ->setLibelle("GroupeCompetence ".$g)
                        ->setDescription("description ".$g)
                        ->setIsdeleted(0);
            for ($c=0; $c <5; $c++)
            {
                $competence = new Competence();
                $competence->setLibelle("compentence ".$c);
                $groupeCompetence->addCompetence($competence);
    
            }
            $manager->persist($groupeCompetence);

        }

        $manager->flush();

    }
}
