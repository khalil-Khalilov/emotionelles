<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RealisationsController extends AbstractController
{
    /**
     * @Route("/realisations", name="realisations")
     */
    public function index(): Response
    {
        return $this->render('realisations/index.html.twig', [
            'controller_name' => 'RealisationsController',
        ]);
    }
    /**
     * @Route("/produits/", name="produits")
     */
    public function produits(): Response
    {
        return $this->render('realisations/produits.html.twig',);
    }
}
