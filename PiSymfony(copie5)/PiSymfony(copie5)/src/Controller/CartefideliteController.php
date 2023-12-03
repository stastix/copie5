<?php

namespace App\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Cartefidelite;
use App\Form\CartefideliteType;
use App\Repository\CartefideliteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCodeBundle\Response\QrCodeResponse;

#[Route('/cartefidelite')]

class CartefideliteController extends AbstractController
{ 

#[Route('/', name: 'app_cartefidelite_index', methods: ['GET'])] 

public function index(CartefideliteRepository $cartefideliteRepository): Response
{$cartes = $cartefideliteRepository->findAll();  

    return $this->render('cartefidelite/index.html.twig', [
        'cartefidelites' => $cartes,
        'message' => 'null'
    ]);
}
    #[Route('/new', name: 'app_cartefidelite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cartefidelite = new Cartefidelite(); 
        $cartefidelite->setDatedebut(new \DateTime());  
        $futureDate=date('Y-m-d', strtotime('+1 year')); 
        $cartefidelite->setDatefin(new \DateTime($futureDate));

        $form = $this->createForm(CartefideliteType::class, $cartefidelite);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $em = $this->getDoctrine()->getManager(); 
            $em->persist($cartefidelite);
            $em->flush();

            return $this->redirectToRoute('app_cartefidelite_index', ['message' => 'La carte fedelite créée avec succès'], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cartefidelite/new.html.twig', [
            'cartefidelite' => $cartefidelite,
            'form' => $form,
        ]);
    }

    #[Route('/{idcarte}', name: 'app_cartefidelite_show', methods: ['GET'])]
    public function show(Cartefidelite $cartefidelite = null): Response
    {
        if (!$cartefidelite) {
            return $this->redirectToRoute('app_cartefidelite_index', ['message' => "La carte fedelite n'existe pas"], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('cartefidelite/show.html.twig', [
            'cartefidelite' => $cartefidelite,
        ]);
    }
    
        
    #[Route('/edit/{idcarte}', name: 'app_cartefidelite_edit')]
    public function edit(Request $request, Cartefidelite $cartefidelite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CartefideliteType::class, $cartefidelite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cartefidelite_index', ['message' => 'La carte fedelite mise à jour avec succès'], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cartefidelite/edit.html.twig', [
            'cartefidelite' => $cartefidelite,
            'form' => $form,
        ]);
    }

    #[Route('/{idcarte}', name: 'app_cartefidelite_delete', methods: ['POST'])]
    public function delete(Request $request, Cartefidelite $cartefidelite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cartefidelite->getIdcarte(), $request->request->get('_token'))) {
            $entityManager->remove($cartefidelite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cartefidelite_index', ['message' => 'La carte fedelite supprimer'], Response::HTTP_SEE_OTHER);
    }
    #[Route('/generate-qr-code/{id}', name: 'generate_qr_codee')]
    public function generateQRCode($id,BuilderInterface $customQrCodeBuilder)
    {
    $result = $customQrCodeBuilder
    ->size(400)
    ->margin(20)
    ->data("http://192.168.1.17:8000/cartefidelite/".$id)
    ->build();
    $response = new QrCodeResponse($result);
    return $response;
    }
    
}
