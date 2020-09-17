<?php

namespace App\Repository;

use App\Entity\CensusImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CensusImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CensusImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CensusImage[]    findAll()
 * @method CensusImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CensusImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CensusImage::class);
    }

    /**
    * @return CensusImage[] Returns an array of CensusImage objects
    */
    
    public function findCensusImageBy($params)
    {
        
        if(empty($params['query']['ed']) || empty($params['query']['state'])){
            return array();
        }        
        $query = $params['query'];
        

        $queryBuilder = $this->createQueryBuilder('img')
            ->leftJoin('App\Entity\Enumeration', 'enumer', 'WITH', 'enumer.id = img.enum')
            ->leftJoin('App\Entity\EdSummary', 'ed', 'WITH', 'enumer.ed = ed.id')
            ->leftJoin('App\Entity\State', 'state', 'WITH', 'state.id = img.state')
            ->select('state.Name AS statename','state.Abbr as abbr','img.filename','ed.ed','img.publication','img.rollnum');
        
        
        foreach($query as $key=>$value){ 
            switch($key){
                case 'state':
                    $queryBuilder->andWhere("state.Abbr = :$key");
                    break;
                case 'county':
                    $queryBuilder->leftJoin('App\Entity\County', 'county', 'WITH', 'county.id = img.county')
                    ->andWhere("ed.county = :$key");
                    break;
                case 'city':
                    $queryBuilder->leftJoin('App\Entity\City', 'city', 'WITH', 'city.id = img.city')
                    ->andWhere("ed.city = :$key");
                    break;
                case 'ed':
                    $queryBuilder->andWhere("ed.ed = :$key");
                    break;
                case 'type':
                    $queryBuilder->andWhere("img.type = :$key");
                    break;
                
                
            }
            $queryBuilder->setParameter($key, $value);
        }
        
         
        return $queryBuilder->orderBy('img.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    SELECT state.name state, county.name county, city.name city, ed.ed, cimg.filename
FROM census_image cimg
LEFT JOIN enumeration enumer ON cimg.enum_id = enumer.id
LEFT JOIN ed_summary ed ON enumer.ed_id = ed.id
LEFT JOIN state ON state.id = cimg.state_id
LEFT JOIN county ON county.id = cimg.county_id
LEFT JOIN city ON city.id = cimg.city_id
WHERE ed.ed ='3_1' AND state.abbr='AL'
*/

    // /**
    //  * @return CensusImage[] Returns an array of CensusImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CensusImage
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
