<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Form\SearchBookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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
    public function addbook(Request $request, ManagerRegistry $manager)
    {
        $book = new Book();
        $book->setPublished(true);
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $book->getAuthor();
            $author->setNb_Books($author->getNb_Books() + 1);
            // Les données du formulaire sont valides, vous pouvez les sauvegarder dans la base de données
            $em=$manager->getManager();
            $em->persist($book);
            $em->persist($author);
            $em->flush();

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
    #[Route('showBooksQueryBuilder', name:'showBooksQueryBuilder')]
    public function showBooksQueryBuilder(BookRepository $repo){
        $list=$repo->showBooksQB();
        return $this->render('book/listB.html.twig',['Books' => $list,]);
    }

    #[Route('showBookDQL', name:'showBookDQL')]
    public function showBookDQL(BookRepository $repo){
        $list=$repo->showBookDQL();
        return $this->render('book/listB.html.twig',['Books' => $list,]);
    }


    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/list_books', name: 'list_books')]
    public function listBooks(Request $request, BookRepository $repo): Response
    {
        $form = $this->createForm(SearchBookType::class);
        $form->handleRequest($request);
        $countRomanceBooks = $repo->countRomanceBooksDQL();

        $list = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $ref = $form->get('ref')->getData(); // Récupérez la référence à partir du formulaire

            if ($ref) {
                // Utilisez la méthode searchBookByRef que nous avons définie dans le repository
                $book = $repo->searchBookByRefQB($ref);

                if ($book) {
                    $list = [$book]; // Si un livre correspondant est trouvé, ajoutez-le à la liste
                }
            } else {
                $list = $repo->findAll(); // Si aucun paramètre n'a été saisi, affichez tous les livres
            }
        }
        else {
            $list = $repo->findAll(); // Si aucun paramètre n'a été saisi, affichez tous les livres
        }

        return $this->render('book/listB.html.twig', [
            'Books' => $list,
            'form' => $form->createView(),
            'countRomanceBooks' => $countRomanceBooks,// Passez le formulaire à la vue
        ]);
    }


    #[Route('booksListByAuthors', name:'booksListByAuthors')]
    public function booksListByAuthors(BookRepository $repo, Request $request): Response
    {
        $form = $this->createForm(SearchBookType::class);
        $form->handleRequest($request);

        $list=$repo->booksListByAuthorsQB();
        return $this->render('book/listB.html.twig',['Books' => $list,
            'form' => $form->createView(),]);
    }


    #[Route('booksListByYear', name:'booksListByYear')]
    public function booksListByYear(BookRepository $repo, Request $request): Response
    {
        $form = $this->createForm(SearchBookType::class);
        $form->handleRequest($request);

        $list=$repo->booksListByYearQB();
        return $this->render('book/listB.html.twig',['Books' => $list,
            'form' => $form->createView(),]);
    }

    #[Route('updateScienceFictionToRomance', name:'updateScienceFictionToRomance')]
    public function updateScienceFictionToRomance(BookRepository $repo): Response
    {
        $repo->updateScienceFictionToRomanceQB();

        $this->addFlash('success', 'Mise à jour effectuée avec succès !');

        // Redirigez l'utilisateur vers une autre page (par exemple, la liste des livres)
        return $this->redirectToRoute('list_books');
    }

    #[Route('booksListByYear2', name:'booksListByYear2')]
    public function booksListByYear2(BookRepository $repo, Request $request): Response
    {
        $form = $this->createForm(SearchBookType::class);
        $form->handleRequest($request);
        $countRomanceBooks = $repo->countRomanceBooksDQL();

        $list=$repo->booksListByYearDQL();
        return $this->render('book/listB.html.twig', [
            'Books' => $list,
            'form' => $form->createView(),
            'countRomanceBooks' => $countRomanceBooks,// Passez le formulaire à la vue
        ]);
    }



}
