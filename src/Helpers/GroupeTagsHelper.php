<?php


namespace App\Helpers;

use App\Entity\Tags;
use App\Repository\TagsRepository;
use App\Repository\GroupeTagsRepository;
use Doctrine\ORM\EntityManagerInterface;

class GroupeTagsHelper
{
    private $grouperepo;
    private $em;
    private $repo;
    public function __construct(GroupeTagsRepository $grouperepo,EntityManagerInterface $em,TagsRepository $repo)
    {
        $this->grouperepo = $grouperepo;
        $this->em = $em;
        $this->repo = $repo;
    }
     //put groupe competence into
     public function putGroupeTag($postman,$id,$request)
     {
         $GroupeTag= $this->grouperepo->find($id);
         
         if($postman->option == "add")
         {
             if ($postman->tags)
             {
                 for ($i=0;$i<count($postman->tags); $i++)
                 {
                     if (isset($postman->tags[$i]->id)){
                         $comp = $this->repo->find($postman->tags[$i]->id);
                         $GroupeTag->addTag($comp);
                     }else{
                         $t = new Tags();
                         if (isset($postman->tags[$i]->libelle)) {
                             $t->setLibelle($postman->tags[$i]->libelle);
                             $t->setDescription($postman->tags[$i]->description);
                             $GroupeTag->addTag($t);
                             $this->em->persist($t);
                         }
                     }
                 }
                 $this->em->flush();
                 return "success";
             }
         }else{
             for ($i=0;$i<count($postman->tags); $i++)
             {
                 if (isset($postman->tags[$i]->id)){
                     $tags = $this->repo->find($postman->tags[$i]->id);
                     $GroupeTag->removeTag($tags);
                     $this->em->flush();
                     return "tag edit success";
                 }
             }
         }
        
     }
}