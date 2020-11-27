<?php

namespace App\Controller;

use App\Entity\Promos;
use App\Entity\Groupes;
use App\Entity\Apprenant;
use App\Helpers\UserHelper;
use App\Repository\ProfilRepository;
use App\Repository\PromosRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PromosController extends AbstractController
{
    private $serialize;
    private $em;
    private $ref;
    private $formateur;
    private $apprenant;
    private $promos;
    private $profil;
    private $userHelper;
    private $encode;

    public function __construct(SerializerInterface $serialize,EntityManagerInterface $em,ReferentielsRepository $ref,FormateurRepository $formateur,ApprenantRepository $apprenant,PromosRepository $promos,ProfilRepository $profil,UserHelper $userHelper, UserPasswordEncoderInterface $encode)
    {
        $this->serialize = $serialize;
        $this->em = $em;
        $this->ref = $ref;
        $this->formateur = $formateur;
        $this->apprenant = $apprenant;
        $this->promos = $promos;
        $this->profil = $profil;
        $this->userHelper = $userHelper;
        $this->encode = $encode;
    }
    
    public function addPromos(Request $request)
    {

        $infosjson = json_decode($request->getContent(),true);
       $promos = new Promos;
          $promos->setLangue($infosjson['langue'])
                  ->setTitre($infosjson['titre'])
                  ->setDescription($infosjson['description'])
                  ->setLieu($infosjson['lieu'])
                  ->setFabrique($infosjson['fabrique'])
                  ->setEtat($infosjson['etat'])
                  ->setDateDebut(new \DateTime())
                  ->setDateFinProvisoire(new \DateTime())
                  ->setDateFinReel(new \DateTime());
            //affectons un referentiel au promos
           $referentiel = $this->ref->find($infosjson['referentiels']['id']);
           $promos->setReferentiels($referentiel);

        if ($infosjson['groupes']) {
            $groupes = new Groupes;
            $groupes->setNom($infosjson['groupes'][0]['nom'])
                ->setDateCreation(new \DateTime())
                ->setStatut($infosjson['groupes'][0]['statut'])
                ->setType($infosjson['groupes'][0]['type']);
            
            $promos->addGroupe($groupes);
        }
        //testons si user veut creer apprenant      
        if (isset($infosjson['apprenants'])) {
            foreach ($infosjson['apprenants'] as $student) {
                $apprenant = new Apprenant();
                $apprenant->setEmail($student['email']);
                $apprenant->setPassword($this->encode->encodePassword($apprenant,$apprenant->getPassword()));
                $groupes->addApprenant($apprenant);
                $this->em->persist($apprenant);
                $this->userHelper->sendMail($apprenant->getEmail());
            }
        }
        //affectons un formateur au groupe
        if (isset($infosjson['formateurs'])){
            for ($i= 0; $i < count($infosjson['formateurs']); $i++) {
                $teacher = $this->formateur->find($infosjson['formateurs'][$i]['id']);
                $groupes->addFormateur($teacher);
                $promos->addFormateur($teacher);
            }
        }

        $this->em->persist($groupes);
        $this->em->persist($promos);
        $this->em->flush();

        return $this->json($promos,Response::HTTP_CREATED);

    }
}
