<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use App\Repository\BookRepository;
use App\Repository\BookReadRepository;
use App\Form\SearchBookExplorerType;
use App\Form\InsertBookType;
use App\Form\EditBookType;
use App\Entity\User;
use App\Entity\BookRead;
use App\Entity\Book;

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
        // $formEditBook = $this->createForm(EditBookType::class, $book);
        // $formEditBook->handleRequest($request);

      
        // if ($formEditBook->isSubmitted() && $formEditBook->isValid()) {


        //     // récupère la description, la note et si le livre a été lue ou non du form.
        //     $getDescription = $formEditBook->get('name')->getData();
        //     $getRating = $formEditBook->get('description')->getData();;
        //     $getIsRead = $formEditBook->get('rating')->getData();;
        //     return $this->redirectToRoute('app.home');
        // }
        

        // nous renvoie sur l'accueil
        return $this->render('pages/home.html.twig', [
            'name'      => $id, 
            'booksRead' => $booksRead,
            'bookNotRead' => $booksNotRead,
            'insertData' => $formInsertBook,
            // 'editData' => $formEditBook,
        ]);
    }





}