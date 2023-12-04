<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\DBAL\Driver\Mysqli\Initializer\Options;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

//biblio entity interafce 
#[Route('/user')]
class UserController extends AbstractController
{
    
    #[Route('/editprofile', name: 'app_user_profil', methods: ['GET', 'POST'])]
    public function editProfil(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Find the user by ID
        $user = $entityManager->getRepository(User::class)->find($this->getUser());
    
        // Check if the user exists
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
    
        // Create the edit form
        $form = $this->createForm(UserType::class, $user, [
            'is_edit' => true, 
        ]);
        $form->handleRequest($request);
    
        // Handle form submission
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the changes to the database
            $entityManager->flush();
    
            // Redirect to a success page or any other route
            return $this->redirectToRoute('app_default', [], Response::HTTP_SEE_OTHER);
        }
    
        // Render the edit form
        return $this->renderForm('user/profile.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/index', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setMotDePasse($form->get('motDePasse')->getData());
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'password' => false, 'is_edit' => false, 
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //persistance des changements
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            // Marque l'objet $user à supprimer lors de la prochaine opération de flush.
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/user/search', name: 'app_user_search', methods: ['POST'])]
    public function search(Request $request, UserRepository $userRepository): JsonResponse
    {
        $searchTerm = $request->request->get('searchTerm');
        $users = $userRepository->search($searchTerm);
    
        
        $data = [
            'users' => $users,
        ];
    
        return $this->json($data);
    }

  
}





