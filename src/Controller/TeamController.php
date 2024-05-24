<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    #[Route('/', name:'team_card')]
    public function teamCard() : Response
    {
        return $this->render('team/teamcard.html.twig');
    }

    #[Route('/team', name:'teams')]
    public function index() :Response
    {
        return $this->render('team/team.html.twig');
    }

}