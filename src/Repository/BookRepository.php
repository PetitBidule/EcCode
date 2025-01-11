<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @return Book[] Renvoie tout les livres
     */
    // public function findAll(): array
    // {
    //     return $this->createQueryBuilder('b')
    //         ->join('r.book_id', 'b')
    //         ->getQuery()
    //         ->getResult();
    // }

    // renvoie tout les livres en fonction de l'id 
    public function findById(int $userId): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    // renvoie en fonction du nom du livre 
     public function findByNameBook(string $bookName): array
    {
       return $this->createQueryBuilder('b')
        ->where('b.name LIKE :name')
        ->setParameter('name', '%' . $bookName . '%') 
        ->getQuery()
        ->getResult();
    }

}
