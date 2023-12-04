<?php

namespace App\Controller;
use App\Entity\Eventssaif;
use App\Entity\Reservation;
use App\Form\EventssaifType;
use App\Repository\CommentsRepository;
use App\Repository\EventssaifRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticController extends AbstractController
{
    #[Route('/static', name: 'app_static')]
    public function index(ReservationRepository $ReservationRepository,CommentsRepository $commentsRepository): Response
    {
        return $this->render('admin/static/static.html.twig', [
            'reservations' => $ReservationRepository->findAll(),
            'commentaire' => $commentsRepository->findAll(),
            ]);
    }
    #[Route('scomments/{id}', name: 'scomments')]
    public function scomments( $id, EventssaifRepository $event ,ReservationRepository $ReservationRepository,CommentsRepository $commentsRepository): Response
    {
       
        

        return $this->render('admin/static/comments.html.twig', [
            
            'commentaire' => $commentsRepository->findByidevent($id),
            ]);
    }
    #[Route('/searchRR', name: 'searchR')]
    public function searchR(Request $request, NormalizerInterface $normalizer, ReservationRepository $reservationRepository)
    { $repository=$this->getDoctrine()->getRepository(Reservation::class);
        //$request1 = $request->get('search');
        //$request2 = $request->get('search2');
        
        $request1 = $request->get('search');
       
        $reservation = $reservationRepository->findStudentByEventIdAndPrixR3($request1);
        $jsonContent = $normalizer->normalize($reservation, 'json', ['groups' => 'reservations']);
        $response = json_encode($jsonContent);
        
        return new Response($response);
    }
    #[Route('/searchRR2', name: 'searchR2')]
    public function searchR2(Request $request, NormalizerInterface $normalizer, ReservationRepository $reservationRepository)
    { $repository=$this->getDoctrine()->getRepository(Reservation::class);
        //$request1 = $request->get('search');
        //$request2 = $request->get('search2');
        
        $request1 = $request->get('search2');
       
        $reservation = $reservationRepository->findStudentByEventIdAndPrixR4($request1);
        $jsonContent = $normalizer->normalize($reservation, 'json', ['groups' => 'reservations']);
        $response = json_encode($jsonContent);
        
        return new Response($response);
    }

/*
    #[Route('/searchRR', name: 'searchR')]
    public function searchR(Request $request, NormalizerInterface $normalizer, ReservationRepository $reservationRepository)
    { $repository=$this->getDoctrine()->getRepository(Reservation::class);
        //$request1 = $request->get('search');
        //$request2 = $request->get('search2');
        
        $request1 = $request->get('search');
        $request2 = $request->get('minPrice');
        $reservation = $reservationRepository->findStudentByEventIdAndPrixR2($request1,$request2);
        $jsonContent = $normalizer->normalize($reservation, 'json', ['groups' => 'reservations']);
        $response = json_encode($jsonContent);
        
        return new Response($response);
    }

    */






    #[Route('/events', name: 'addevent')]
    public function addeventss(Request $request, ManagerRegistry $manager, EventssaifRepository $event): Response
    {
        $em = $manager->getManager();
        $event=new Eventssaif();
        $form = $this->createForm(EventssaifType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
        
          
            $em->persist($event);
            $em->flush();
            $this->addFlash('successC', 'event ajouter');
            return $this->redirectToRoute('addevent');
        }
    
        return $this->render('admin/events/event.html.twig',[
            
            'form' => $form->createView()
         
        ]);
    }


   


    
}