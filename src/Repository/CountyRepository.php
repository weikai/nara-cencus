<?php

namespace App\Repository;

use App\Entity\County;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method County|null find($id, $lockMode = null, $lockVersion = null)
 * @method County|null findOneBy(array $criteria, array $orderBy = null)
 * @method County[]    findAll()
 * @method County[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, County::class);
    }

    /**
     * @return County[] Returns an array of County objects
    */
    
    public function findCountyBy($query)
    {
        
        $qb = $this->createQueryBuilder('c');
        if(!empty($query)){            
            $qb->innerJoin('App\Entity\CityState', 'cs', 'WITH', 'c.id = cs.County');
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
    //  * @return County[] Returns an array of County objects
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
    public function findOneBySomeField($value): ?County
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
