<?php

namespace App\Controller;

use App\Entity\Promos;
use App\Entity\Groupes;
use App\Entity\Apprenant;
use App\Helpers\UserHelper;
use App\Helpers\PromoHelper;
use App\Repository\ProfilRepository;
use App\Repository\PromosRepository;
use App\Repository\GroupesRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReferentielsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
    private $profilRepo;
    private $userHelper;
    private $encode;
    private $validator;

    public function __construct(SerializerInterface $serialize,EntityManagerInterface $em,ReferentielsRepository $ref,FormateurRepository $formateur,ApprenantRepository $apprenant,PromosRepository $promos,ProfilRepository $profilRepo,UserHelper $userHelper, UserPasswordEncoderInterface $encode, ValidatorInterface $validator)
    {
        $this->serialize = $serialize;
        $this->em = $em;
        $this->ref = $ref;
        $this->formateur = $formateur;
        $this->apprenant = $apprenant;
        $this->promos = $promos;
        $this->profilRepo = $profilRepo;
        $this->userHelper = $userHelper;
        $this->encode = $encode;
        $this->validator = $validator;
    }
    
    public function addPromos(Request $request, PromoHelper $helper)
    {
        $infosjson = $request->request->all();
        //recuperons le fichier excel des apprenants
        $FileExcel = $request->files->get("ExcelFile");
        unset($infosjson['referentiels']);
        $promos = $this->serialize->denormalize($infosjson, Promos::class);
        $promos->setEtat("encours");
        $referentiel = $this->ref->find($request->request->get('referentiels'));
        if ($referentiel) {
            $promos->setReferentiels($referentiel);
        }
        //get All students in the Excel file in array
        $students = $helper->ApprenantFromFileExcel($FileExcel);   
        if ($students) { 
            $profil = $this->profilRepo->findOneByLibelle("Apprenant");
            foreach ($students as  $student){
                $apprenant = $this->serialize->denormalize($student, Apprenant::class);
                //testons si l'email ne se trouve pas dans la BDD
                $ExistEmail = $this->apprenant->findOneByEmail($apprenant->getEmail());
                if (!$ExistEmail){
                    $apprenant->setProfil($profil);
                    $apprenant->setPassword($this->encode->encodePassword($apprenant,$apprenant->getPassword()));
                    //send mail
                    $this->userHelper->sendMail($apprenant->getEmail());
                    $this->em->persist($apprenant);
                    $promos->addApprenant($apprenant);
                    $apprenant->setPromos($promos);
                }
            }  
        }
        $avatar = $this->userHelper->traitementImage($request);
        $promos->setAvatar($avatar);
        $erreur = $this->validator->validate($promos);
        if (count($erreur) > 0){
            return $this->json($erreur);
        }
        $this->em->persist($promos);
        $this->em->flush();
        
        return $this->json("added successfully",Response::HTTP_CREATED);
    }
    
    
    public function getApprenantGroupePromo($idp,$id,GroupesRepository $repo)
    {
        $groupeInPromo = $repo->ifGroupeInPromo($id,$idp);
        if ($groupeInPromo) {
            return $this->json($groupeInPromo,Response::HTTP_OK,[],['groups'=>"gp_read"]);
        }
        return $this->json(Response::HTTP_NOT_FOUND);
    }

    //add or delete apprenant in promo
    public function putApprenants($id,Request $request)
    {
        $postman = json_decode($request->getContent(),true);
        $promos = $this->promos->find($id);
        if ($postman['option'] == "add") {
            if ($promos && $postman['apprenants']) {
                foreach ($postman['apprenants'] as $student){
                    $apprenant = $this->apprenant->find($student['id']);
                    $promos->addApprenant($apprenant);
                }
            }
        }else{
            if ($promos && $postman['apprenants']) {
                foreach ($postman['apprenants'] as $student){
                    $apprenant = $this->apprenant->find($student['id']);
                    $promos->removeApprenant($apprenant);
                }
            }
        }
        $this->em->flush();
        return $this->json($promos,Response::HTTP_OK);
    }

    //add or delete formateur in promo
    public function putFormateurs($id,Request $request)
    {
        $postman = json_decode($request->getContent(),true);
        $promos = $this->promos->find($id);
        if ($postman['option'] == "add") {
            if ($promos && $postman['formateurs']) {
                foreach ($postman['formateurs'] as $teacher){
                    $formateur = $this->formateur->find($teacher['id']);
                    $promos->addFormateur($formateur);
                }
            }
        }else{
            if ($promos && $postman['formateurs']) {
                foreach ($postman['formateurs'] as $teacher){
                    $formateur = $this->formateur->find($teacher['id']);
                    $promos->removeFormateur($formateur);
                }
            }
        }
        $this->em->flush();
        return $this->json($promos,Response::HTTP_OK);
    }

    //edit statut group
    public function editStatut($id,$idg,GroupesRepository $groupeRepo,Request $request)
    {
        $json = json_decode($request->getContent());
        //testons si le groupe est dans la promo
        $existe = $groupeRepo->ifGroupeInPromo($idg,$id);
        if ($existe) {
            $groupe = $groupeRepo->find($idg);
            $groupe->setStatut($json->statut);
            $this->em->flush();
            return $this->json("statut modifié");
        }
        return $this->json("promo ou groupe inexistant");
    }
}
