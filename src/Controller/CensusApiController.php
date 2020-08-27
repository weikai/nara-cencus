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

}
