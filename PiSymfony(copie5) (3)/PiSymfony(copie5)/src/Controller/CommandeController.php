<?php

namespace App\Controller;


use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;



class CommandeController extends AbstractController
{

    private $entityManager;
    private $commandeRepository;

    public function __construct(EntityManagerInterface $entityManager, CommandeRepository $commandeRepository)
    {
        $this->entityManager = $entityManager;
        $this->commandeRepository = $commandeRepository;
    }




    #[Route('/confirm/{id}', name: 'app_commande_confirm', methods: ['GET'])]
public function confirmCommande(Commande $commande, MailerInterface $mailer): Response
{
    // Get the product ID of the selected Commande
    $productId = $commande->getProductId();

    // Fetch all Commande entities with the same product ID
    $commandesWithSameProduct = $this->commandeRepository->findBy(['productId' => $productId]);

    // Loop through each Commande and set Comfirmed to true
    foreach ($commandesWithSameProduct as $cmd) {
        $cmd->setComfirmed(true);
    }

    // Send confirmation email
    $this->sendConfirmationEmail($commande, $mailer);

    // Persist changes to the database
    $this->getDoctrine()->getManager()->flush();

    return $this->redirectToRoute('app_commande_index'); // Replace 'your_redirect_route' with the actual route you want to redirect to
}

    #[Route('/pp', name: 'app_commande_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        $usrid = $user->getId();
        $commandeDetails = $this->commandeRepository->getCommandeDetailsForProductId1($usrid);
//////////////////// show par ussrconect (filtrage)////////////////////
        return $this->render('commande/index.html.twig', [
            'commandeDetails' => $commandeDetails,
        ]);
    }


    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('hhh/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('hohoh/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('jlkj/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
    

    private function sendConfirmationEmail(Commande $commande, MailerInterface $mailer)
    {


        if ($commande->getProductId() === $commande->getProduit()->getId()) {
            // Get personalized data
            $productName = $commande->getProduit()->getNom();
            $productPrice = $commande->getProduit()->getPrixUnitaire();

            // Prepare and send email
            $email = (new Email())
            ->from('amiraayari.tn@gmail.com')
            ->to('amiraayari.tn@gmail.com') /////////////// user conect 
            ->subject('Order Confirmation')
                ->html("Thank you for ordering the product: $productName. Price: $productPrice");

            $mailer->send($email);
        }
    }

    }





