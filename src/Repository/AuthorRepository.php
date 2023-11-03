<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function listAuthorByEmail()
{
    $qb = $this->createQueryBuilder("q")
    ->orderBy('q.email', 'ASC'); 
   $preresult=$qb ->getQuery();
  return $preresult->getResult();
}




public function SearchAuthorbyNbBooks(int $min, int $max){
    $em=$this->getEntityManager ();
   $req=$em->createQuery("select a from App\Entity\Author a where a.nbbooks > :min AND a.nbbooks < :max");
    $req->setParameter('min', $min)
        ->setParameter('max', $max);
  return $req ->getResult();
}


public function DeleteAuthorByNbBooks(){
    $em=$this->getEntityManager ();
    $req=$em->createQuery("Delete from App\Entity\Author a where a.nbbooks = 0");
    return $req->getResult();
}
}
