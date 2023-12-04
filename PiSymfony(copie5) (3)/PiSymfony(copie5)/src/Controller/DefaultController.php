<?php

namespace App\Controller;
use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservationRepository;
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
    public function dashborad(ReservationRepository $ReservationRepository,CommentsRepository $commentsRepository): Response
    {
        return $this->render('admin/static/static.html.twig', [
            'reservations' => $ReservationRepository->findAll(),
            'commentaire' => $commentsRepository->findAll(),
            ]);
    } 

    #[Route('/about', name: 'app_about')] 
    public function about():Response 
    { 
        return $this->render('about.html.twig');
    }
    
}
