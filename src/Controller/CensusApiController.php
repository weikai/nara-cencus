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
use Symfony\Component\Dotenv\Dotenv;
use IIIF\PresentationAPI\Resources\Manifest;
use IIIF\PresentationAPI\Resources\Sequence;
use IIIF\PresentationAPI\Resources\Canvas;

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
        
        
        $data = [];               
        $params=$this->getQuery($request, array('state','county','city'));
        $query = $params['query'];
        $state = $county = $city = [];

        if($location == 'state' || $location == 'location'){
            $stateRepository = $this->entityManager->getRepository(State::class);
            $squery = array_merge(array(),$query);
            unset($squery['state']);
            $results = $stateRepository->findStateBy($squery);   
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
            
            $cquery = array_merge(array(),$query);
            unset($cquery['county']);
            $countyRepository = $this->entityManager->getRepository(County::class);
            $results = $countyRepository->findCountyBy($cquery);   
            foreach ($results as $result) {                    
                $county[] = $result->getName();
            }
            if(!empty($county)){
                $data['county'] = $county;
            }
        }
        if($location == 'city' || $location == 'location'){    
            $cquery = array_merge(array(),$query);
            unset($cquery['city']);
            $cityRepository = $this->entityManager->getRepository(City::class);        
            $results = $cityRepository->findCityBy($cquery);   
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
        
        

        $query=[];   
        $data = [];       
        $images=[];
        $params=$this->getQuery($request, array('state','county','city','ed'));

        $censusImageRepository = $this->entityManager->getRepository(CensusImage::class);
        $results = $censusImageRepository->findCensusImageBy($params);   



        $manifest = new Manifest(true);
        $manifest->setID("http://example.org/iiif/book1/manifest");
            

        $thumbnail = new \IIIF\PresentationAPI\Properties\Thumbnail();
        $manifest->addThumbnail($thumbnail);
        
        
        

        $sequence = new Sequence();
        $manifest->addSequence($sequence);
        $sequence->setID("http://example.org/iiif/book1/sequence/normal");
        //$sequence->addLabel("Current Page Order");
        $sequence->addLabel("Normal Sequence", "en");
      
        
        $i=0;
        foreach ($results as $result) {
            $i++;
            $imgpath=$_ENV['CENSUS_IIIF_ENDPOINT'] . $_ENV['PREFIX1940'] . '%2F' . 
            "{$result['Abbr']}%2Fm-t0627" . 
             "-{$result['rollnum']}%2F" . str_replace('m-t1224','m-t0627',$result['filename']);
            
             //set manifest thumbnail image to be the first one in the ED list
            if($i == 1){
                $manifest->addLabel("ED " . str_replace('_','-',$result['ed']));
                $thumbnail->setID("{$imgpath}}/full/80,100/0/default.jpg");
            }
            

            $canvas = new Canvas();
            $sequence->addCanvas($canvas);
            $canvas->setID("{$imgpath}/full/full/0/default.jpg");
            $canvas->addLabel("p. $i");
            $canvas->setWidth(800);
            $canvas->setHeight(600);


            $service_thumbnail = new \IIIF\PresentationAPI\Links\Service();
            $thumbnail->setService($service_thumbnail);
            $service_thumbnail->setContext("http://iiif.io/api/image/2/context.json");
            $service_thumbnail->setID($imgpath);
            $service_thumbnail->setProfile("http://iiif.io/api/image/2/level1.json");
            
            $content = new \IIIF\PresentationAPI\Resources\Content();            
            $content->setId("{$imgpath}/full/full/0/default.jpg");
            $content->setType("dctypes:Image");
            $content->setFormat("image/jpeg");
            $content->addService($service_thumbnail);


            $annotation = new \IIIF\PresentationAPI\Resources\Annotation();
            $annotation->setContent($content);
            $annotation->setOn('on');
            


            $canvas->addImage($annotation);


            
            /*
            $images[] = [
                'id' => $result->getId(),                
                'filename' => $result->getFilename(),
                'state' => $result->getState()
            ];
            */
        }
        $data['images'] = $images;
        return (new JsonResponse($manifest->toArray(), Response::HTTP_OK))->setEncodingOptions( JSON_PRETTY_PRINT );
    }

    

    /**
     * @Route("/search/{searchterm}", defaults={"searchterm" = null}, name="api_search", methods={"GET"})
    */
    public function search(Request $request, $searchterm): JsonResponse
    {   
        $params=$this->getQuery($request, array('size','page','state','county','city','ed'),
        array(
            'state'=>'stateabbr',
            'county'=>'countyname',
            'city'=>'cityname'
        ));
        
        
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
            $edSummaryRepository = $this->entityManager->getRepository(EdSummary::class);
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
    private function getQuery($request, $queryList = array(), $replacement=array() ){
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
                case 'ed':
                    if(!empty($value = $request->query->get($query))){                        
                        $value = str_replace('-','_',$value);                        
                        $params['query'][empty($replacement[$query]) ? $query : $replacement[$query]] = $value;
                    } 
                    break;
                case 'county':
                        if(!empty($value = $request->query->get($query))){                                      
                            $params['query'][empty($replacement[$query]) ? $query : $replacement[$query]] = $value;
                        } 
                        break;
                default:
                    if(!empty($value = $request->query->get($query))){                        
                        $params['query'][empty($replacement[$query]) ? $query : $replacement[$query]] = $value;
                    } 
                    break;

            }
        }
        return $params;

    }
}
