<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $password = "passer";

        
        for ($i=0; $i < 5; $i++) { 
            $user = (new User())
              ->setFirstname($faker->firstname)
              ->setLastname($faker->lastname)
              ->setEmail($faker->email);
              $encoder = $this->encoder->encodePassword($user,$password);
              $user->setPassword($encoder);
            
              $manager->persist($user);
        }

        $manager->flush();
    }
}
