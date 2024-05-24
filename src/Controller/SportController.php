<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SportController extends AbstractController
{
    #[Route('/', name:'sport_card')]
    public function teamCard() : Response
    {
        return $this->render('sport/sportcard.html.twig');
    }

    #[Route('/sport', name:'sports')]
    public function index() :Response
    {
        return $this->render('sport/sport.html.twig');
    }
}