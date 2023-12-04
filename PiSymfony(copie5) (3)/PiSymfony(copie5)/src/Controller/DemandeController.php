<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Form\DemandeType;
use App\Repository\DemandeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Vonage\Client\Credentials\Basic;
use Vonage\Client;
use Vonage\SMS\Message\SMS as VonageSms;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;





class DemandeController extends AbstractController
{
    private const BAD_WORDS = ['fuck', 'shit', 'asshole', 'bitch', 'cunt', 'bastard', 'dickhead', 'motherfucker', 'twat', 'wanker',
    'prick', 'bollocks', 'damn', 'bloody', 'bugger', 'crap', 'ass', 'moron', 'idiot', 'jerk',
    'douchebag', 'wtf', 'piss', 'suck', 'arse', 'knob', 'knobhead', 'nutjob', 'schmuck', 'turd',
    'slut', 'whore', 'dick', 'douchecanoe', 'jackass', 'dweeb', 'numbnuts', 'scumbag', 'dipshit',
    'dumbass', 'nitwit', 'numbskull', 'lameass', 'bozo', 'git', 'pillock', 'tosser', 'muppet', 'numpty', 'plonker',
    'merde', 'putain', 'con', 'salope', 'enculé', 'connard', 'bordel', 'nique ta mère', 'enculer', 'bite',
    'chier', 'couillon', 'trou du cul', 'enculeur', 'enfoiré', 'débile', 'abruti', 'crétin', 'imbécile',
    'branleur', 'salaud', 'taré', 'minable', 'conasse', 'chiure', 'tare', 'niquer', 'pétasse', 'conard',
    'crisse', 'tabarnak', 'ostie', 'câlisse', 'esti', 'coliss', 'maudit', 'dégueulasse', 'trouduc', 'foutre',
    'merdique', 'trou de balle', 'vaurien', 'va te faire foutre', 'encule', 'imbécilité', 'dégueu', 'foutu',
    'sacré', 'foutaise', 'maudite', 'fucking'];

    private function filterBadWords($input, $badWords)
    {
       
        $filtered = preg_replace_callback(
            '/\b(' . implode('|', array_map('preg_quote', $badWords)) . ')\b/i',
            function ($matches) {
                return str_repeat('*', strlen($matches[0]));
            },
            $input
        );
    
        return $filtered;
    }
    
    #[Route('/demande', name: 'add_demande')]
    public function index(Request $request, ManagerRegistry $managerRegistry, DemandeRepository $demandeRepository, UserRepository $userRepository): Response
    {  

        $user = $this->getUser(); 

       
        $em = $managerRegistry->getManager();
        $demande = new Demande();
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
    
            $duration = $form->get('duration')->getData();
    
            if ($duration > 30) {
                $this->addFlash('successC', 'Duration must not exceed 30 days.');
            } else {
                $demande->setUseName($user);
                $filteredDescription = $this->filterBadWords($form->get('description')->getData(), self::BAD_WORDS);
    
                $demande->setDescription($filteredDescription);
                $em->persist($demande);
                $em->flush();
                $this->addFlash('successC', 'request added');
                return $this->redirectToRoute('add_demande');
            }
        }
    
             return $this->render('demande/index.html.twig', [
            'demands' => $demandeRepository->findAll(),
            'f' => $form->createView(),
            'user' =>   $user 
        ]);
    }
    


    #[Route('/demande/edit/{id}', name: 'demande_edit')]
    public function editDemande(Request $request, ManagerRegistry $manager, DemandeRepository $demandeRepository, $id): Response
    {
        $em = $manager->getManager();
        $demande  = $demandeRepository->find($id);
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($demande);
            $em->flush();
            $this->addFlash('successC', 'request edited');

            return $this->redirectToRoute('add_demande');
        }
            return $this->render('demande/index.html.twig', [
                'demands' => $demandeRepository->findAll(),
                'f' => $form->createView()]);
    
    }


    #[Route('/demande/delete/{id}', name: 'demande_delete')]
    public function deleteDemande (Request $request, ManagerRegistry $manager, $id, DemandeRepository $demandeRepository ): Response
    {
        $em = $manager->getManager();
        $demande = $demandeRepository->find($id);
        if (!$demande) {
            
             return $this->redirectToRoute('add_demande');
            
        }
        $em->remove($demande);
        $em->flush();
        $this->addFlash('successC', 'request deleted');
        
            return $this->redirectToRoute('add_demande');
    
    }

    #[Route('/showdemande', name:'demande_show')]
    public function show(DemandeRepository  $demandeRepository): Response
    {

        return $this->render('demande/showd.html.twig', [
            'demands' =>$demandeRepository->showrdemandeUser(),
             
        ]);}  
        
    
    #[Route('/like/demande/{id}', name: 'like.demande', methods: ['GET'])]
    public function like($id, ManagerRegistry $manager, UserRepository $userRepository, DemandeRepository $demandeRepository): Response
    {
        $user = $this->getUser(); 
        $em = $manager->getManager();
        $demande = $demandeRepository->find($id);


        if ($demande->isLikedByUser($user)) {
            $demande->removeLike($user);
            $em->flush();

            return $this->json([
                'message' => 'Le like a été supprimé.',
                'nbLike' => $demande->howManyLikes()
            ]);
        }

        $demande->addLike($user);
        $em->flush();

        return $this->json([
            'message' => 'Le like a été ajouté.',
            'nbLike' => $demande->howManyLikes()
        ]);
    }  


    #[Route('/sendsms/{id}', name: 'send_sms')]
    public function sendSms(LoggerInterface $logger, DemandeRepository $demandeRepository, $id): Response
    {


        $demande = $demandeRepository->find($id);

        if (!$demande) {
            $this->addFlash('error', 'Demand not found.');
            return $this->redirectToRoute('demande_show');
        }
        try {
            $apiKey = 'c43e2861';
            $apiSecret = 'VVS8GyPRZw50KS2V';
    
            $from = '+216 56 170 640';
    
            $to = '+216 56 170 640';
    
            $basic = new Basic($apiKey, $apiSecret);
            $client = new Client($basic);
    
            $message = sprintf('Dear Traveller, we are excited to inform you that the trip to %s for %s days is confirmed.', $demande->getDestination(), $demande->getDuration());

            $sms = new VonageSms($to, 'EpicJourneys', $message);

    
            $response = $client->sms()->send($sms);
            $message = $response->current();
    
            if ($message->getStatus() == 0) {

                $this->addFlash('success', 'SMS sent successfully!');
            } else {
  
                $this->addFlash('error', 'Failed to send SMS. Status: ' . $message->getStatus());
            }
    
            return $this->redirectToRoute('demande_show');
        } catch (\Exception $e) {
            $logger->error('Exception in sendSms: ' . $e->getMessage());
    
     
            $this->addFlash('error', 'An error occurred while processing your request. Please check the logs for more information.');

            return $this->redirectToRoute('demande_show');
        }
    }
    
}
