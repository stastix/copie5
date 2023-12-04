<?php

namespace App\Controller;

use App\Entity\Offrespecialevenment;
use App\Entity\OffrespecialevenmentRepository;
use App\Form\OffrespecialevenmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route; 

#[Route('/offrespecialevenment')]
class OffrespecialevenmentController extends AbstractController
{

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    #[Route('/', name: 'app_offrespecialevenment_index', methods: ['GET'])]
    public function index(\App\Repository\OffrespecialevenmentRepository $offrespecialevenmentRepository): Response
    {
        return $this->render('offrespecialevenment/index.html.twig', [
            'offrespecialevenments' => $offrespecialevenmentRepository->findAll(),
            'message'=> 'null'
        ]);
    }

    #[Route('/new', name: 'app_offrespecialevenment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offrespecialevenment = new Offrespecialevenment(); 
        $offrespecialevenment->setDateDepart(new \DateTime());
        
        $form = $this->createForm(OffrespecialevenmentType::class, $offrespecialevenment);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) { 
            $uploadedFile = $form->get('image')->getData(); 
    
            if ($uploadedFile) {
                $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
    
                try {
                    $destination = $this->getParameter('kernel.project_dir').'/public/img';
                    $uploadedFile->move($destination, $newFilename);
                    $offrespecialevenment->setImage($newFilename);
                } catch (FileException $e) {
                    // Handle file upload error, you can log or add a flash message here
                    $this->addFlash('error', 'Error uploading the image.');
                }                   
            }
            $this->addFlash('success', 'Offrespecialevenment created successfully.');

            $entityManager->persist($offrespecialevenment);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_offrespecialevenment_index', ['message' => 'Offre ajouter avec succès'], Response::HTTP_SEE_OTHER);
        
        }
    
        return $this->renderForm('offrespecialevenment/new.html.twig', [
            'offrespecialevenment' => $offrespecialevenment,
            'form' => $form,
        ]);
    }
    
    
    #[Route('/{idoffrespecialevenment}', name: 'app_offrespecialevenment_show', methods: ['GET'])]
    public function show(Offrespecialevenment $offrespecialevenment = null): Response
    {
        if (!$offrespecialevenment) {
            return $this->redirectToRoute('app_offrespecialevenment_index', ['message' => "La offre spéciale événement n'existe pas"], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('offrespecialevenment/show.html.twig', [
            'offrespecialevenment' => $offrespecialevenment,
        ]);
    }
    

    #[Route('/{idoffrespecialevenment}/edit', name: 'app_offrespecialevenment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offrespecialevenment $offrespecialevenment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffrespecialevenmentType::class, $offrespecialevenment, [
            'method' => 'POST', // Add this line to specify the method
        ]);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('image')->getData();
    
            if ($uploadedFile) {
                // Debugging
                dump($uploadedFile);
    
                $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
    
                try {
                    $destination = $this->getParameter('kernel.project_dir').'/public/img';
                    $uploadedFile->move($destination, $newFilename);
                    $offrespecialevenment->setImage($newFilename);
                } catch (FileException $e) {
                    // Handle file upload error, you can log or add a flash message here
                    $this->addFlash('error', 'Error uploading the image.');
                }
            }
    
            // Move this line outside the if ($uploadedFile) block
            $entityManager->flush();
    
            return $this->redirectToRoute('app_offrespecialevenment_index', ['message' => 'Offre mise à jour avec succès'], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('offrespecialevenment/edit.html.twig', [
            'offrespecialevenment' => $offrespecialevenment,
            'form' => $form,
        ]);
    }
            
    #[Route('/{idoffrespecialevenment}', name: 'app_offrespecialevenment_delete', methods: ['POST'])]
    public function delete(Request $request, Offrespecialevenment $offrespecialevenment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offrespecialevenment->getIdoffrespecialevenment(), $request->request->get('_token'))) {
            $entityManager->remove($offrespecialevenment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offrespecialevenment_index', ['message' => 'Offre supprimer'], Response::HTTP_SEE_OTHER);
    } 


}
