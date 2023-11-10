<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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

    public function showBooksQB(){
        $list=$this->createQueryBuilder('b')
            ->where('b.title',':param')
            ->addWhere('b.title',':param2')
            ->setParameter('param','g%')  //les paramètres nommés
            ->setParameter('param2','%b')
            //->setParameter(['param'=>'g%','param2'=>'%b']) //plusieur parametres
            //->where('b.title','?1')
            //->setParameter('1','g%')  //les paramètres positionnels
                ->orderBy('b.title','ASC')
            ->getQuery()
            ->getResult();
        return $list;
    }

    public function showBookDQL(){
        $em=$this->getEntityManager();
        $list=$em->createQuery('select b from App\Entity\Book b where b.title LIKE :param')
            ->setParameter('param','g%')
            ->getResult();
        return $list;
    }


    public function booksListByAuthorsQB(){
        $list=$this->createQueryBuilder('b')
            ->orderBy('b.author','ASC')
            ->getQuery()
            ->getResult();
        return $list;
    }

    public function searchBookByRefQB($ref)
    {
        return $this->createQueryBuilder('b')
            ->where('b.ref = :ref')
            ->setParameter('ref', $ref)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function booksListByYearQB()
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.author', 'a')
            ->where('b.publicationDate < :ref')
            ->andWhere('a.nb_books > 10')
            ->setParameter('ref', new \DateTime('2023-01-01'))
            ->getQuery()
            ->getResult();
    }

    public function updateScienceFictionToRomanceQB()
    {
        return $this->createQueryBuilder('b')
            ->update()
            ->set('b.category', ':newCategory')
            ->where('b.category = :oldCategory')
            ->setParameter('newCategory', 'Romance')
            ->setParameter('oldCategory', 'Science-Fiction')
            ->getQuery()
            ->execute();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countRomanceBooksDQL(){
        $em= $this->getEntityManager();

        $query = $em->createQuery('SELECT COUNT(b.ref) FROM App\Entity\Book b WHERE b.category = :category')
            ->setParameter('category', 'Romance');

        return $query->getSingleScalarResult();
    }
    public function booksListByYearDQL(){
        $em=$this->getEntityManager();
        $list=$em->createQuery('select b from App\Entity\Book b where b.publicationDate > :date1 and b.publicationDate < :date2')
            ->setParameter('date1', new \DateTime('2018-12-31'))
            ->setParameter('date2', new \DateTime('2024-01-01'))
            ->getResult();
        return $list;
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
}
