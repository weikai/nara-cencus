<?php

namespace App\Repository;

use App\Entity\CityState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CityState|null find($id, $lockMode = null, $lockVersion = null)
 * @method CityState|null findOneBy(array $criteria, array $orderBy = null)
 * @method CityState[]    findAll()
 * @method CityState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CityState::class);
    }

    // /**
    //  * @return CityState[] Returns an array of CityState objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CityState
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
