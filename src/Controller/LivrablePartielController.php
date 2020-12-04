<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\FilDiscution;
use App\Entity\LivrablePartiel;
use App\Entity\CompetencesValides;
use App\Repository\NiveauRepository;
use App\Repository\PromosRepository;
use App\Repository\ApprenantRepository;
use App\Entity\ApprenantLivrablePartiel;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use App\Repository\BriefDuPromoRepository;
use App\Repository\BriefApprenantRepository;
use App\Repository\LivrablePartielRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CompetencesValidesRepository;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\ApprenantLivrablePartielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LivrablePartielController extends AbstractController
{
    private $promosRepo;
    private $apprenantRepo;
    private $competenceRepository;
    private $em;
    private $livrableRepo;
    private $compvalideRepo;
    private $briefDupromoRepo;
    private $niveauRepo;

    public function __construct(PromosRepository $promosRepo,ApprenantRepository $apprenantRepo,CompetenceRepository $competenceRepository,EntityManagerInterface $em,LivrablePartielRepository $livrableRepo,CompetencesValidesRepository $compvalideRepo,BriefDuPromoRepository $briefDupromoRepo,NiveauRepository $niveauRepo)
    {
        $this->promosRepo = $promosRepo;
        $this->apprenantRepo = $apprenantRepo;
        $this->competenceRepository = $competenceRepository;
        $this->em = $em;
        $this->livrableRepo = $livrableRepo;
        $this->compvalideRepo = $compvalideRepo;
        $this->briefDupromoRepo = $briefDupromoRepo;
        $this->niveauRepo = $niveauRepo;
    }
       
    
    // Recupere les apprenants d une promo dans  un referentiel avec competences et statut niveaux
    public function getCompetencesApprenants($idp,$id)
    {
        $promos = $this->promosRepo->ifRefInPromo($idp,$id);
       if ($promos) {
            return $this->json($promos,Response::HTTP_OK,[],['groups'=>"promo_ref_app"]);
       }
       return $this->json("promo ou referentiel inexistant");
    }

    // Recupere les competences d un apprenant d'une promo
    public function getCompetencesApprenant($ida,$id,$idp)
    {
        $promos = $this->compvalideRepo->ifRefAppInPromo($ida,$idp,$id);
       if ($promos) {
            return $this->json($promos,Response::HTTP_OK,[],['groups'=>"promo_ref_app"]);
       }
       return $this->json("promo ou referentiel ou apprenant inexistant");
    }

        //put statut liv partiel apprenant
        public function editStatutApprenantLivrablePartiel($idA,$idL,Request $request,SerializerInterface $serializer, ApprenantLivrablePartielRepository $apprenatLivPartielRepo)
        {
            $json = json_decode($request->getContent(),true);
            $livrablepartiel = $this->livrableRepo->find($idL);
            $apprenant = $this->apprenantRepo->find($idA);
            if ($apprenant && $livrablepartiel) {
                foreach ($apprenant->getApprenantLivrablePartiels() as $liv){
                    if ($liv->getId() == $idL) {
                        $liv->setEtat($json['etat']);
                        $this->em->flush();
                        return $this->json("edited successfully");
                    }
                }
                
            }else{
                return $this->json("apprenant ou livrable partiel innexistant");
            }
        }


    // Recuperes les statistiques d un apprenant
    public function getStatistiquesApprenant($ida,$idp,$idr,BriefApprenantRepository $br)
    {
        $exist=$this->compvalideRepo->ifRefAppInPromo($ida,$idp,$idr);
        if($exist) {
            $apprenant=$this->apprenantRepo->find($ida);
            if($apprenant) {
                $briefstatus=$apprenant->getBriefApprenant();
                $nbrevalid=0;$nbreAssigne=0;$nbreNonvalide=0;$nbrerendu=0;
                foreach($briefstatus as $br) {
                 if($br->getStatut()=="assigne") {
                     $nbreAssigne++;
                 }
                 if($br->getStatut()=="valide") {
                    $nbrevalid++;
                }   
                if($br->getStatut()=="nonvalide") {
                    $nbreNonvalide++;
                }   
             }
             $nbrerendu=$nbrevalid+$nbreNonvalide;
             $tab=["Apprenant"=>$apprenant ,"Assigne"=>$nbreAssigne ,"valide" =>$nbrevalid,"nonvalide"=>$nbreNonvalide,"rendu"=>$nbrerendu ];
            
             return $this->json($tab,Response::HTTP_OK,[],['groups'=>"liste_br_app_statut"]);
            }
        }
    }

    //add/delete livrables partiels d'un brief
    public function addDeleteLivrablPartielBriefs($idp,$id,Request $request,SerializerInterface $serializer)
    {
        
        $json = json_decode($request->getContent());
        $briefDupromo = $this->briefDupromoRepo->ifBriefInPromo($idp,$id);
        $livrablepartiel = new LivrablePartiel;
        if ($briefDupromo) {
            if (isset($json->livrablePartiels)) { 
                for ($i=0; $i < count($json->livrablePartiels) ; $i++) { 
                    $livrablepartiel 
                        ->setLibelle($json->livrablePartiels[$i]->libelle)
                        ->setDescription($json->livrablePartiels[$i]->description)
                        ->setDelai(new \DateTime())
                        ->setNbreCorrige(intval($json->livrablePartiels[$i]->nbreCorrige))
                        ->setNbreRendue(intval($json->livrablePartiels[$i]->nbreRendue))
                        ->setType($json->livrablePartiels[$i]->type)
                        ->setBriefDuPromo($briefDupromo[0])
                    ;
                    //ajout des niveaux au livrable partiel
                    if (isset($json->niveaux)) {
                        for ($n=0; $n < count($json->niveaux); $n++) { 
                            $livrablepartiel->addNiveau($this->niveauRepo->find($json->niveaux[$n]->id));
                        }
                    }
                    $this->em->persist($livrablepartiel);
                }
                
                $this->em->flush();
                return $this->json("livrable partiel added successfully");
            }else{
                //delete livrable partiel
                foreach ($briefDupromo[0]->getLivrablePartiels() as $livrable) {
                    $briefDupromo[0]->removeLivrablePartiel($livrable);
                    $this->em->flush();
                }
                return $this->json("remove successfully");
            }
        }
    }

    //getCollectionCompetenceStatistiques
     // Recupere une collection de competence d un referentiel d une promo et pour chaque niveau 
     // on aura le nombre d apprenant ayant validé ce niveau de competence 
     public function getCollectionCompetenceStatistiques($idp,$idr,CompetencesValidesRepository $cvaliderepos,CompetenceRepository $com)
     {
        $refer=$cvaliderepos->ifRefInPromo($idp,$idr);
       
        if ($refer) {
            $competences=$com->findAll();
              if($competences) {
                foreach($competences as  $comp) {
                    $tab[]=$comp->getCompetencesValides();
                }     
                 $nligne=count($cvaliderepos->findAll());
                 $t=[];
                 $ta=array();
                 for ($i=0; $i <count($tab) ; $i++) 
                   {
                    $nbrniveau1=0;$nbreniveau2=0;$nbreniveau3=0;
                     for ($j=0; $j <($nligne/count($tab)) ; $j++) 
                        {   
                            $cle=$tab[$i][$j]->getCompetence()->getLibelle();                       
                               if( $tab[$i][$j]->getNiveau1()=="1" )
                                  {
                                    $nbrniveau1++;   
                                  }
                               $t[$cle]["niveau1"]=$nbrniveau1;
                               if( $tab[$i][$j]->getNiveau2()=="1" ) 
                               {
                                  $nbreniveau2++;                        
                               }
                               $t[$cle]["niveau2"]=$nbreniveau2;
                        
                            if( $tab[$i][$j]->getNiveau3()=="1" ) 
                              {
                                $nbreniveau3++;
                              }
                              $t[$cle]["niveau3"]=$nbreniveau3;
                        
                       } 
                         $ta[$cle][$i]=$t[$cle];
                    }
               
                    return $this->json($ta,Response::HTTP_OK);
               }
       }
           
        return $this->json("promo ou referentiel inexistant");
  }

     #ajouter  fil de discustion et des commentaires
     public function addDiscutionCommentaire($id,ApprenantLivrablePartielRepository $apprenatLivPartiel,Request $request,TokenStorageInterface $tokenStorageInterface,SerializerInterface $serializer)
     {
         $json = json_decode($request->getContent());
         //recupersons le user (apprenat/formateur) connecté
         $user = $tokenStorageInterface->getToken()->getUser();
         $livrablepartiel = $this->livrableRepo->find($id);
         if ($livrablepartiel) {
              //ajoutons le commentaire
              $commentaire = (new Commentaire)
              ->setDescription($json->description)
              ->setCreateAt(new \DateTime());
 
             $apprenantLivrablePartiel = $apprenatLivPartiel->recupereLivrablePartiel($id);
             if ($apprenantLivrablePartiel[0]->getFilDiscution() != NULL) {
                 $commentaire->setFilDiscution($apprenantLivrablePartiel[0]->getFilDiscution());
             }else{
                 $filDiscution = new FilDiscution;
                 $commentaire->setFilDiscution($filDiscution);
                 $apprenantLivrablePartiel[0]->setFilDiscution($filDiscution);
                 $this->em->persist($filDiscution);
             }
             //testons si c un apprenant ou un formateur qui send le message
             if ($user->getProfil()->getLibelle()== "APPRENANT") {
                 $commentaire->setApprenant($user);
             }else {
                 $commentaire->setFormateur($user);
             }
             $this->em->persist($commentaire);
             $this->em->flush();
             return $this->json("success");
         }
     }

     //initialise chaque apprenant avec toutes les competences
     public function initialisation($id)
     {
         $promos = $this->promosRepo->find($id);
         if ($promos) {
            $apprenants = $this->apprenantRepo->findAll();
            $competences = $this->competenceRepository->findAll();
             if ($apprenants && $competences) {
                foreach ($apprenants as $student) {
                    if ($this->apprenantRepo->ifApprenantDansPromo($id,$student->getId())) {
                         foreach ($competences as $competence) {
                             $competencesValides = (new CompetencesValides)
                                 ->setPromos($promos)
                                 ->setReferentiels($promos->getReferentiels())
                                 ->setApprenant($student)
                                 ->setCompetence($competence)
                                 ->setNiveau1(0)
                                 ->setNiveau2(0)
                                 ->setNiveau3(0);
     
                             $this->em->persist($competencesValides);
                         }
                     }
                }
                $this->em->flush();
                return $this->json("intialisation reussi");
             }
         }else{
             return $this->json("promo inexistant");
         }
     }

 
}
