<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilFixtures extends Fixture
{
    public const ADMIN_REFERENCE = "ADMIN";
    public const FORMATEUR_REFERENCE = "FORMATEUR";
    public const CM_REFERENCE = "CM";
    public const APPRENANT_REFERENCE = "APPRENANT";

    public function load(ObjectManager $manager)
    {

        $profils = [
            self::ADMIN_REFERENCE,self::FORMATEUR_REFERENCE,self::CM_REFERENCE,self::APPRENANT_REFERENCE
        ];

        for ($i=0; $i < count($profils); $i++) { 
            $profil = new Profil();
            $profil->setLibelle($profils[$i]);

            if ($profils[$i] == self::ADMIN_REFERENCE) {
                $this->addReference(self::ADMIN_REFERENCE, $profil);
            }else if ($profils[$i] == self::FORMATEUR_REFERENCE){
                $this->addReference(self::FORMATEUR_REFERENCE, $profil);
            }else if ($profils[$i] == self::CM_REFERENCE){
                $this->addReference(self::CM_REFERENCE, $profil);
            }else{
                $this->addReference(self::APPRENANT_REFERENCE, $profil);
            }

            $manager->persist($profil);
            
        }

        $manager->flush();
    }
}
