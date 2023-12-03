<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'generate_product')]
    
    public function add_product(Request $request, ManagerRegistry $manager, ProduitRepository $produitRepository, CommandeRepository $commandeRepository): Response
    {
        $em = $manager->getManager();

        $produit = new produit();
        
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($produit->getStock() < 0) {
                $this->addFlash('danger', 'Le stock ne peut pas être négatif.');
                return $this->redirectToRoute('generate_product');
            }
            if ($produit->getPrixUnitaire() < 0) {
                $this->addFlash('danger', 'Le prix ne peut pas être négatif.');
                return $this->redirectToRoute('generate_product');
            }
            if (null === $produit->getNom()) {
                $this->addFlash('danger', 'Le champ "Nom" ne peut pas être vide.');
                return $this->redirectToRoute('generate_product');
            }
        
        
            $em->persist($produit);
            $em->flush();
    
            return $this->render('produit/index.html.twig', [
                'produits' => $produitRepository->findAll(),
                'commandes' => $commandeRepository->findAll(),
                'f' => $form->createView()]);

            }
            return $this->render('produit/index.html.twig', [
                'produits' => $produitRepository->findAll(),
                'commandes' => $commandeRepository->findAll(),

                'f' => $form->createView()]);
        }

    
        #[Route('/boutique.html', name: 'acheter_product')]
        public function pbooking(ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
        {
            // Use the findAll method to get all records
            $query = $produitRepository->findAll();
    
            $pagination = $paginator->paginate(
                $query, // Doctrine QueryBuilder (not result)
                $request->query->getInt('page', 1), // Current page number
                3 // Number of items per page
            );
    
            return $this->render('produit/acheter.html.twig', [
                'pagination' => $pagination,
                'produits' => $query,
            ]);
        }
#[Route('/produit/edit/{id}', name: 'produit_edit')]
public function editProduct(Request $request, ManagerRegistry $manager, $id, ProduitRepository $produitRepository,  CommandeRepository $commandeRepository): Response
{       $em = $manager->getManager();

        $produit  = $produitRepository->find($id);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
       

        if ($form->isSubmitted()) {
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('generate_product');
        }
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
            'commandes' => $commandeRepository->findAll(),
            'f' => $form->createView()]);
        }         
        #[Route('/produit/delete/{id}', name: 'produit_delete')]
        public function deleteProduct(Request $request, ManagerRegistry $manager, $id, ProduitRepository $produitRepository): Response
        {
        $em = $manager->getManager();
        $produit1 = $produitRepository->find($id);
        
      
        $em->remove($produit1);
        $em->flush();
        
                return $this->redirectToRoute('generate_product');
     

    }
    #[Route('/produit/commandessss/{id}', name: 'produit_commande')]
  
    public function ajouterCommande($id,ProduitRepository $produitRepository, ManagerRegistry $manager): Response
    {
        $commande = new Commande();
        $produit =$produitRepository->find($id);
    
        $user = $this->getUser();
        $usrid = $user->getId();
        $commande->setUserId($usrid);
        $factureFileName = 'facture_' . uniqid() . '.jpg';
    /////////////// user_id = user conect
        // Save the facture image to the public directory
        $facturePath = $this->getParameter('kernel.project_dir') . '/public/images/' . $factureFileName;
        // Assuming you have the facture image data available in $factureImageData
        // Example: file_put_contents($facturePath, $factureImageData);
    
        $commande->setPathFacture('images/' . $factureFileName);
        
        $commande->setProductId($id);
        $commande->setProduit($produit);
        $em= $manager->getManager();
     
       $em->persist($commande);
       $em->flush();
       $this->addFlash('success','order validated');
        return $this->redirectToRoute('app_commande_index');
    }
    
    
}    



   






    



  

    
    
   