<?php

namespace App\Controller;

use App\Repository\BookReadRepository;
use App\Repository\BookRepository;
use App\Entity\Book;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\BookRead;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use App\Form\InsertBookType;
use App\Form\SearchBookExplorerType;
use App\Form\EditBookType;

use Doctrine\Persistence\ManagerRegistry;


class HomeController extends AbstractController
{
    private BookReadRepository $readBookRepository;
    private BookRepository $allBookRepo;

    public function __construct(BookReadRepository $bookReadRepository, BookRepository $allBookRepo)
    {
        $this->bookReadRepository = $bookReadRepository;
        $this->allBookRepo = $allBookRepo;
    }

    #[Route('/', name: 'app.home')]
    public function index(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {

        // reprend l'id du user connecté
        $id = $this->getUser()->getId();

        
        $booksRead  = $this->bookReadRepository->findByUserId($id, false);
        $booksNotRead  = $this->bookReadRepository->findByUserId($id, true);

        // j'ai mieux compris cette méthod x)
        $entityManager = $doctrine->getManager();
        $bookId = $entityManager->getRepository('App\Entity\Book')->findById($id);

        $newBook = new Book();
        $book = new BookRead();


        $formInsertBook = $this->createForm(InsertBookType::class, $book);
        $formInsertBook->handleRequest($request);

        // lors de la validation du formulaire, on insère un nouveau livre en bdd
        if ($formInsertBook->isSubmitted() && $formInsertBook->isValid()) {

            // récupère la description, la note et si le livre a été lue ou non du form.
            $getDescription = $formInsertBook->get('description')->getData();
            $getRating = $formInsertBook->get('rating')->getData();;
            $getIsRead = $formInsertBook->get('is_read')->getData();;

            // ajout d'un nouveau livre en bdd
            $newBook->setName("Livre 2");
            $newBook->setDescription($getDescription);
            $newBook->setPages(230);

            $entityManager->persist($newBook);

            $entityManager->flush();

            // ajout d'un livre lue en bdd
            $book->setBookId($newBook); 
            $book->setUserId($id); 
            $book->setDescription($getDescription);
            $book->setRating($getRating);
            $book->setRead($getIsRead);
            $book->setCreatedAt(new DateTime());
            $book->setUpdatedAt(new DateTime());

            $entityManager->persist($book);

            $entityManager->flush();
            return $this->redirectToRoute('app.home');
        }

        // pour edit un livre lu ou non
        $formEditBook = $this->createForm(EditBookType::class, $book);
        $formEditBook->handleRequest($request);

      
        if ($formEditBook->isSubmitted() && $formEditBook->isValid()) {

            $getName = $request->query->get('name');

            // récupère la description, la note et si le livre a été lue ou non du form.
            $getDescription = $formEditBook->get('name')->getData();
            $getRating = $formEditBook->get('description')->getData();;
            $getIsRead = $formEditBook->get('rating')->getData();;

            // ajout d'un nouveau livre en bdd
            $newBook->setName("Livre 2");
            $newBook->setDescription($getDescription);
            $newBook->setPages(230);

            $entityManager->persist($newBook);

            $entityManager->flush();

            // ajout d'un livre lue en bdd
            $book->setBookId($newBook); 
            $book->setUserId($id); 
            $book->setDescription($getDescription);
            $book->setRating($getRating);
            $book->setRead($getIsRead);
            $book->setCreatedAt(new DateTime());
            $book->setUpdatedAt(new DateTime());

            $entityManager->persist($book);

            $entityManager->flush();
            return $this->redirectToRoute('app.home');
        }
        

        // nous renvoie sur l'accueil
        return $this->render('pages/home.html.twig', [
            'booksRead' => $booksRead,
            'bookNotRead' => $booksNotRead,
            'name'      => $id, 
            'insertData' => $formInsertBook,
            'editData' => $formEditBook,
        ]);
    }




    #[Route('/explorer', name: 'app.explorer')]
    public function explorer(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $entityManager): Response
    {

        $book = $doctrine->getManager()->getRepository('App\Entity\BookRead')->test();


        $formSearchBook = $this->createForm(SearchBookExplorerType::class, $book);
        $formSearchBook->handleRequest($request);

        // lors de la validation du formulaire, on insère un nouveau livre en bdd
        if ($formSearchBook->isSubmitted() && $formSearchBook->isValid()) {

            // récupère le nom du livre  
            $getDescription = $formSearchBook->get('name')->getData();

            $bookSearch = $entityManager->getRepository('App\Entity\Book')->findByNameBook($getDescription);

           return $this->render('pages/explorer.html.twig', [
            'name' => 'Théo',
            'test'  => "",
            'search' => $bookSearch,
            'form' => $formSearchBook
        ]);
        }


        // $allBooks = $this->allBookRepo->findAll();
        return $this->render('pages/explorer.html.twig', [
            'name' => 'Théo',
            'test'  => $book,
            'search' => "",
            'form' => $formSearchBook

        ]);
    }


      #[Route('/{name}', name: 'app.edit')]
    public function modalEdit(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $entityManager): Response
    {

        $book = $doctrine->getManager()->getRepository('App\Entity\BookRead')->test();


        $formSearchBook = $this->createForm(SearchBookExplorerType::class, $book);
        $formSearchBook->handleRequest($request);

        // lors de la validation du formulaire, on insère un nouveau livre en bdd
        if ($formSearchBook->isSubmitted() && $formSearchBook->isValid()) {

            // récupère le nom du livre  
            $getDescription = $formSearchBook->get('name')->getData();

            $bookSearch = $entityManager->getRepository('App\Entity\Book')->findByNameBook($getDescription);

           return $this->render('pages/explorer.html.twig', [
            'name' => 'Théo',
            'test'  => "",
            'search' => $bookSearch,
            'form' => $formSearchBook
        ]);
        }


        // $allBooks = $this->allBookRepo->findAll();
        return $this->render('pages/explorer.html.twig', [
            'name' => 'Théo',
            'test'  => $book,
            'search' => "",
            'form' => $formSearchBook

        ]);
    }
}