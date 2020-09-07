<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\State;
use App\Entity\City;
use App\Entity\County;
use App\Entity\EdSummary;
use App\Entity\CensusImage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class CensusApiController
 * @package App\Controller
 *
 * @Route(path="/api")
 */
class CensusApiController extends AbstractController
{   private $entityManager;
   

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;        
        
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

        $state = $county = $city = [];

        if($location == 'state' || $location == 'location'){
            $stateRepository = $this->entityManager->getRepository(State::class);
            $results = $stateRepository->findStateBy($query);   
            foreach ($results as $result) {
                $state[] = [
                    'abbr' => $result->getAbbr(),
                    'name' => $result->getName(),                
                ];
            }
            if(!empty($state)){
                $data['state'] = $state;
            }
        }
        if($location == 'county' || $location == 'location'){  
            
        
            $countyRepository = $this->entityManager->getRepository(County::class);
            $results = $countyRepository->findCountyBy($query);   
            foreach ($results as $result) {                    
                $county[] = $result->getName();
            }
            if(!empty($county)){
                $data['county'] = $county;
            }
        }
        if($location == 'city' || $location == 'location'){    
            $cityRepository = $this->entityManager->getRepository(City::class);        
            $results = $cityRepository->findCityBy($query);   
            foreach ($results as $result) {
                $city[] = $result->getName();
            }
            if(!empty($city)){
                $data['city'] = $city;        
            }
        }
        return (new JsonResponse($data, Response::HTTP_OK))->setEncodingOptions( JSON_PRETTY_PRINT );
    }

    

    /**
     * @Route("/manifest", name="manifest", methods={"GET"})
    */
    public function manifest(Request $request): JsonResponse
    {
        $censusImageRepository = $this->entityManager->getRepository(CensusImage::class);
        $results = $censusImageRepository->findCensusImageBy();   
        $data=[];
        foreach ($results as $result) {
            $images[] = [
                'id' => $result->getId(),                
                'filename' => $result->getFilename()
            ];
        }
        $data['images'] = $images;
        return (new JsonResponse($data, Response::HTTP_OK))->setEncodingOptions( JSON_PRETTY_PRINT );
    }

    /**
     * @Route("/search/{searchterm}", defaults={"searchterm" = null}, name="api_search", methods={"GET"})
    */
    public function search(Request $request, $searchterm): JsonResponse
    {   
        $params=$this->getQuery($request, array('size','page','state','county','city','ed'));
        $data=[];        
        $total=0;
        if(!empty($searchterm)){            
            $rquery = array();            
            foreach(preg_split("/[\s,\/]+/",$searchterm) as $qword){
                if(!empty($qword)){
                    $qword = preg_replace('/(\d+)-(\d+)/',"$1_$2", $qword); //convert hyphen between numbers to underscore for ED number
                    $rquery[] = "+$qword";
                }
            }
            $params['searchterm'] = implode(' ', $rquery);            
        }   
        if(!empty($searchterm) || !empty($params['query'])){
            $edSummaryRepository = $entityManager->getRepository(EdSummary::class);
            $paginator = $edSummaryRepository->findEdSummaryBy($params);  
            $totalItems = count($paginator);
            $total = count($paginator);
            //$pagesCount = ceil($totalItems / $pageSize);
            foreach ($paginator as $pageItem) {            
                $data[] = [
                    'id' => $pageItem->getId(), 
                    'state_name' => $pageItem->getStateName(), 
                    'state_abbr' => $pageItem->getStateAbbr(),
                    'county' => $pageItem->getCountyName(),
                    'city' => $pageItem->getCityName(),
                    'ed' => str_replace('_','-',$pageItem->getEd()), //convert underscore between numbers to hyphen for ED number
                    'description' => $pageItem->getDescription(),
                ];
            }
        }
        
        return (new JsonResponse(
            [
            'page'=>$params['page'], 
            'size'=>$params['size'],
            'total' =>  $total,
            'results' => $data
            ], Response::HTTP_OK))->setEncodingOptions( JSON_PRETTY_PRINT );
        
    }
    private function getQuery($request, $queryList = array() ){
        $params=array(
            'size' => 25,
            'page' => 1,
            'query'=>[],
        );

        if(empty($queryList)){
            return $params;
        }
        foreach($queryList as $query){
            switch($query){
                case 'size':
                    if( !empty($size = $request->query->get('size'))){
                        $params['size'] = $size <= 200 ? $size : 25;                                    
                    }
                    break;
                case 'page':
                    if(!empty($page = $request->query->get('page'))){
                        $params['page'] = $page > 0? $page : 1;
                    }
                    break;
                case 'state':
                    if(!empty($value = $request->query->get($query))){
                        $params['query']['stateabbr'] = $value;
                    } 
                    break;
                default:
                    if(!empty($value = $request->query->get($query))){
                        if($query == 'ed'){
                            $value = str_replace('-','_',$value);
                        }
                        $params['query'][$query] = $value;
                    } 
                    break;

            }
        }
        return $params;

    }
}
