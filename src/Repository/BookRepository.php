<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function SearchBookByRef($ref) {
    return $this->createQueryBuilder('b')
        ->where('b.ref = :ref')
        ->setParameter('ref', $ref)
        ->getQuery()
        ->getResult();
}





public function BookListByAuthor() {
    $qb = $this->createQueryBuilder("q")
    ->orderBy('q.author', 'ASC'); 
   $preresult=$qb ->getQuery();
  return $preresult->getResult();
}



public function BooksBefore2023() {     //livres  publiés avant l’année 2023 dont l’auteur a plus de 10 livres
    $books = $this->createQueryBuilder()
        ->select('b')
        ->from('App\Entity\Book', 'b')
        ->join('b.author', 'a')
        ->where('b.publicationDate < :year')
        ->andWhere('a.nbbooks > :bookCount')
        ->setParameter('year', new \DateTime('2023-01-01'))
        ->setParameter('bookCount', 10)
        ->getQuery()
        ->getResult();
    return $books;
}

public function ModifCategoryBooks() {
    return $this->createQueryBuilder('b')
    ->update('App\App\Entity\Book', 'b')
    ->set('b.category','Romance')
    ->where("b.category = 'Science-fiction'")
    ->getQuery()
    ->getResult();


}


public function CountRomance() {
    $em=$this->getEntityManager ();
    $req=$em->createQuery("select count(b) from App\Entity\Book b where b.category='Romance'");
    $result=$req->getResult();
    return $result;

}

public function BooksBetweenDates() {
    $em=$this->getEntityManager ();
    $startDate = new \DateTime('2014-01-01');
    $endDate = new \DateTime('2018-12-31');

    $req=$em->createQuery("select b from App\Entity\Book b WHERE b.publicationDate >= :start_date
    AND b.publicationDate <= :end_date")
    ->setParameter('start_date', $startDate)
    ->setParameter('end_date', $endDate);
   

    $result=$req->getResult();
    return $result;


}





}