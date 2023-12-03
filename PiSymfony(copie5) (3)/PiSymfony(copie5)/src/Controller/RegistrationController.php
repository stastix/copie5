<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\Authenticator;
use App\Security\EmailVerifier;
 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

use Symfony\Component\Mailer\MailerInterface;
use App\Service\TwilioService;


class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private MailerInterface $mailer;
    private TwilioService $twilioService;
    

    public function __construct(EmailVerifier $emailVerifier , MailerInterface $mailer,TwilioService $twilioService)
    {
        $this->emailVerifier = $emailVerifier;
        $this->twilioService = $twilioService; 
        
    }

    

    


    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, Authenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        

        $user->setRole("CLIENT");
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
         

        $user->setMotDePasse($form->get('plainPassword')->getData());
        
        $dateNaissance = $form->get('dateNaissance')->getData();
        $dateNaissanceString = $dateNaissance->format('Y-m-d');
        $user->setDateNaissance($dateNaissanceString);
     
        
    
        $hashedPassword = password_hash($user->getMotDePasse(), PASSWORD_BCRYPT);
         $user->setMotDePasse($hashedPassword);
        

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            $smsMessage = 'Bienvenue sur notre site! Merci pour votre inscription.';
            $errorMessage = 'Une erreur s\'est produite lors de l\'envoi du SMS.';

            try {
                // Envoi du SMS après l'inscription
                $to = '+21621577358'; // Remplacez par le numéro de téléphone de l'utilisateur
                $message = 'Bienvenue sur notre site! Merci pour votre inscription.';
                $this->twilioService->sendSMS($to, $message);
            } catch (\Exception $e) {
                // Gestion de l'erreur d'envoi SMS
                $this->addFlash('error', 'Une erreur s\'est produite lors de l\'envoi du SMS.');
            }
            
            // Envoi de l'e-mail de réinitialisation
           // $this->sendResetPasswordEmail($user);

            
 

            // Redirection après l'inscription
            return $this->redirectToRoute('app_login');
            // generate a signed url and email it to the user

            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }



    
}
