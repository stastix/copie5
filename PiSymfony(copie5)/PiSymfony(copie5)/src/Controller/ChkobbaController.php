<?php

namespace App\Controller;

use ApiPlatform\Api\UrlGeneratorInterface;
use App\Entity\Payment;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Omnipay\Omnipay;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ChkobbaController extends AbstractController
{
    
  private $passerelle;

  private $manager;
  
  public function __construct(EntityManagerInterface $manager)
  {
    $this->passerelle = Omnipay::create('PayPal_Rest');
    $this->passerelle->initialize([
        'clientId' => $_ENV['PAYPAL_CLIENT_ID'],
        'secret' => $_ENV['PAYPAL_SECRET_KEY'],
        'testMode' => true,
    ]);

    $this->manager = $manager;

  }




   //Page d'accueil
   #[Route('/baw', name: 'app_cart')]
   public function cart(): Response
   {
       return $this->render('operation/cart.html.twig');
   }


   //Page d'accueil
   #[Route('/payment/{idR}', name: 'app_payment')]
   public function payment($idR,ReservationRepository $reservationRepository): Response
   {
     $reservation= $reservationRepository->find($idR);


     $response = $this->passerelle->purchase([
      'amount'     => $reservation->getPrixR(),
      'currency'   => $_ENV['PAYPAL_CURRENCY'],
      'returnUrl'  => 'https://127.0.0.1:8000/success',
      'cancelUrl'  => 'https://127.0.0.1:8000/error',
  ])->send();
    
      
  if ($response->isRedirect()) {
    return $this->redirect($response->getRedirectUrl());
}

       
     
      
       return $this->render('operation/index.html.twig');
   }


  
   #[Route('/success', name: 'app_success')]
   public function success(): Response
   {
    
        $operation=$this->passerelle->completePurchase(array(
           'payer_id'=>'2',
           'transactionReference'=>'10'
        ));

        $response=$operation->send();

        if($response->isSuccessful())
        {
            $data=$response->getData();

             
            $payment = new Payment();

            $payment->setPaymentId($data['id'])
                    ->setPayerId($data['payer']['payer_info']['payer_id'])
                    ->setPayerEmail('sb-ypi2s27615725@personal.example.com')
                    ->setAmount('500')
                    ->setCurrency($_ENV['PAYPAL_CURRENCY'])
                    ->setParchasedAt(new \DateTime())
                    ->setPaymentStatut('yes');


           $this->manager->persist($payment);

           $this->manager->flush();
           
           return $this->render('Operation/success.html.twig',
             [
               'message'=>'Votre paiement a été un succès'
             ]
             );
        }

        else
        {
           return $this->render('Operation/success.html.twig',
             [
               'message'=>'Le paiement a échoué !'
             ]
             );
        }
        
   }
   #[Route('/error', name: 'app_error')]
   public function error(): Response
   {
       return $this->render('Operation/success.html.twig',
             [
               'message'=>'le paiement a échoué'
             ]
             );
   }
}

