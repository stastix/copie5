<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationsType;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Service\TranslatorService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;




class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }


 #[Route('/reclamation/add', name: 'add_reclamation')]
public function addReclamation(Request $request, ManagerRegistry $managerRegistry, ReclamationRepository $reclamationRepository,UserRepository $userRepository): Response
{$user = $this->getUser(); 
   
    $em = $managerRegistry->getManager();
    $reclamation = new Reclamation();
    
    $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);
        if ($form->isSubmitted()) { 
            
            
            $reclamation->setUseName($user);  

        $em->persist($reclamation);
        $em->flush();
        $this->addFlash('successC', 'complaint added');
        return $this->redirectToRoute('add_reclamation');
    }

    return $this->render('reclamation/index.html.twig',[
    'reclamations' => $reclamationRepository->findAll(),
                'f' => $form->createView()]); 
}


    #[Route('/reclamation/edit/{id}', name: 'reclamation_edit')]
    public function editReclamation(Request $request, ManagerRegistry $manager, ReclamationRepository $reclamationRepository, $id): Response
    {
        $em = $manager->getManager();
        $reclamation  = $reclamationRepository->find($id);
        $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($reclamation);
            $em->flush();
            $this->addFlash('successC', 'complaint edited');

            return $this->redirectToRoute('add_reclamation');
        }
            return $this->render('reclamation/index.html.twig', [
                'reclamations' => $reclamationRepository->findAll(),
                'f' => $form->createView()]);
    
    }


    #[Route('/reclamation/delete/{id}', name: 'reclamation_delete')]
    public function deleteReclamation (Request $request, ManagerRegistry $manager, $id, ReclamationRepository $reclamationRepository ): Response
    {
        $em = $manager->getManager();
        $reclamation = $reclamationRepository->find($id);
        if (!$reclamation) {
            
             return $this->redirectToRoute('add_reclamation');
            
        }
        $em->remove($reclamation);
        $em->flush();
        $this->addFlash('successC', 'complaint deleted');
        
            return $this->redirectToRoute('add_reclamation');
    
    }

    #[Route('/showreclamation', name:'reclamation_show')]
    public function show(ReclamationRepository  $reclamationRepository): Response
    {



        return $this->render('reclamation/show.html.twig', [
            'reclamations' =>$reclamationRepository->showreclamationandUser(),
        ]);}
    

    #[Route('/editReclamation/{id}', name:'reclamation_mark')]
        public function change( ManagerRegistry $manager, $id, ReclamationRepository  $reclamationRepository): Response
        {
            $em = $manager->getManager();
            $reclamation  = $reclamationRepository->find($id);
            $reclamation ->setEtatReclamation("Read");
            $em->persist($reclamation);
            $em->flush();
           
    
            return $this->render('reclamation/show.html.twig',[
                'reclamations' =>$reclamationRepository->showreclamationandUser(),
            ]);}  

     #[Route('/searchCible', name: 'searchCible')]
     public function searchCible(Request $request, NormalizerInterface $normalizer, ReclamationRepository $reclamationRepository)
    {
      $repository=$this->getDoctrine()->getRepository(Reclamation::class);
           
                
      $request1 = $request->get('search');
      if ($request1==5) {
         $request2='Customer Service Department';
         $reclamation = $reclamationRepository->searchByCible($request2);
         $jsonContent = $normalizer->normalize($reclamation, 'json', ['groups' => 'reclamations']);
         $response = json_encode($jsonContent); }

        else if ($request1== 2) {
            $request2= 'General Management or CEO';
            $reclamation = $reclamationRepository->searchByCible($request2);
                
            $jsonContent = $normalizer->normalize($reclamation, 'json', ['groups' => 'reclamations']);
            $response = json_encode($jsonContent);}
        else if ($request1== 3) {
             $request2= 'After-Sales Service Department';
             $reclamation = $reclamationRepository->searchByCible($request2);
               
             $jsonContent = $normalizer->normalize($reclamation, 'json', ['groups' => 'reclamations']);
             $response = json_encode($jsonContent); }
         else if ($request1== 4) {
             $request2= 'Reservation Manager or Department';
             $reclamation = $reclamationRepository->searchByCible($request2);
               
             $jsonContent = $normalizer->normalize($reclamation, 'json', ['groups' => 'reclamations']);
             $response = json_encode($jsonContent); }
                
         else  {
             $reclamation = $reclamationRepository->showreclamationandUser2();
                //$reclamation=new Reclamation;
            $jsonContent = $normalizer->normalize($reclamation, 'json', ['groups' => 'reclamations']);
             $response = json_encode($jsonContent);}
               
              
                
                return new Response($response);
            }

            private $translate;
            
            public function __construct(TranslatorService $translate)
            {
                 $this->translate=$translate;
            }
            
            
            
            
             #[Route('/trans3/{id}', name: 'app_home3')]
             public function tr($id,ReclamationRepository $reclamationRepository,? string $text_to_translate, ? string $text_translated, ? string $source_lang, ? string $target_lang): Response
             {
                            
                 $rec=$reclamationRepository->find($id);
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
                
                 
                 
                 return $this->render('reclamation/traduc.html.twig',
             
                   [
                     'leftLang'=>$leftLang,
                     'rightLang'=>$rightLang,
                     'text_to_translate'=>$text_to_translate,
                     'text_translated'=>$text_translated,
                     'rec'=>$rec,
            
                   ]
             
             );
             }
            
            
            
            
             #[Route('/translate3/{id}', name: 'app_translate3', methods:'POST')]
             public function translate($id,ReclamationRepository $reclamationRepository,Request $request,? string $text_to_translate, ? string $text_translated, ? string $source_lang, ? string $target_lang): Response
             {
                $rec=$reclamationRepository->find($id);
            
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
                 
                  
                  return $this->render('reclamation/traduc.html.twig',
                  [
                     'text_to_translate'=>$text_to_translate,
                     'text_translated'=>$translation,
                     'leftLang'=>$leftLang,
                     'rightLang'=>$rightLang,
                     'rec'=>$rec,
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
             
             #[Route('/SendMail/{id}/', name: 'send_mail')]
             public function sendMail(MailerInterface $mailer, $id, ReclamationRepository $reclamationRepository) {
             
             
                 $reclamation  = $reclamationRepository->find($id);
                
                 $cible = $reclamation->getCibleReclamation();

                 $htmlContent = '<p>Dear customer,</p>';
                 $htmlContent .= '<p>We are here to inform you that your complaint about ' . $cible . ' has been taken care of.</p>';


                 $email = (new Email())
                 ->from('saoudi.maram13@gmail.com')
                 ->to('saoudi.maram13@gmail.com')
 
                 ->subject('Epic Jouneys')
                 ->text('Your complaint has been taken care of')
                 ->html($htmlContent);
     
             $mailer->send($email);
             $this->addFlash('success', 'Email sent successfully!');
             return $this->redirectToRoute('reclamation_show');
             }         


     public function calculateComplaintStatistics($reclamations)
    {
        $statistics = [];
        $totalReclamations = count($reclamations);

        // Count occurrences of each complaint target
        foreach ($reclamations as $reclamation) {
            $target = $reclamation->getCibleReclamation();

            if (!isset($statistics[$target])) {
                $statistics[$target] = 1;
            } else {
                $statistics[$target]++;
            }
        }

        // Calculate percentages
        foreach ($statistics as &$count) {
            $count = ($count / $totalReclamations) * 100;
        }

        return $statistics;
    }

    

    #[Route('/statistic', name: 'app_default2')]
    public function index2(ReclamationRepository  $reclamationRepository): Response
    {
        $reclamationsQuery = $reclamationRepository->showreclamationandUser3();
        $reclamations = $reclamationsQuery->getQuery()->getResult();
    
        $complaintStatistics = $this->calculateComplaintStatistics($reclamations);
    
        return $this->render('reclamation/statistic.html.twig', [
            'reclamations' => $reclamations,
            'complaintStatistics' => $complaintStatistics,
        ]);
    }

 
}