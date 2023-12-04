<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiqueController extends AbstractController
{
    #[Route('/statistique', name: 'app_statistique')]
    public function index(): Response
    {
        return $this->render('statistique/index.html.twig', [
            'controller_name' => 'StatistiqueController',
        ]);
    }


    
    #[Route('/statistique', name: 'app_statistique')]
    public function statistique(UserRepository $userRepository): Response
    {
        $totalUtilisateurs = $userRepository->count([]);
        $hommes = $userRepository->countByGenre('Homme');
        $femmes = $userRepository->countByGenre('Femme');

        $pourcentageHommes = ($hommes / $totalUtilisateurs) * 100;
        $pourcentageFemmes = ($femmes / $totalUtilisateurs) * 100;

        return $this->render('statistique/sexe.html.twig', [
            'totalUtilisateurs' => $totalUtilisateurs,
            'hommes' => $hommes,
            'femmes' => $femmes,
            'pourcentageHommes' => $pourcentageHommes,
            'pourcentageFemmes' => $pourcentageFemmes,
        ]);
    }
}
