<?php

namespace App\Controller;

use App\Repository\ActualiteRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageadminController extends AbstractController
{
    /**
     * @Route("/admin", name="page_admin")
    */
    public function adminActualites(ActualiteRepository $actualiteRepository): Response
    {
        $actualites = $actualiteRepository->findAll();

        return $this->render('pageadmin/page_admin.html.twig', [
            "actualites"=>$actualites,
        ]);
    }
     /**
     * @Route("/admin/listereservation", name="listereservation")
    */
    public function listereservation(Request $request, ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findAll();
            


        return $this->render('pageadmin/listereservation.html.twig', [
            "reservations"=>$reservations,
        ]);
        
    }
    

}


