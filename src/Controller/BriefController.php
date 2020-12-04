<?php

namespace App\Controller;

use App\Entity\Tags;
use App\Entity\Brief;
use App\Entity\Ressource;
use App\Entity\BriefDuPromo;
use App\Entity\BriefApprenant;
use App\Entity\EtatBriefGroupe;
use App\Entity\LivrableAttendues;
use App\Repository\TagsRepository;
use App\Repository\BriefRepository;
use App\Repository\NiveauRepository;
use App\Repository\PromosRepository;
use App\Repository\GroupesRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use App\Repository\RessourceRepository;
use App\Entity\LivrableAttenduApprenant;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\LivrableAttenduesApprenant;
use App\Repository\BriefDuPromoRepository;
use App\Repository\BriefApprenantRepository;
use App\Repository\EtatBriefGroupeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\LivrableAttenduesRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BriefController extends AbstractController
{
    private $tagRepo;
    private $groupeRepo;
    private $niveauRepo;
    private $formateurRepo;
    private $em;
    private $briefRepo;
    private $promoRepo;
    private $apprenantRepo;
    private $mailer;
    private $BriefPromoRepo;
    private $ressourceRepo;
    private $LivRepo;

    public function __construct(TagsRepository $tagRepo,GroupesRepository $groupeRepo,NiveauRepository $niveauRepo,FormateurRepository $formateurRepo,EntityManagerInterface $em,BriefRepository $briefRepo,PromosRepository $promoRepo,ApprenantRepository $apprenantRepo,\Swift_Mailer $mailer,BriefDuPromoRepository $BriefPromoRepo,LivrableAttenduesRepository $LivRepo,RessourceRepository $ressourceRepo)
    {
        $this->tagRepo = $tagRepo;
        $this->groupeRepo = $groupeRepo;
        $this->niveauRepo = $niveauRepo;
        $this->formateurRepo = $formateurRepo;
        $this->em = $em;
        $this->briefRepo = $briefRepo;
        $this->promoRepo = $promoRepo;
        $this->apprenantRepo = $apprenantRepo;
        $this->mailer = $mailer;
        $this->BriefPromoRepo = $BriefPromoRepo;
        $this->LivRepo = $LivRepo;
        $this->ressourceRepo = $ressourceRepo;
    }

     //fonction qui gere l'envoie de mail
     public function sendMail($email)
     {
         $mail = (new \Swift_Message('ASSIGNATION BRIEF'))
         ->setFrom('moucfady@gmail.com')
         ->setTo($email)
         ->setBody('un brief vous a eté assigné !!! veuillez vous connecter sur la plateforme pour voir les details du brief merci !!! ');
 
         $this->mailer->send($mail);
     }

    public function createBriefs(Request $request, TokenStorageInterface $tokenStorageInterface)
    {
        $json = json_decode($request->getContent());
        //recuperons le formateur connecté
        $formateur = $tokenStorageInterface->getToken()->getUser();
        $brief = (new Brief)
            ->setLangue($json->langue)
            ->setNomBrief($json->nomBrief)
            ->setDescription($json->description)
            ->setContexte($json->contexte)
            ->setModalitePedagogique($json->modalitePedagogique)
            ->setCritereEvaluation($json->critereEvaluation)
            ->setArchive(0)
            ->setDateCreation(new \DateTime())
            ->setEtatBrief($json->etatBrief);
        //créons et affectons les livrables attendues du brief
        for ($i=0; $i < count($json->livrableAttendus) ; $i++) { 
            $livrableAttendues = (new LivrableAttendues)
            ->setLibelle($json->livrableAttendus[$i]->libelle)
            ->setDescription($json->livrableAttendus[$i]->description);
            $brief->addLivrableAttendue($livrableAttendues);
        }
        //recuperons et affectons les tags  du brief
        for ($i=0; $i < count($json->tags) ; $i++) { 
            $tags = $this->tagRepo->find($json->tags[$i]->id);
            if ($tags) {
                $brief->addTag($tags);
            }
        }
        //recuperons et affectons les niveaux  du brief
        for ($i=0; $i < count($json->niveaux) ; $i++) { 
            $niveaux = $this->niveauRepo->find($json->niveaux[$i]->id);
            if ($niveaux) {
                $brief->addNiveau($niveaux );
            }
        }
        $brief->setFormateurs($formateur);
        $this->em->persist($brief);

        //recuperons et affectons les groupes et assignons le brief
        for ($i=0; $i < count($json->groupes) ; $i++) { 
            $groupes = $this->groupeRepo->find($json->groupes[$i]->id);
            if ($groupes) {
                $tatBriefGroupe = (new EtatBriefGroupe)
                        ->setStatut("encours")
                        //prenons le brief
                        ->setBrief($brief)
                        //assignation du brief au groupe
                        ->setGroupe($groupes);
                    //recuperons le promo ou se trouve le groupe
                    $promo = $groupes->getPromos();
                $this->em->persist($tatBriefGroupe);
            }
        }
        //inserons dans la table qui permet de savoir chaque brief appartient a quel promo
        $briefDuPromo = (new BriefDuPromo)
           ->setPromos($promo)
           ->setStatut("encours")
           ->setBrief($brief);
        $this->em->persist($briefDuPromo);

        for ($i=0; $i < count($json->ressources) ; $i++) { 
            $ressources = (new Ressource)
            ->setLibelle($json->ressources[$i]->libelle)
            ->setType($json->ressources[$i]->type);
            $ressources->setBrief($brief);
            $this->em->persist($ressources);
        }
        $this->em->flush();
        return $this->json("brief added successfully");
    }

    //permet de dupliquer un brief
    public function dupliqueBrief($id,TokenStorageInterface $tokenStorageInterface)
    {
        $briefAdupliquer = $this->briefRepo->find($id);
        //recuperons le formateur connecté
        $formateur = $tokenStorageInterface->getToken()->getUser();

        if ($briefAdupliquer) {
            $brief = (new Brief)
                ->setLangue($briefAdupliquer->getLangue())
                ->setNomBrief($briefAdupliquer->getNomBrief())
                ->setDescription($briefAdupliquer->getDescription())
                ->setContexte($briefAdupliquer->getContexte())
                ->setModalitePedagogique($briefAdupliquer->getModalitePedagogique())
                ->setCritereEvaluation($briefAdupliquer->getCritereEvaluation())
                ->setArchive(0)
                ->setDateCreation($briefAdupliquer->getDateCreation())
                ->setEtatBrief($briefAdupliquer->getEtatBrief())
                ->setFormateurs($formateur)
            ;
            $this->em->persist($brief);
            $this->em->flush();
            return$this->json("dupliquer avec succes"); 
        }else{
            return$this->json("ce brief n existe pas");
        }
    }

    //assignation brief
    public function assignationBrief($idp,$id,Request $request)
    {
        $json = json_decode($request->getContent());
        $promo = $this->promoRepo->find($idp);
        if ($promo) {
            //get brief a affecter
            $brief = $this->briefRepo->find($id);
            if ($brief) {
                if (isset($json->type) && $json->type == "apprenant") {
                    $apprenant = $this->apprenantRepo->find($json->apprenant->id);
                    if ($apprenant) {
                        //regroupons le brief et la promo dans BrifDuPromo
                        $briefDuPromo =( new BriefDuPromo)
                        ->setBrief($brief)
                        ->setStatut("assigne")
                        ->setPromos($promo);
                        
                        $briefApprenant = (new BriefApprenant)
                        ->setStatut("assigne")
                        ->setApprenant($apprenant)
                        ->setBriefDuPromo($briefDuPromo);
                        //envoyons un mail a l'apprenant
                        $this->sendMail($apprenant->getEmail());
                        $this->em->persist($briefDuPromo);
                        $this->em->persist($briefApprenant);
                        $this->em->flush();

                        return $this->json("assignation reussi");
                        
                    }
                }else{

                    if (isset($json->type) && $json->type == "groupe") {
                        //regroupons le brief et la promo dans BrifDuPromo
                        $briefDuPromo = (new BriefDuPromo)
                        ->setBrief($brief)
                        ->setStatut("assigne")
                        ->setPromos($promo);
                        $this->em->persist($briefDuPromo);
                        for ($i=0; $i < count($json->groupes) ; $i++) { 
                            $groupes = $this->groupeRepo->find($json->groupes[$i]->id);
                            //recuperons les apprenants du groupe
                            foreach($groupes->getApprenants() as $student) { 
                                $briefApprenant = (new BriefApprenant)
                                ->setStatut("assigne")
                                ->setApprenant($student)
                                ->setBriefDuPromo($briefDuPromo);
                                $etatBriefGroupe = (new EtatBriefGroupe)
                                ->setStatut("assigne")
                                ->setGroupe($groupes)
                                ->setBrief($brief);
                                //envoyons un mail a l'apprenant
                                $this->sendMail($student->getEmail());
                                $this->em->persist($briefApprenant);
                                $this->em->persist($etatBriefGroupe);
                                $this->em->flush();   
                            }
                        }
                        return $this->json("assigne au groupe");
                    }
                }
            }
        }
    }

    //ajout des urls livrable by apprenant
    public function addUrlLivrable($id,$idg,Request $request)
    {
        $json = json_decode($request->getContent());
        $exist = $this->groupeRepo->ifApprenantInGroupe($id,$idg);
        if ($exist) {
            $groupe = $this->groupeRepo->find($idg);
            //get apprenant du groupe
            $apprenants = $groupe->getApprenants();
            foreach ($apprenants as $student) {
                for($i=0;$i < count($json->livrableAttenduApprenants); $i++) {
                    $url = $json->livrableAttenduApprenants[$i]->url;
                    $livrableAttendues = $this->LivRepo->find($json->livrableAttenduApprenants[$i]->id);
                    $livrableAttenduesApprenant=(new LivrableAttenduesApprenant)
                        ->setUrl($url)
                        ->setLivrableAttendues($livrableAttendues)
                        ->setApprenant($student);
                    $this->em->persist($livrableAttenduesApprenant);
                }
            }
            $this->em->flush();
            return $this->json("livrable rendu");
        }else{
            return $this->json("groupe ou apprenant inexistant");
        }
    }



    //modification d'un brief
    public function EditBrief(Request $request,$idp,$id)
    {
        $json  = json_decode($request->getContent());
        $promo = $this->promoRepo->find($idp);
        $brief = $this->briefRepo->find($id);
        if ($promo and $brief) {
            //archiver le brief
            if (isset($json->type) && $json->type== "archiver") {
                $brief->setArchiver(1);
                $this->em->flush();
                return $this->json("archiver");
            }elseif (isset($json->type) && $json->type == "cloturer") {  
                //cloturer le brief
                foreach ($brief->getBriefDuPromos() as $briefAcloturer) {
                   $briefAcloturer->setStatut("cloturer");
                }
                $this->em->flush();
                return $this->json("cloturer");

                //ajout ou affectations des donnees complementaires au brief
            }else
                {
                    if (isset($json->niveaux)) {
                        //ajoutons niveaux a ce brief
                        foreach ($json->niveaux as $level) {
                            $niveau = $this->niveauRepo->find($level->id);
                            $brief->addNiveau($niveau);
                            $this->em->flush();
                        }
                    }

                    if (isset($json->tags)) {
                        //ajoutons tags
                        foreach ($json->tags as $tag) {
                            $tag = $this->tagRepo->find($tag->id);
                            $brief->addTag($tag);
                            $this->em->flush();
                        }
                    }
                
                    if (isset($json->livrableAttendus)) {
                        //ajoutons livrables attendues
                        foreach ($json->livrableAttendus as $liv) {
                            $livrableAttendus = $this->LivRepo->find($liv->id);
                            $brief->addLivrableAttendu($livrableAttendus);
                            $this->em->flush();
                        }  
                    }
                    if (isset($json->ressources)) {
                        //ajoutons livrables attendues
                        foreach ($json->ressources as $ressource) {
                            $res= $this->ressourceRepo->find($ressource->id);
                            $ressoures = (new Ressource)
                            ->setBrief($brief)
                            ->setLibelle($res->getLibelle())
                            ->setType($res->getType())
                            ;
                            $this->em->persist($ressoures);
                            $this->em->flush();
                        }  
                    }

                    return $this->json('added successfully');
                }
            }else{
                return $this->json('promo ou brief inexistant');
            }
    }

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

    //recupere les briefs d'un grpe d'une promo
    public function getBriefDunGroupeDunePromotion($id,$idp,EtatBriefGroupeRepository $etatBriefGroupe)
    {
        //comme un promo appartient a un seul promo on se base sur le groupe pour trouver la promo qui correspond
        $briefs = $this->groupeRepo->ifGroupeInPromo($idp,$id);
       if ($briefs) {  
           //affichons le brief et tout les groupes encours
           $encours = $etatBriefGroupe->ifStatutEncours($id);
           if ($encours) {
                return $this->json($encours,Response::HTTP_OK,[],['groups'=>"promo_gr_br"]);
            }
       }
       return $this->json("promo ou groupe inexistant");
    }

    //recupere les briefs  d'une promo
    public function getBriefDunePromotion($id,BriefDuPromoRepository $brief)
    {
        $promos = $this->promoRepo->find($id);
       if ($promos) {  
            $briefs = $brief->listBriefDunePromo($id);
            return $this->json($briefs,Response::HTTP_OK,[]);
       }
       return $this->json("promo inexistant");
    }

    // Recuperer les briefs brouilons d un formateur 
    public function getBriefBrouillon($id)
    {
     
       $briefs = $this->briefRepo->ListeBrouillon($id,"brouillon");
        if ($briefs) {
            return $this->json($briefs,Response::HTTP_OK,[],['groups'=>"brouillon"]);
        }
        return $this->json("Ce formateur n a pas de brouillon");
    }

    // Recuperer les briefs valides d un formateur 
    public function getBriefValide($id,BriefRepository $brief)
    {
    
       $briefs = $brief->ListeBrouillon($id,"valide");
        if ($briefs) {
            return $this->json($briefs,Response::HTTP_OK,[],['groups'=>"brouillon"]);
        }
        return $this->json("Ce formateur n a pas de brief valide");
    }

    //recupere un brief d'une promo
    public function getOneBriefPromo($idp,$id,BriefDuPromoRepository $brief)
    {
    
        $briefs = $brief->ifBriefInPromo($idp,$id);
        if ($briefs) {  
                return $this->json($briefs,Response::HTTP_OK,[],['groups'=>"promo_one_br"]);
        }
        return $this->json("promo ou brief inexistant");
    }

    //getBriefsApprenantsDunePromotion
    public function getBriefsApprenantsDunePromotion($id,BriefApprenantRepository $brief)
        {   
            $promos = $this->promoRepo->find($id);
           if ($promos) {  
            $briefs = $brief->listBriefAppDunePromo($id,"encours");
                return $this->json($briefs,Response::HTTP_OK,[],["groups"=>"br_app_ass"]);
           }
           return $this->json("promo inexistant ou brief non assigne");
        }
}