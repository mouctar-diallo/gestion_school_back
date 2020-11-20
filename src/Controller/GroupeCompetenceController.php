<?php

namespace App\Controller;

use App\Helpers\GroupeCompetenceHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeCompetenceController extends AbstractController
{
    
    public function create(Request $request, GroupeCompetenceHelper $helper): Response
    {
        $groupeCompetence = json_decode($request->getContent(), true);
        $helper->addGroupeCompetence($groupeCompetence);
        return $this->json('groupe added successfully');
    }
}
