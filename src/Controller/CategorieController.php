<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\BookRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }

    #[Route('/categorie/list_categories', name:'list_categories')]
    public function list_categories(CategorieRepository $repo): Response
    {
        $list=$repo->findAll();
        return $this->render('categorie/listC.html.twig', [
            'Categories' => $list,
        ]);
    }

    #[Route('/categorie/addCategorie', name:'addCategorie')]
    public function addCategorie(Request $request, ManagerRegistry $manager)
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont valides, vous pouvez les sauvegarder dans la base de données
            $em=$manager->getManager();
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'La categorie a été enregistré avec succès.');

            // Redirigez l'utilisateur vers une autre page (par exemple, une liste d'auteurs)
            return $this->redirectToRoute('list_categories');
        }

        return $this->render('categorie/CategorieType.html.twig', ['form' => $form->createView(),]);
    }

    #[Route('/editC/{id}', name:'edit_categorie')]
    public function editcategorie($id, Request $request, EntityManagerInterface $entityManager)
    {
        // Récupérer l'auteur à partir de l'ID
        $categorie = $entityManager->getRepository(Categorie::class)->find($id);

        if (!$categorie) {
            throw $this->createNotFoundException('categorie non trouvé');
        }

        // Créer le formulaire de modification de l'auteur en utilisant l'entité récupérée
        $form = $this->createForm(CategorieType::class, $categorie);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            // Rediriger l'utilisateur vers une autre page (par exemple, la liste des auteurs)
            return $this->redirectToRoute('list_categories');
        }

        return $this->render('categorie/CategorieEdit.html.twig', [
            'form' => $form->createView(),
            'categorie' => $categorie, // Passer l'entité Author pour afficher les informations actuelles
        ]);
    }

    #[Route('/deleteC/{id}', name:'delete_categorie')]
    public function deletecategorie($id, CategorieRepository $repo, ManagerRegistry $manager)
    {
        $categorie =$repo->find($id);
            $en = $manager->getManager();
            $en->remove($categorie);
            $en->flush();
        // Rediriger l'utilisateur vers une autre page (par exemple, la liste des livres)
        return $this->redirectToRoute('list_categories');
    }
}
