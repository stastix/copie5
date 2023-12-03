<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/default', name: 'app_default')]
    public function index(): Response
    {
        return $this->render('home.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/dashborad', name: 'app_dashborad')]
    public function dashborad(): Response
    {
        return $this->render('base-back.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    
}
