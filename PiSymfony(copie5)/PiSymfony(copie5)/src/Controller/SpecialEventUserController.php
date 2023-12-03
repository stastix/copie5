<?php

namespace App\Controller;

use App\Entity\Offrespecialevenment;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialEventUserController extends AbstractController
{
    #[Route(path:"/user", name:"")]

    #[Route('/specialevent', name: 'app_specialEvent', methods: ['GET'])]
    public function index(\App\Repository\OffrespecialevenmentRepository $offrespecialevenmentRepository,UserRepository $userRepository): Response
    {     
        $user = $this->getUser();
    
        if (!$user || !$user->getCartefidelite()) {
            return $this->render('special_event_user/index.html.twig',['offrespecialevenments'=>null]);        }
    
        $userNiveauCarte = $user->getCartefidelite()->getNiveaucarte();
    
        $specialEvents = $offrespecialevenmentRepository->findBy(['niveau' => $userNiveauCarte]);
    
        return $this->render('special_event_user/index.html.twig', [
            'offrespecialevenments' => $specialEvents,
        ]);
    
    
    }
    #[Route('/specialevent/{idoffrespecialevenment}', name: 'app_offrespecialevenment_booking', methods: ['GET'])]
    public function booking(Offrespecialevenment $offrespecialevenment): Response
        {
        return $this->render('special_event_user/booking.html.twig', [
            'specialevent' => $offrespecialevenment,
        ]);
    } 

    #[Route('/home/specialevent', name: 'app_specialEvent_home', methods: ['GET'])]
    public function index1(\App\Repository\OffrespecialevenmentRepository $offrespecialevenmentRepository): Response
    {
        return $this->render('special_event_user/index.html.twig', [
        'offrespecialevenments' => $offrespecialevenmentRepository->findAll(),
        ]);
    }


}
