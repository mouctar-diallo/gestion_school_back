<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\DataFixtures\ProfilFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $password = "passer";

        
        for ($i=0; $i < 2; $i++) { 
            $apprenant = new Apprenant();
            $apprenant->setFirstname($faker->firstname);
            $apprenant->setLastname($faker->lastname);
            $apprenant->setArchive(0);
            $apprenant->setEmail($faker->email);
            $apprenant->setTelephone($faker->phoneNumber);
            $apprenant->setAdresse($faker->city);
            $encoder = $this->encoder->encodePassword($apprenant,$password);
            $apprenant->setPassword($encoder);
            $apprenant->setProfil($this->getReference(ProfilFixtures::APPRENANT_REFERENCE));

              $manager->persist($apprenant);
        }

        $manager->flush();

    }

    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
