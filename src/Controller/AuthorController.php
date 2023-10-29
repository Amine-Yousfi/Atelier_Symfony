<?php

namespace App\Controller;

use App\Form\SearchAuthorType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Container2cC8FC5\getAuthorRepositoryService;
use Doctrine\ORM\EntityManagerInterface;
#use phpDocumentor\Reflection\DocBlock\Tags\Author;
#use Symfony\Bridge\Doctrine\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;
use App\Form\AuthorType;
use Symfony\Component\HttpFoundation\Request;


class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    //exercice 1
   /* #[Route('/author/{name}', name:'author')]
    public function showAuthor($name)
    {
        return $this->render( 'author/show.html.twig',['ghassen'=> $name]);
    }
*/
//exercice 2
   /* #[Route('/author/list', name:'list')]
    public function list(AuthorRepository $repo): Response
    {
        $list=$repo->findAll();
        $authors = array(
            array('id' => 4, 'picture' => 'images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
                'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 5, 'picture' => 'images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
                ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 6, 'picture' => 'images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
                'taha.hussein@gmail.com', 'nb_books' => 300),
        );
        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }*/

    #[Route('/author/list_authors', name:'list_authors')]
    public function list_authors(AuthorRepository $repo): Response
    {
        $list=$repo->findAll();
        return $this->render('author/list.html.twig', [
            'authors' => $list,
        ]);
    }

    #[Route('author/showA/{id}', name:'showA')]
    public function showA($id,AuthorRepository $repo)
    {
        $list= $repo->find($id);
        return $this->render('author/showA.html.twig',['author'=>$list]);
    }


    #[Route('/author/AuthorType', name:'createauthor')]
    public function createAuthor(Request $request)
    {
        $author = new Author();
        $author->setNb_Books(0);
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont valides, vous pouvez les sauvegarder dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', 'L\'auteur a été enregistré avec succès.');

            // Redirigez l'utilisateur vers une autre page (par exemple, une liste d'auteurs)
            return $this->redirectToRoute('list_authors');
        }

        return $this->render('author/AuthorType.html.twig', ['form' => $form->createView(),]);
    }

    #[Route('/delete/{id}', name:'delete_author')]
    public function deleteAuthor($id, AuthorRepository $repo, ManagerRegistry $manager)
    {
        $author =$repo->find($id);
        $en=$manager->getManager();
        $en->remove($author);
        $en->flush();

        // Rediriger l'utilisateur vers une autre page (par exemple, la liste des auteurs)
        return $this->redirectToRoute('list_authors');
    }
    #[Route('/edit/{id}', name:'edit_author')]
    public function editAuthor($id, Request $request, EntityManagerInterface $entityManager)
    {
        // Récupérer l'auteur à partir de l'ID
        $author = $entityManager->getRepository(Author::class)->find($id);

        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        // Créer le formulaire de modification de l'auteur en utilisant l'entité récupérée
        $form = $this->createForm(AuthorType::class, $author);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            // Rediriger l'utilisateur vers une autre page (par exemple, la liste des auteurs)
            return $this->redirectToRoute('list_authors');
        }

        return $this->render('author/AuthorEdit.html.twig', [
            'form' => $form->createView(),
            'author' => $author, // Passer l'entité Author pour afficher les informations actuelles
        ]);
    }

    #[Route('/author/jj', name:'add')]
    public function add(ManagerRegistry $manager): Response
    {
        $author = new Author();
        $author->setUsername('ghassen ben aissa');
        $author->setEmail('ghassen.benaissa@gmail.com');


        // Obtenez le gestionnaire d'entités (EntityManager)
        $em = $manager->getManager();

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($author);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new product with id '.$author->getId());
    }


    #[Route('/author/{id}', name:'authorDetails')]
    public function authorDetails($id)
    {

        $authors = array(
            array('id' => 5, 'picture' => 'images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
                'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => 'images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
                ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => 'images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
                'taha.hussein@gmail.com', 'nb_books' => 300),
        );
        foreach ($authors as $authors) {
            if ($authors['id'] == $id) {
                return $this->render( 'author/showAuthor.html.twig',['det'=> $authors]);
            }

        }
        return null;
    }

    #[Route('listAuthorByEmail', name:'listAuthorByEmail')]
    public function listAuthorByEmail(AuthorRepository $repo): Response
    {
        $list=$repo->listAuthorByEmail();
        return $this->render('author/list.html.twig', [
            'authors' => $list,
        ]);
    }

    #[Route('/list_Authors', name: 'list_Authors')]
    public function listAuthors(Request $request, AuthorRepository $repo): Response
    {
        $form = $this->createForm(SearchAuthorType::class);
        $form->handleRequest($request);

        $list = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $minnb = $form->get('minnb')->getData(); // Récupérez la référence à partir du formulaire
            $maxnb = $form->get('maxnb')->getData(); // Récupérez la référence à partir du formulaire

            if ($maxnb && $minnb) {
                // Utilisez la méthode searchBookByRef que nous avons définie dans le repository
                $authors = $repo-> authorsListByYearDQL($minnb,$maxnb);

                if ($authors) {
                    $list = $authors; // Si un livre correspondant est trouvé, ajoutez-le à la liste
                }
            } else {
                $list = $repo->findAll(); // Si aucun paramètre n'a été saisi, affichez tous les livres
            }
        }
        else {
            $list = $repo->findAll(); // Si aucun paramètre n'a été saisi, affichez tous les livres
        }

        return $this->render('author/list.html.twig', [
            'authors' => $list,
            'form' => $form->createView(),// Passez le formulaire à la vue
        ]);
    }
    #[Route('deleteAuthorsWithZeroBooks', name:'deleteAuthorsWithZeroBooks')]
    public function deleteAuthorsWithZeroBooks(AuthorRepository $repo): Response
    {
        $repo->deleteAuthorsWithZeroBooksDQL();

        $this->addFlash('success', 'Mise à jour effectuée avec succès !');

        // Redirigez l'utilisateur vers une autre page (par exemple, la liste des livres)
        return $this->redirectToRoute('list_Authors');
    }

}
