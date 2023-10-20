<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    #[Route('/produit/list_produits', name:'list_produits')]
    public function list_produits(ProduitRepository $repo): Response
    {
        $list=$repo->findAll();
        return $this->render('produit/listP.html.twig', [
            'Produits' => $list,
        ]);
    }

    #[Route('/produit/addProduit', name:'addProduit')]
    public function addProduit(Request $request, ManagerRegistry $manager)
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont valides, vous pouvez les sauvegarder dans la base de données
            $em=$manager->getManager();
            $em->persist($produit);
            $em->flush();

            $this->addFlash('success', 'Le produit a été enregistré avec succès.');

            // Redirigez l'utilisateur vers une autre page (par exemple, une liste d'auteurs)
            return $this->redirectToRoute('list_produits');
        }

        return $this->render('produit/ProduitType.html.twig', ['form' => $form->createView(),]);
    }
}
