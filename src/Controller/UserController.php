<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/espace-utilisateur", name="espace_utilisateur")
     */
    public function espaceUtilisateur(): Response
    {
    
        return $this->render('user/espace_utilisateur.html.twig', [
    
            'controller_name' => 'UserController',
    
        ]);
    
    }
}
