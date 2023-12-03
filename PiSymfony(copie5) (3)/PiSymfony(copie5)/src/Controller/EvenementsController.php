<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenements;
use App\Form\EvenementsType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use DateTimeImmutable;
use App\Repository\EvenementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Email; 
use Symfony\Component\Mailer\MailerInterface; 
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Service\PdfService;
use Symfony\Component\Serializer\SerializerInterface;

class EvenementsController extends AbstractController
{
    private $mailer;

    public function __construct(SerializerInterface $serializer,MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->serializer = $serializer;

    }



    #[Route('/evenements', name: 'app_evenements')]
    public function index(): Response
    {
        return $this->render('evenements/index.html.twig', [
            'controller_name' => 'EvenementsController',
        ]);
    }


    #[Route("/evenements/add", name: 'evenements_add', methods: ['GET', 'POST'])]
    public function addEvenement(Request $request): Response
    {   $evenement = new Evenements();
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingEvent = $this->getDoctrine()->getRepository(Evenements::class)->findOneBy([
                'titre' => $evenement->getTitre(),
            ]);
            if ($existingEvent) {
                $this->addFlash('warning', 'Cet événement existe déjà.');
            } else {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($evenement);
                $entityManager->flush();
                $emailContent = "Nouvel événement EpicJourneys ajouté :\n\n";
                $emailContent .= "Titre : " . $evenement->getTitre() . "\n";
                $emailContent .= "Destination : " . $evenement->getDestination(). "\n";
                $email = (new Email())
                    ->from('ps1913895@gmail.com')
                    ->to('mkanzari001@gmail.com') 
                    ->subject('[EpicJourneys] Nouvel événement ajouté')
                    ->text($emailContent); 
                $this->mailer->send($email);
                return $this->redirectToRoute('evenements_show');
            }
        }
        return $this->render('evenements/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
    
    #[Route('/evenements/show', name: 'evenements_show',)]
    public function show(EvenementsRepository $evenementsRepository): Response
    {
        $evenements = $evenementsRepository->findAll();

        return $this->render('evenements/show.html.twig', [
            'evenements' => $evenements,
        ]);
    }

#[Route('/evenements/edit/{id}', name: 'evenements_edit',methods: ['GET', 'POST'])]
public function edit(Request $request, int $id): Response
{
    $evenement = $this->getDoctrine()->getRepository(Evenements::class)->find($id);

    if (!$evenement) {
        throw $this->createNotFoundException('L\'événement n\'existe pas');
    }
    $evenement->setImage(null); 

    $form = $this->createForm(EvenementsType::class, $evenement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {


        $newImage = $form->get('image')->getData();

        $newImage = $form->get('image')->getData();
        if ($newImage !== null) {
            $newFilename = uniqid().'.'.$newImage->guessExtension();
            $newImage->move(
                $this->getParameter('image_directory'),
                $newFilename
            );
            $evenement->setImage($newFilename);
        }
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('evenements_show');
    }
    return $this->render('evenements/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}




#[Route('/evenements/delete/{id}', name: 'evenements_delete', methods: ['POST'])]
public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
{
    $evenement = $entityManager->getRepository(Evenements::class)->find($id);

    if (!$evenement) {
        throw $this->createNotFoundException('L\'événement n\'existe pas');
    }

    if ($this->isCsrfTokenValid('delete'.$evenement->getIdevenement(), $request->request->get('_token'))) {
        $entityManager->remove($evenement);
        $entityManager->flush();
    }

    return $this->redirectToRoute('evenements_show');
}


#[Route('/evenements/generate-pdf', name: 'evenements_generate_pdf')]
public function generatePDF(EvenementsRepository $evenementsRepository, PdfService $pdfService): Response
{    $evenements = $evenementsRepository->findAll();
    $htmlContent = $this->renderView('evenements/show.html.twig', [
        'evenements' => $evenements,
    ]);
    $pdfContent = $pdfService->generatePdfFromHtml($htmlContent);
    $response = new Response($pdfContent);
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment;filename=evenements.pdf');
    return $response;
}


#[Route('/events23121', name: 'app_events')]
public function events(EvenementsRepository $evenementsRepository): Response
{
    $lowBudgetEvents = $evenementsRepository->findLowBudgetEvents();

    $data = [];
    foreach ($lowBudgetEvents as $event) {
        $data[$event->getTitre()] = $event->getPrix();
    }
    $closestEvent = $evenementsRepository->findClosestEvent();
    return $this->render('espaceclient/events.html.twig', [
        'data' => $data,
        'closestEvent' => $closestEvent,
    ]);
}



#[Route('/evenements/search', name: 'evenements_search_ajax')]
public function searchEvenementsAjax(Request $request, EvenementsRepository $repository, SerializerInterface $serializer)
{
    $criteria = $request->query->get('criteria');
    $searchValue = $request->query->get('searchValue');
    $evenements = $repository->findByCriteria($criteria, $searchValue);
    $data = $serializer->serialize($evenements, 'json', ['groups' => 'evenements']);
    return new JsonResponse($data, 200, [], true);
}



}