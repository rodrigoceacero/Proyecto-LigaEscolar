<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    #[Route('/', name:'player_card')]
    public function teamCard() : Response
    {
        return $this->render('person/personcard.html.twig');
    }

    #[Route('/player', name:'players')]
    public function index() :Response
    {
        return $this->render('person/person.html.twig');
    }
}