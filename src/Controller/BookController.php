<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book/list_books', name:'list_books')]
    public function list_books(BookRepository $repo): Response
    {
        $list=$repo->findAll();
        return $this->render('book/listB.html.twig', [
            'Books' => $list,
        ]);
    }

    #[Route('/book/addBook', name:'addBook')]
    public function addbook(Request $request, EntityManagerInterface $entityManager)
    {
        $book = new Book();
        #$book->setAuthor($author);
        #$author->setNb_Books($author->getNb_Books() + 1);
        $book->setPublished(true);
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $book->getAuthor();
            $author->setNb_Books($author->getNb_Books() + 1);
            // Les données du formulaire sont valides, vous pouvez les sauvegarder dans la base de données
            $entityManager->persist($book);
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', 'Le livre a été enregistré avec succès.');

            // Redirigez l'utilisateur vers une autre page (par exemple, une liste d'auteurs)
            return $this->redirectToRoute('list_books');
        }

        return $this->render('book/BookType.html.twig', ['form' => $form->createView(),]);
    }

    #[Route('/editB/{id}', name:'edit_book')]
    public function editbook($id, Request $request, EntityManagerInterface $entityManager)
    {
        // Récupérer l'auteur à partir de l'ID
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Book non trouvé');
        }

        // Créer le formulaire de modification de l'auteur en utilisant l'entité récupérée
        $form = $this->createForm(BookType::class, $book);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            // Rediriger l'utilisateur vers une autre page (par exemple, la liste des auteurs)
            return $this->redirectToRoute('list_books');
        }

        return $this->render('book/bookEdit.html.twig', [
            'form' => $form->createView(),
            'book' => $book, // Passer l'entité Author pour afficher les informations actuelles
        ]);
    }

    #[Route('/deleteB/{id}', name:'delete_book')]
    public function deletebook($id, BookRepository $repo, ManagerRegistry $manager)
    {
        $book = $repo->find($id);

        if ($book) {
            $author = $book->getAuthor();
            $en = $manager->getManager();
            $author->setNb_Books($author->getNb_Books() - 1);
            $en->remove($book);
            $en->flush();
            // Vérifier le nombre de livres de l'auteur
            $authorBooks = $author->getNb_Books();
            if ($authorBooks == 0) {
                // Si l'auteur n'a plus de livres, le supprimer
                $en->remove($author);
                $en->flush();
            }
        }

        // Rediriger l'utilisateur vers une autre page (par exemple, la liste des livres)
        return $this->redirectToRoute('list_books');
    }

    #[Route('book/showB/{id}', name:'showB')]
    public function showB($id,BookRepository $repo)
    {
        $list= $repo->find($id);
        return $this->render('book/showB.html.twig',['book'=>$list]);
    }
}
