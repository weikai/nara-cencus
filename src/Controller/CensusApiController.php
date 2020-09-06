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
        //$this->cityStateRepository = $entityManager->getRepository(CityState::class);
        $this->countyRepository = $entityManager->getRepository(County::class);
    }
    
    /**
     * @Route("/{location}", name="get_location", requirements={ "location": "location|state|county|city" }, methods={"GET"})
     */
    public function getLocation(Request $request, $location): JsonResponse    
    {
        
        $query=[];   
        $data = [];       
        if(!empty($request->query->get('city'))){
            $query['city'] = $request->query->get('city');
        }
        if(!empty($request->query->get('county'))){
            $query['county'] = $request->query->get('county') . ' County';
        }
        if(!empty($request->query->get('state'))){
            $query['state'] = $request->query->get('state');
        }

        $states = $counties = $cities = [];

        if($location == 'state' || $location == 'location'){
            
            $results = $this->stateRepository->findStateBy($query);   
            foreach ($results as $result) {
                $states[] = [
                    'value' => $result->getAbbr(),
                    'label' => $result->getName(),                
                ];
            }
            if(!empty($states)){
                $data['states'] = $states;
            }
        }
        if($location == 'county' || $location == 'location'){  
            
            $results = $this->countyRepository->findCountyBy($query);   
            foreach ($results as $result) {                    
                $counties[] = [  
                    'value' => $result->getName(),
                    'label' => $result->getName(),                
                ];
            }
            if(!empty($counties)){
                $data['counties'] = $counties;
            }
        }
        if($location == 'city' || $location == 'location'){    
            $results = $this->cityRepository->findCityBy($query);   
            foreach ($results as $result) {
                $cities[] = [    
                    'value' => $result->getName(),
                    'label' => $result->getName(),                
                ];
            }
            if(!empty($cities)){
                $data['cities'] = $cities;        
            }
        }

        
        
    
        
        
        return (new JsonResponse($data, Response::HTTP_OK))->setEncodingOptions( JSON_PRETTY_PRINT );
    }

    

    /**
     * @Route("/city", name="get_cities", methods={"GET"})
     */
    public function getCities(Request $request): JsonResponse
    {
        $state = $request->query->get('state');
        $county = $request->query->get('county');

        $data = [];
        if(empty($state) && empty($city)){
            $cities = $this->cityRepository->findAll();
            
            foreach ($cities as $city) {
                $data[] = [
                    'value' => $city->getId(),
                    'label'=> $city->getName(),
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
     * @Route("/search/{query}", defaults={"query" = null}, name="api_search", methods={"GET"})
    */
    public function search(Request $request, $query): JsonResponse
    {   
        $page = $request->query->get('page');
        $limit = $request->query->get('limit');

        $limit = ($limit && $limit <= 200) ? $limit : 25;
        $page = ($page && $page >= 1)? $page : 1;
                
        $data = [];
        $count = $total = 0;
        
        $results=array();
        if(!empty($query)){
            $dbconn=$this->em->getConnection();
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
                //Todo
            }
            
        }
        
        
        

        foreach ($results as $result) {      
            $result['ed'] = str_replace('_','-',$result['ed']);
            $data[] = $result;                                
        }
        
                
        return (new JsonResponse(['page'=>$page, 'limit'=> $limit, 'count'=>$count, 'total'=>$total, 'results' => $data], Response::HTTP_OK))->setEncodingOptions( JSON_PRETTY_PRINT );
    }

}
