<?php

namespace App\Controller;
use App\Entity\Ratingsaif;
use App\Repository\EventssaifRepository;
use App\Repository\RatingsaifRepository;
use App\Service\TranslatorService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Comments;
use App\Entity\Reservation;
use App\Form\CommentsType;
use App\Repository\UserRepository;
use App\Repository\CommentsRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReservationsController extends AbstractController
{


   
    #[Route('/reservations', name: 'app_reservations')]
    public function index(): Response
    {
        return $this->render('reservations/index.html.twig', [
            'controller_name' => 'ReservationsController',
        ]);
    }
    #[Route('/reservations/liste/{id}', name: 'retourToBook')]
    public function retour($id): Response
    {
        return $this->redirectToRoute('sbooking', ['id'=> $id]);
    }
    
    #[Route('/reservations/list', name: 'slist_evennement')]
    public function slist(\App\Repository\OffrespecialevenmentRepository $offrespecialevenmentRepository,UserRepository $userRepository,EventssaifRepository $eventRepository): Response
    {
        $user = $this->getUser();
    
        if (!$user || !$user->getCartefidelite()) {
            return $this->render('reservations/slist.html.twig',['offrespecialevenments'=>null,
            'events' => $eventRepository->findAll(),
            'eventsD' => $eventRepository->findAllDistinctdestination(),
            'baw'=> $eventRepository->findAllDistinctTypes(),
           
            
        
        ]);        }
    
        $userNiveauCarte = $user->getCartefidelite()->getNiveaucarte();
    
        $specialEvents = $offrespecialevenmentRepository->findBy(['niveau' => $userNiveauCarte]);
    
       
        return $this->render('reservations/slist.html.twig', [
            'events' => $eventRepository->findAll(),
            'eventsD' => $eventRepository->findAllDistinctdestination(),
            'baw'=> $eventRepository->findAllDistinctTypes(),
            'offrespecialevenments' => $specialEvents,
        ]);
    }

    #[Route('/reservations/listchoix', name: 'slist_evennement_choix')]
    public function slistchoix(Request $request,EventssaifRepository $eventRepository): Response
    {
        $dest = $request->get('dest');
        $dest2 = $request->get('type');
        return $this->render('reservations/slist.html.twig', [
            'events' => $eventRepository->findBychoix($dest,$dest2)
            ,
            'eventsD' => $eventRepository->findAllDistinctdestination(),
            'baw'=> $eventRepository->findAllDistinctTypes(),
         
        ]);
    }
    #[Route('/reservations/listchoix2', name: 'slist_evennement_choix2')]
    public function slistchoix2(Request $request, EventssaifRepository $eventRepository): Response
    {
        $dest = $request->get('dest');
        $dest2 = $request->get('type');
    
        $events = $eventRepository->findBychoix($dest, $dest2);
    
        $response2 = new JsonResponse([
            'events' => $events,
        ]);
    
        return $response2;
    }
 
    
    #[Route('/reservations/list/booking/{id}', name: 'sbooking')]
    public function addcomment(Request $request, $id, ManagerRegistry $manager, EventssaifRepository $event, CommentsRepository $commentsRepository): Response
    {
        $events = $event->find($id);
        $image = $events->getImagesaif();
        $prix=$events->getPrixsaif();
        $em = $manager->getManager();
        $user = $this->getUser();
        $Comments = new Comments();
    
        $form = $this->createForm(CommentsType::class, $Comments);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $Comments->setIdEvent($id);
            $Comments->setEventssaif($events);
            $Comments->setUserName($user->getUsername());
          
            $em->persist($Comments);
            $em->flush();
            $this->addFlash('successC', 'Comment ajouté');
            return $this->redirectToRoute('sbooking', ['id' => $id]);
        }
    
        return $this->render('reservations/sbooking.html.twig', [
            'comments' => $commentsRepository->findByidevent($id),
            'f' => $form->createView(),
            'id' => $id,
            'im' => $image,
            'pr' => $prix
        ]);
    }


    #[Route('/reservations/edit/{id}', name: 'comment_edit')]
    public function editBook(Request $request, ManagerRegistry $manager, $id, CommentsRepository $commentsRepository): Response
    {
        $user = $this->getUser();
        $usrid = $user->getId();

        $em = $manager->getManager();

        $comment  = $commentsRepository->find($id);
        $f2=$comment->getIdEvent();
        $imm=$comment->getEventssaif();
        $im=$imm->getImagesaif();
        $prix=$imm->getPrixsaif();
        if ($comment->getUserName() == $user->getUsername()) {
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            
            $em->persist($comment);
            $em->flush();
            $this->addFlash('successC', 'Comment modifier');
            return $this->redirectToRoute('sbooking', ['id'=> $f2]);
        
        } }
            else {
                $em->flush();
                $this->addFlash('successC', 'hes not your comment');
                return $this->redirectToRoute('sbooking', ['id'=> $f2]);
            }
        

        return $this->render('reservations/sbooking.html.twig', [
            'comments' => $commentsRepository->findByidevent($f2),
            'f' => $form->createView()
            ,'id'=>$f2
            ,'im'=>$im
            , 'pr' => $prix
            ]);
    }

    #[Route('/reservations/delete/{id}', name: 'comment_delete')]
    public function deleteBook(Request $request, ManagerRegistry $manager, $id, CommentsRepository $commentsRepository): Response
    {$user = $this->getUser();
        $em = $manager->getManager();
        
        $Comment1 = $commentsRepository->find($id);
        $imm=$Comment1->getEventssaif();
        $im=$imm->getImagesaif();
        $f=$Comment1->getIdEvent();
        if ($Comment1->getUserName() == $user->getUsername()) {
        $em->remove($Comment1);
        $em->flush();
        $this->addFlash('successC', 'Comment suprimer');
                return $this->redirectToRoute('sbooking', ['id'=> $f]);}
                else {
                    $em->flush();
                    $this->addFlash('successC', 'hes not your comment');
                    return $this->redirectToRoute('sbooking', ['id'=> $f]);
                }   
     
    }
    #[Route('/reservations/add/{id}', name: 'add_reservation')]
    public function addreservation(Request $request,$id, ManagerRegistry $manager,EventssaifRepository $eventssaifRepository  , CommentsRepository $commentsRepository): Response
    {


        
        $fadit=$eventssaifRepository->find($id);
        $basePrice=$fadit->getPrixsaif();
         $em1 = $manager->getManager();
        $reservation = new Reservation();
        $adults = $request->get('nbAdults');
        $kids = $request->get('nbKids');
       
        
        $reservation->setNbAdults($adults);
        $reservation->setNbkids($kids);
        $reservation->setEventId($id);


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nbAdults = isset($_POST['nbAdults']) ? (int)$_POST['nbAdults'] : 0;
            $nbKids = isset($_POST['nbKids']) ? (int)$_POST['nbKids'] : 0;
           
            $discountForKids = 0.3 * $basePrice * $nbKids;  
            $totalSeats = $nbAdults + $nbKids;
            $additionalSeatsDiscount = 0;
        
            if ($totalSeats > 2) {
                for ($i = 3; $i <= $totalSeats; $i++) {
                    $additionalSeatsDiscount += 0.05 * $basePrice;
                }
            }
        
            $newPrice = $basePrice * $totalSeats - $discountForKids - $additionalSeatsDiscount;
             $reservation->setPrixR($newPrice);
        }
        else {
        $reservation->setPrixR($basePrice);
        }
        $reservation->setUserId(2);
      
        $em1->persist($reservation);
        $em1->flush();
        $this->addFlash('successR', 'Réservation effectuée avec succès!');
        return $this->render('operation/cart.html.twig',['fadit'=> $reservation]);
        
 
}







#[Route('/reservations/update_rating/{id}', name: 'update_event_rating')]
public function updateEventRating(Request $request, ManagerRegistry $manager,EventssaifRepository $eventssaifRepository, $id,RatingsaifRepository $r ): JsonResponse
{ $user = $this->getUser();
    $usrid = $user->getId();
    $test=$r->findRating($usrid,$id);
    if ($test === null) {
    $em = $manager->getManager();
    $event = $eventssaifRepository->find($id);

    $ur = $request->get('userRating');
    $RS= new Ratingsaif();
    $RS->setIdUser($usrid);
    $RS->setValueRaiting($ur);
    $RS->setEventR( $event);
    $em->persist($RS);
    $em->flush();
    $old=$event->getRating();
    $nbRaiting=$event->getEntityCountOfRaiting();
    $new=(($old * $nbRaiting)+$ur)/($nbRaiting+1);
   $event->setRating($new);
   $em->persist($event);
    $em->flush();
    return new JsonResponse(['message' => 'Rating updated successfully']);}
    return new JsonResponse(['message'=> 'tu est deja donner ton raiting']);
}
private $translate;


public function __construct(TranslatorService $translate)
{
     $this->translate=$translate;
}




 #[Route('/t/{id}', name: 'app_home')]
 public function tr($id,CommentsRepository $commentsRepository,? string $text_to_translate, ? string $text_translated, ? string $source_lang, ? string $target_lang): Response
 {

     $comment=$commentsRepository->find($id);
     $tab_lang=json_decode(file_get_contents($this->getParameter('lang_code')));

     if($source_lang && $target_lang)
     {
        foreach($tab_lang->lang as $tb)
        {
           if($tb->name == $source_lang)
           {
               $leftLang=array_unique(array_merge(array('0'=>$tb),$tab_lang->lang),SORT_REGULAR);
           }

           if($tb->name == $target_lang)
           {
               $rightLang=array_unique(array_merge(array('0'=>$tb),$tab_lang->lang),SORT_REGULAR);
           }
        }
     }
     
     else
     {
         $leftLang=$tab_lang->lang;

         $rightLang=array_reverse($tab_lang->lang,true);
     }
    
     
     
     return $this->render('reservations/index.html.twig',
 
       [
         'leftLang'=>$leftLang,
         'rightLang'=>$rightLang,
         'text_to_translate'=>$text_to_translate,
         'text_translated'=>$text_translated,
         'comment'=>$comment,

       ]
 
 );
 }




 #[Route('/translate/{id}', name: 'app_translate', methods:'POST')]
 public function translate($id,CommentsRepository $commentsRepository,Request $request,? string $text_to_translate, ? string $text_translated, ? string $source_lang, ? string $target_lang): Response
 {
    $comment=$commentsRepository->find($id);

      $leftLang2=$request->request->get('left-lang');

      $rightLang2=$request->request->get('right-lang');

      $text_to_translate=$request->request->get('textsend');



      $translation=$this->translate->getTranslate($leftLang2,$rightLang2,$text_to_translate);
      $tab_lang=json_decode(file_get_contents($this->getParameter('lang_code')));

      if($source_lang && $target_lang)
      {
         foreach($tab_lang->lang as $tb)
         {
            if($tb->name == $source_lang)
            {
                $leftLang=array_unique(array_merge(array('0'=>$tb),$tab_lang->lang),SORT_REGULAR);
            }
 
            if($tb->name == $target_lang)
            {
                $rightLang=array_unique(array_merge(array('0'=>$tb),$tab_lang->lang),SORT_REGULAR);
            }
         }
      }
      
      else
      {
          $leftLang=$tab_lang->lang;
 
          $rightLang=array_reverse($tab_lang->lang,true);
      }
     
      
      return $this->render('reservations/index.html.twig',
      [
         'text_to_translate'=>$text_to_translate,
         'text_translated'=>$translation,
         'leftLang'=>$leftLang,
         'rightLang'=>$rightLang,
         'comment'=>$comment,
      ]
      
      );


 }



 #[Route('/switch', name: 'app_switch')]
 public function switchLang(Request $request): Response
 {
      $data=json_decode($request->getContent(),true);

      $leftLang=$data['leftLang'];

      $rightLang=$data['rightLang'];

      $text_to_translate=$data['text_to_translate'];

      $translation=$this->translate->getTranslate($leftLang,$rightLang,$text_to_translate);

      $table=
      [
         'text_translated'=>$translation
      ];

      return $this->json($table);


 }

}
