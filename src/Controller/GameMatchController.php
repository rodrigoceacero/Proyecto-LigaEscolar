<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameMatchController extends AbstractController
{
    #[Route('/match', name:'matchs')]
    public function index() :Response
    {
        return $this->render('match/match.html.twig');
    }
}