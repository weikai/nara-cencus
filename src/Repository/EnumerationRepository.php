<?php

namespace App\Repository;

use App\Entity\Enumeration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Enumeration|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enumeration|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enumeration[]    findAll()
 * @method Enumeration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnumerationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enumeration::class);
    }

    // /**
    //  * @return Enumeration[] Returns an array of Enumeration objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Enumeration
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
