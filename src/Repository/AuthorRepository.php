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

    //afficher la liste des auteurs par ordre alphabétique des adresses email.
    public function listAuthorByEmail(){
        $list=$this->createQueryBuilder('a')
            ->orderBy('a.email','ASC')
            ->getQuery()
            ->getResult();
        return $list;
    }

    // rechercher la liste des auteurs dont le nombre de livres est compris entre deux valeurs
    public function authorsListByYearDQL($minnb,$maxnb){
        $em=$this->getEntityManager();
        $list = $em->createQuery('SELECT a FROM App\Entity\Author a WHERE a.nb_books >= :minnb AND a.nb_books <= :maxnb')
            ->setParameter('minnb', $minnb)
            ->setParameter('maxnb', $maxnb)
            ->getResult();
        return $list;
    }

    // Supprimer les auteurs dont le nombre des livres est égale à 0 ( nb_books = 0)
    public function deleteAuthorsWithZeroBooksDQL(){
        $em=$this->getEntityManager();
        $list = $em->createQuery('DELETE FROM App\Entity\Author a WHERE a.nb_books = :zero')
            ->setParameter('zero', 0)
            ->execute();
    }


//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//          ph  ->orderBy('a.id', 'ASC')
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
}
