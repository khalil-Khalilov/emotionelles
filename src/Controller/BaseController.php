<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
   
    /**
     * @Route("/", name="accueil")
     */
    public function index(): Response
    {

        return $this->render('base/accueil.html.twig');
    }

    /**
     * @Route("/a-propos", name="apropos")
     */
    public function apropos(): Response
    {
        return $this->render('base/apropos.html.twig');
    }

    public function header(): Response
    {
        return $this->render('base/header.html.twig');
    }

}

