<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\DataFixtures\ProfilFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture implements DependentFixtureInterface
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
            $admin = (new User())
              ->setFirstname($faker->firstname)
              ->setLastname($faker->lastname)
              ->setArchive(0)
              ->setEmail($faker->email);
              $encoder = $this->encoder->encodePassword($admin,$password);
              $admin->setPassword($encoder);
              $admin->setProfil($this->getReference(ProfilFixtures::ADMIN_REFERENCE));

              $manager->persist($admin);
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
