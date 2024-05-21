<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController{
    
    #[Route('/', name:'main_app')]
    public function main(Security $security): Response
    {
        if ($security->getUser()) {
            return $this->render('main/main.html.twig');
        } else {
            return $this->redirectToRoute('login');
        }
    }
}