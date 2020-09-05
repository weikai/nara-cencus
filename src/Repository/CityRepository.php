<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    /**
     * @return City[] Returns an array of City objects
    */
    
    public function findCityBy($query)
    {
        
        $qb = $this->createQueryBuilder('c');
        if(!empty($query)){            
            $qb->innerJoin('App\Entity\CityState', 'cs', 'WITH', 'c.id = cs.City');
            foreach($query as $key => $value){
                $field = ($key == 'state')? 'Abbr' :'Name';
                $ukey = ucfirst($key);
                $qb->innerJoin("App\Entity\\$ukey" , $key, 'WITH', "cs.$ukey = $key.id")
                ->andWhere("$key.$field = :$key")
                ->setParameter($key, $value);
            }
        }            
        $qb->orderBy('c.Name', 'ASC');
        return $qb->getQuery()->getResult();        
    }

    // /**
    //  * @return City[] Returns an array of City objects
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
    public function findOneBySomeField($value): ?City
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
