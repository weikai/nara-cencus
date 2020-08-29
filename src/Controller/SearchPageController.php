<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchPageController extends AbstractController
{
    /**
     * @Route("/search", name="search_page")
     */
    public function index()
    {
        return $this->render('search_page/index.html.twig', [
            'controller_name' => 'SearchPageController',
        ]);
    }
}
