<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupesController extends AbstractController
{
   
    public function retireApprenantGroupe($id,$idA)
    {
        dd("remove apprenant in groupe");
    }
}
