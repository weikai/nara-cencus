<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\State;
use App\Entity\City;
use App\Entity\CityState;
use App\Entity\County;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;


/**
 * Class CensusApiController
 * @package App\Controller
 *
 * @Route(path="/api")
 */
class CensusApiController extends AbstractController
{   private $em;
    private $stateRepository;
    private $cityRepository;
    private $cityStateRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->stateRepository = $entityManager->getRepository(State::class);
        $this->cityRepository = $entityManager->getRepository(City::class);
        $this->cityStateRepository = $entityManager->getRepository(CityState::class);
        $this->countyRepository = $entityManager->getRepository(County::class);
    }
    
    /**
     * @Route("/get-states", name="get_all_states", methods={"GET"})
     */
    public function getAllStates(): JsonResponse
    {
        $states = $this->stateRepository->findAll();

        $data = [];
        foreach ($states as $state) {
            $data[] = [
                'id' => $state->getId(),
                'name' => $state->getName(),
                'abbr' => $state->getAbbr(),
            ];
        }
        /*
        ->createQueryBuilder('e')
        ->addOrderBy('e.time', 'ASC')
        ->getQuery()
        ->execute();
        */
        
        return (new JsonResponse(['states' => $data], Response::HTTP_OK))->setEncodingOptions( JSON_PRETTY_PRINT );
    }

    /**
     * @Route("/get-state/{id}", name="get_one_state", methods={"GET"})
     */
    public function getOneState($id): JsonResponse
    {
        $state = $this->stateRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $state->getId(),
            'name' => $state->getName(),
            'abbr' => $state->getAbbr(),
        ];

        return (new JsonResponse(['state' => $data], Response::HTTP_OK))->setEncodingOptions( JSON_PRETTY_PRINT );
    }

    /**
     * @Route("/get-cities/{id}", defaults={"id"=""}, name="get_cities", methods={"GET"})
     */
    public function getCities($id): JsonResponse
    {
        $data = [];
        if(empty($id)){
            $cities = $this->cityRepository->findAll();
            
            foreach ($cities as $city) {
                $data[] = [
                    'id' => $city->getId(),
                    'name'=> $city->getName(),
                ];
            }            
        }
        else{
            
            $cities = $this->em->createQueryBuilder('c')
            ->addSelect('c')
            ->from('App\Entity\City','c')            
            ->innerJoin('App\Entity\CityState', 'cs')            
            ->where('cs.state = :id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();


            foreach ($cities as $city) {
                
                $data[] = [
                    'id' => $city->getId(),              
                    'name'=> $city->getName()
                ];                
            }
            
        }        
        return (new JsonResponse(['cities' => $data], Response::HTTP_OK))->setEncodingOptions( JSON_PRETTY_PRINT );
    }

    /**
     * @Route("/get-counties/{id}", defaults={"id"=""}, name="get_counties", methods={"GET"})
     */
    public function getCounties($id): JsonResponse
    {
        $data = [];
        if(empty($id)){
            $counties = $this->countyRepository->findAll();
            
            foreach ($counties as $county) {
                $data[] = [
                    'id' => $county->getId(),
                    'name'=> $county->getName(),
                ];
            }            
        }
        else{
            
            $counties = $this->em->createQueryBuilder('c')
            ->addSelect('c')
            ->from('App\Entity\County','c')            
            ->innerJoin('App\Entity\CityState', 'cs')            
            ->where('cs.state = :id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();


            foreach ($counties as $county) {
                
                $data[] = [
                    'id' => $county->getId(),              
                    'name'=> $county->getName()
                ];                
            }
            
        }        
        return (new JsonResponse(['cities' => $data], Response::HTTP_OK))->setEncodingOptions( JSON_PRETTY_PRINT );
    }

    /**
     * @Route("/search/{query}/{limit}/{page}", defaults={"query" = null, "page" = 0, "limit" = 50}, name="search", methods={"GET"})
    */
    public function search($query, $page, $limit): JsonResponse
    {
        $data = [];
        if(empty($query)){
            $RAW_QUERY = "SELECT concat(cs.id,ed.id) id, state.name state, county.name county, city.name city, ed.ed, ed.description
                          FROM city_state cs
                          JOIN state ON state.id = cs.state_id
                          JOIN county ON county.id = county_id
                          JOIN city ON city.id = cs.city_id
                          JOIN ed_summary ed ON ed.state_id = state.id AND ed.county_id = county.id                          
                          GROUP BY sortkey
                          ORDER BY sortkey
                          LIMIT :limit                          
                          OFFSET :offset
                          ";
        
            $statement = $this->em->getConnection()->prepare($RAW_QUERY);
            // Set parameters             
            $statement->bindValue('limit', $limit, \PDO::PARAM_INT);            
            $statement->bindValue('offset', $page * $limit, \PDO::PARAM_INT);
        }
        else{

            $RAW_QUERY = "SELECT concat(cs.id,ed.id) id, state.name state, county.name county, city.name city, ed.ed, ed.description
                        FROM city_state cs
                        JOIN state ON state.id = cs.state_id
                        JOIN county ON county.id = county_id
                        JOIN city ON city.id = cs.city_id
                        JOIN ed_summary ed ON ed.state_id = state.id AND ed.county_id = county.id                          
                        WHERE CONCAT(state.name,' ', city.name, ' ', ed.description, ' ', ed.ed) REGEXP :query
                        GROUP BY sortkey
                        ORDER BY sortkey
                        LIMIT :limit                          
                        OFFSET :offset
                          ";
        
            $statement = $this->em->getConnection()->prepare($RAW_QUERY);
            // Set parameters 
            $statement->bindValue('query', $query, \PDO::PARAM_STR);
            $statement->bindValue('limit', $limit, \PDO::PARAM_INT);            
            $statement->bindValue('offset', $page * $limit, \PDO::PARAM_INT);
            
        }
        $statement->execute();
        $results = $statement->fetchAll();
        

        foreach ($results as $result) {      
            
            $data[] = $result;                    
        }
        
                
        return (new JsonResponse(['page'=>$page+1, 'limit'=> $limit, 'results' => $data], Response::HTTP_OK))->setEncodingOptions( JSON_PRETTY_PRINT );
    }

}
