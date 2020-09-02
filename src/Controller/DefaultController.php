<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
         // redirects to the "homepage" route
        return $this->redirectToRoute('search_page');
    }
    /**
     *  @Route("/search/{reactRouting}", name="search_page", defaults={"reactRouting": null})
    */
    public function searchPage()
    {
        return $this->render('default/index.html.twig');
    }
}
