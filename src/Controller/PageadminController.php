<?php

namespace App\Controller;

use App\Repository\ActualiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
