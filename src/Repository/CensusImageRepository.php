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
    
    public function findCensusImageBy()
    {
        return $this->createQueryBuilder('img')
            //->andWhere('m.exampleField = :val')
            //->setParameter('val', $value)
            ->orderBy('img.id', 'ASC')
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
