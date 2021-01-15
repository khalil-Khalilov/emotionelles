<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageadminController extends AbstractController
{
    /**
     * @Route("/admin", name="page_admin")
     */
    public function index(): Response
    {
        return $this->render('pageadmin/admin.html.twig', [
            'controller_name' => 'PageadminController',
        ]);
    }
}
