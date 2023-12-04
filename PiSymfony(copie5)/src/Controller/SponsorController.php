<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Sponsor;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\SponsorRepository;



use App\Form\SponsorType;

class SponsorController extends AbstractController
{
    #[Route('/sponsor', name: 'app_sponsor')]
    public function index(): Response
    {
        return $this->render('sponsor/index.html.twig', [
            'controller_name' => 'SponsorController',
        ]);
    }

    #[Route("/sponsor/add", name: 'sponsor_add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $sponsor = new Sponsor();
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingSponsor = $this->getDoctrine()->getRepository(Sponsor::class)->findOneBy([
                'nomsponsor' => $sponsor->getNomsponsor(),
                // Ajoutez ici d'autres critères pour vérifier l'unicité du sponsor
            ]);

            if ($existingSponsor) {
                $this->addFlash('warning', 'Ce sponsor existe déjà.');
            } else {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($sponsor);
                $entityManager->flush();
                return $this->redirectToRoute('sponsor_show');
            }
        
        }

        return $this->render('sponsor/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

 
    #[Route('/sponsor/show', name: 'sponsor_show',)]
    public function show(SponsorRepository $sponsorRepository): Response
    {
        $sponsors = $sponsorRepository->findAll();

        return $this->render('sponsor/show.html.twig', [
            'sponsors' => $sponsors,
        ]);
    }




    #[Route('/sponsor/edit/{id}', name: 'sponsor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $sponsor = $entityManager->getRepository(Sponsor::class)->find($id);

        if (!$sponsor) {
            throw $this->createNotFoundException('Sponsor non trouvé');
        }

        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si le formulaire est soumis et valide, enregistrez les modifications
            $entityManager->flush();

            return $this->redirectToRoute('sponsor_show'); // Redirigez où vous voulez après l'édition
        }

        return $this->render('sponsor/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/sponsor/delete/{id}', name: 'sponsor_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $sponsor = $entityManager->getRepository(Sponsor::class)->find($id);

        if (!$sponsor) {
            throw $this->createNotFoundException('Sponsor non trouvé');
        }

        if ($this->isCsrfTokenValid('delete'.$sponsor->getIdsponsor(), $request->request->get('_token'))) {
            $entityManager->remove($sponsor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sponsor_show'); // Redirection vers une autre page après suppression
    }



    
}





















