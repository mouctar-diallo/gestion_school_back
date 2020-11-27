<?php

namespace App\Controller;

use App\Entity\Tags;
use App\Entity\GroupeTags;
use App\Helpers\GroupeTagsHelper;
use App\Repository\TagsRepository;
use App\Repository\GroupeTagsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeTagsController extends AbstractController
{
    private $validator;
    private $em;
    private $groupe;
    private $tagRepo;
    public function __construct(ValidatorInterface $validator, EntityManagerInterface $em,GroupeTagsRepository $groupe,TagsRepository $tagRepo)
    {
        $this->validator = $validator;
        $this->em = $em;
        $this->groupe = $groupe;
        $this->tagRepo = $tagRepo;
    }

    public function createGroupeTags(Request $request)
    {
        $json = json_decode($request->getContent());
        //verifions s'il faut crée le groupe oubien l'affecté des tags
        if (isset($json->id)) {
            $groupeTags = $this->groupe->find($json->id);
        }else{
            $groupeTags = null;
        }
        if ($groupeTags != null) {
            //dans le cas ou groupe tags existe deja
            for ($i=0; $i < count($json->tags); $i++) { 
                if (isset($json->tags[$i]->id)) {
                    //affectation la/les competences au groupe
                    $tags = $this->tagRepo->find($json->tags[$i]->id);
                    $groupeTags->addTag($tags);
                }else{
                    $tags = new Tags;
                    $tags->setLibelle($json->tags[$i]->libelle)
                            ->setDescription($json->tags[$i]->description);
                    $groupeTags->addTag($tags);
                }
            }

            $this->em->flush();
            return $this->json('added succesfully',Response::HTTP_OK);
        }else{ //si groupe de competence n'existe on crée
            $groupeTags = new GroupeTags;
                $groupeTags->setLibelle($json->libelle);
            for ($i=0; $i < count($json->tags); $i++) { 
                if (isset($json->tags[$i]->id)) {
                    //affectation de la tags
                    $tags = $this->tagRepo->find($json->tags[$i]->id);
                    $groupeTags->addTag($tags);
                }else{
                    //creation tags
                    $tags = new Tags;
                    $tags->setLibelle($json->tags[$i]->libelle)
                            ->setDescription($json->tags[$i]->description);
                    $groupeTags->addTag($tags);
                }
            }
           
            $this->em->persist($groupeTags);
            $this->em->flush();
            return $this->json('added succesfully',Response::HTTP_OK);
        }
    }

       //put groupe competence d'un groupe
       public function addOrRemoveTags($id,GroupeTagsHelper $helper,Request $request)
       {
           $postman = json_decode($request->getContent());
           $helper->putGroupeTag($postman,$id,$request);
   
           return $this->json("success");
       }
}
