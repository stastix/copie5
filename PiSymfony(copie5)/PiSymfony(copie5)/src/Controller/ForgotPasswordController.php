<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\MailService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\EmailverifType;
use App\Form\CodeverifType;
use App\Form\ModifpassType;

class ForgotPasswordController extends AbstractController
{

    #[Route('/emailVerification', name: 'email_verification', methods: ['GET', 'POST'])]
public function emailVerification(Request $request,UserRepository $repo,MailerInterface $mailer, MailService $mailService): Response
{
    $code = $this->generateRandomString(6);

    $form1 = $this->createForm(EmailverifType::class);
    //traiter demande http 
    $form1->handleRequest($request);

    if ($form1->isSubmitted() && $form1->isValid()) {
       
        // Handle form submission if needed
        // For example, you can check the submitted email and send a verification email
        $enteredEmail = $form1->get('email')->getData();
        $mailService->sendEmail($enteredEmail, 'Email Verification', "This is your code :  $code");
        $user = $repo->findOneBy(['email' => $enteredEmail]);
        $id=$user->getId();

        //add message a la session et stocké dans session pour les utiliser 
        $this->addFlash('success', 'Verification email sent successfully.');

        // Redirect to a success page or another route
        return $this->redirectToRoute('code_verification', ['userId' => $id, 'verificationCode' => $code]);
        
    }

    return $this->render('forgot_password/emailverif.html.twig', [
        'form1' => $form1->createView(),
    ]);
}
#[Route('/codeverif/{userId}/{verificationCode}', name: 'code_verification', methods: ['GET', 'POST'])]
public function codeverif(Request $request, int $userId, string $verificationCode): Response
{
    $form1 = $this->createForm(CodeverifType::class);

    $form1->handleRequest($request);

    if ($form1->isSubmitted() && $form1->isValid()) {
        $code = $form1->get('Code')->getData();
        if($code==$verificationCode){
            return $this->redirectToRoute('modif_verification', ['userId' => $userId]);

        }
        else{
            $this->addFlash('Verify the code sent to your email', 'Code Incorrect.');
        }
        
        // Handle form submission if needed
        // For example, you can check the submitted code and perform verification

     

        // Return the verification code in the response
       
    }

    return $this->render('forgot_password/verifcode.html.twig', [
        'form1' => $form1->createView(),
        'userId' => $userId,
        'verificationCode' => $verificationCode,
    ]);
}


#[Route('/codemodif/{userId}', name: 'modif_verification', methods: ['GET', 'POST'])]
public function codemodif(Request $request, int $userId,ManagerRegistry $manager,UserRepository $repo): Response
{
    $form1 = $this->createForm(ModifpassType::class);

    $form1->handleRequest($request);
    $em = $manager->getManager();
    $user = $repo->find($userId);
    $email=$user->getEmail();
    if ($form1->isSubmitted()) {
        // Handle form submission if needed
        // For example, you can check the submitted data and update the password
         // Hash the password before saving it to the database
         $hashedPassword = password_hash($user->getMotDePasse(), PASSWORD_BCRYPT);
         $user->setMotDePasse($hashedPassword);
         $hashedPassword = password_hash($user->getMotDePasse(), PASSWORD_BCRYPT);
         $user->setMotDePasse($hashedPassword);
         $em->flush();
        $this->addFlash('success', 'Success.');

        // Redirect to a success page or another route
        return $this->redirectToRoute('app_login');
    }

    return $this->render('forgot_password/modifpass.html.twig', [
        'form1' => $form1->createView(),
        'userId' => $userId,
        'email'=>$email,
        
    ]);
    
}


private function generateRandomString(int $length): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}
}