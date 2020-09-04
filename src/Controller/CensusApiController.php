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
     * @Route("/state", name="get_states", methods={"GET"})
     */
    public function getStates(Request $request): JsonResponse
    {
        $city = $request->query->get('city');
        $county = $request->query->get('county');
        
        
        $data = [];

        if(empty($city) && empty($county)){
            $states = $this->stateRepository->findAll();
            foreach ($states as $state) {
                $data[] = [
                    'value' => $state->getId(),
                    'label' => $state->getName(),                
                ];
            }
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
     * @Route("/search/{query}/{limit}/{page}", defaults={"query" = null, "page" = 0, "limit" = 50}, name="api_search", methods={"GET"})
    */
    public function search($query, $page, $limit): JsonResponse
    {
        $data = [];

        $dbconn=$this->em->getConnection();
        $limit = $limit > 200 ? 200:$limit;
        $page = $page < 1 ? 1: $page;
        
        
        if(!empty($query)){
            $rquery = array();            
            foreach(preg_split("/[\s,\/]+/",$query) as $qword){
                if(!empty($qword)){
                    $qword = preg_replace('/(\d+)-(\d+)/',"$1_$2", $qword);
                    $rquery[] = "+$qword";
                }
            }
            $query = implode(' ', $rquery);
            

            $RAW_QUERY = "SELECT SQL_CALC_FOUND_ROWS id, statename, countyname, cityname, ed, description
                        FROM census.ed_summary
                        WHERE MATCH(ed,description,statename,stateabbr,countyname,cityname)  AGAINST(:query IN BOOLEAN MODE)
                        LIMIT :limit                          
                        OFFSET :offset";

            $statement = $dbconn->prepare($RAW_QUERY);
            // Set parameters 
            $statement->bindValue('query', $query, \PDO::PARAM_STR);
            $statement->bindValue('limit', $limit, \PDO::PARAM_INT);            
            $statement->bindValue('offset', ($page - 1 ) * $limit, \PDO::PARAM_INT);
            try{
                $statement->execute();            
                $count = $statement->rowCount();
                $results = $statement->fetchAll();
                $total = $dbconn->query('SELECT FOUND_ROWS();')->fetch(\PDO::FETCH_COLUMN); 
            }
            catch(Exception $e){
                $results=array();
            }
            
        }
        
        
        

        foreach ($results as $result) {      
            $result['ed'] = str_replace('_','-',$result['ed']);
            $data[] = $result;                                
        }
        
                
        return (new JsonResponse(['page'=>$page, 'limit'=> $limit, 'count'=>$count, 'total'=>$total, 'results' => $data], Response::HTTP_OK))->setEncodingOptions( JSON_PRETTY_PRINT );
    }

}
