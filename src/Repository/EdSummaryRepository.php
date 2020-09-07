<?php

namespace App\Repository;

use App\Entity\EdSummary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;


/**
 * @method EdSummary|null find($id, $lockMode = null, $lockVersion = null)
 * @method EdSummary|null findOneBy(array $criteria, array $orderBy = null)
 * @method EdSummary[]    findAll()
 * @method EdSummary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EdSummaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EdSummary::class);
    }

    // /**
    //  * @return EdSummary[] Returns an array of EdSummary objects
    //  */
    
    public function FindEdSummaryBy($params=['max'=>25]) 
    {
        $limit = !empty($params['size']) && $params['size'] < 1000? $params['size'] : 1000;
        $page = !empty($params['page']) && is_numeric($params['page'])  && $params['page'] > 0 ? $params['page'] : 1;
        $offset = $limit * ($page - 1);
        $queries = $params['query'];

        $alterFieldNameList=array('state','county','city');

        $queryBuilder = $this->createQueryBuilder('e');
            //->andWhere('e.exampleField = :val')
            //->setParameter('val', $value)
        if(!empty($params['searchterm'])){
            $queryBuilder->where('MATCH_AGAINST(e.ed,e.description,e.statename,e.stateabbr,e.countyname,e.cityname) AGAINST(:searchterm boolean)>0')
            ->setParameter('searchterm', $params['searchterm']);
        }
        foreach($queries as $key=>$value){
            if(in_array($key,$alterFieldNameList)){
                $key .='name';
            }            
            $queryBuilder->andWhere("e.$key = :$key")
            ->setParameter($key, $value);
        }
        
        $queryBuilder->orderBy('e.sortkey', 'ASC');
        
        $query = $queryBuilder->getQuery();//->getResult();
        // load doctrine Paginator
        $paginator = new Paginator($query);                
        $paginator
        ->getQuery()
        ->setFirstResult($offset)
        ->setMaxResults($limit); 
        
        return $paginator;
    }
    

    // /**
    //  * @return EdSummary[] Returns an array of EdSummary objects
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
    public function findOneBySomeField($value): ?EdSummary
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
