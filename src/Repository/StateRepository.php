<?php

namespace App\Repository;

use App\Entity\State;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method State|null find($id, $lockMode = null, $lockVersion = null)
 * @method State|null findOneBy(array $criteria, array $orderBy = null)
 * @method State[]    findAll()
 * @method State[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, State::class);
    }

    /**
     * @return State[] Returns an array of State objects
    */
    
    public function findStateBy($query)
    {
        $qb = $this->createQueryBuilder('s');
        if(!empty($query)){
            $qb->innerJoin('App\Entity\CityState', 'cs', 'WITH', 's.id = cs.state');
            foreach($query as $key => $value){
                $ukey = ucfirst($key);
                $qb->innerJoin("App\Entity\\$ukey" , $key, 'WITH', "cs.$ukey = $key.id")
                ->andWhere("$key.Name = :$key")
                ->setParameter($key, $value);
            }
        }
            
        $qb->orderBy('s.Name', 'ASC');
        return $qb->getQuery()->getResult();        
    }
    

    
    // /**
    //  * @return State[] Returns an array of State objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?State
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
