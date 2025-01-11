<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SearchBookExplorerType;


class ExplorerController extends AbstractController
{
       #[Route('/explorer', name: 'app.explorer')]
    public function explorer(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $entityManager): Response
    {

        $book = $doctrine->getManager()->getRepository('App\Entity\BookRead')->findAllBook();


        $formSearchBook = $this->createForm(SearchBookExplorerType::class, $book);
        $formSearchBook->handleRequest($request);

        // lors de la validation du formulaire, on insère un nouveau livre en bdd
        if ($formSearchBook->isSubmitted() && $formSearchBook->isValid()) {

            // récupère le nom du livre  
            $getDescription = $formSearchBook->get('name')->getData();

            // fais la recherche en fonction de l'entrée utilisateur 
            $bookSearch = $entityManager->getRepository('App\Entity\Book')->findByNameBook($getDescription);

           return $this->render('pages/explorer.html.twig', [
            'name' => 'Théo',
            'allBook'  => "",
            'search' => $bookSearch,
            'form' => $formSearchBook
        ]);
        }


        return $this->render('pages/explorer.html.twig', [
            'name' => 'Théo',
            'allBook'  => $book,
            'search' => "",
            'form' => $formSearchBook

        ]);
    }
}
