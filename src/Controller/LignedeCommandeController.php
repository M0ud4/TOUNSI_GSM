<?php

namespace App\Controller;

use App\Entity\LignedeCommande;
use App\Entity\Panier;
use App\Form\LignedeCommandeType;
use App\Repository\PanierRepository;
use App\Repository\ProductRepository;
use App\Repository\LignedeCommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lignede/commande')]
final class LignedeCommandeController extends AbstractController
{
    #[Route(name: 'app_lignede_commande_index', methods: ['GET'])]
    public function index(LignedeCommandeRepository $lignedeCommandeRepository): Response
    {
        return $this->render('lignede_commande/index.html.twig', [
            'lignede_commandes' => $lignedeCommandeRepository->findAll(),
        ]);
    }
    #[Route('/add/{id}', name: 'app_lignede_commande_add', methods: ['GET', 'POST'])]
    public function add(int $id, ProductRepository $productRepository, PanierRepository $panierRepository, EntityManagerInterface $entityManager): Response
    {
        $product = $productRepository->find($id);
    
        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }
    
        $user = $this->getUser();
        $panier = $panierRepository->findOneBy(['user' => $user, 'isActive' => true]);
    
        if (!$panier) {
            $panier = new Panier();
            $panier->setUser($user);
            $panier->setIsActive(true);
            $entityManager->persist($panier);
        }
    
        $ligne = new LignedeCommande();
        $ligne->setProduct($product);
        $ligne->setQuantity('1'); // Exemple, vous pouvez ajuster la logique pour gérer la quantité
        $ligne->setIdPanier($panier);
    
        $panier->addLignedeCommande($ligne);
        $entityManager->persist($ligne);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/new', name: 'app_lignede_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lignedeCommande = new LignedeCommande();
        $form = $this->createForm(LignedeCommandeType::class, $lignedeCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lignedeCommande);
            $entityManager->flush();

            return $this->redirectToRoute('app_lignede_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lignede_commande/new.html.twig', [
            'lignede_commande' => $lignedeCommande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lignede_commande_show', methods: ['GET'])]
    public function show(LignedeCommande $lignedeCommande): Response
    {
        return $this->render('lignede_commande/show.html.twig', [
            'lignede_commande' => $lignedeCommande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lignede_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LignedeCommande $lignedeCommande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LignedeCommandeType::class, $lignedeCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_lignede_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lignede_commande/edit.html.twig', [
            'lignede_commande' => $lignedeCommande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lignede_commande_delete', methods: ['POST'])]
    public function delete(Request $request, LignedeCommande $lignedeCommande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lignedeCommande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($lignedeCommande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_lignede_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
