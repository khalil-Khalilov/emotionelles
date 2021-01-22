<?php

namespace App\Controller;

use App\Repository\ActualiteRepository;
use App\Repository\RealisationsRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    // PARTIE ACCUEIL 
   
    /**
     * @Route("/", name="accueil")
     */
    public function accueil(ActualiteRepository $actualiteRepository, RealisationsRepository $realisationsRepository): Response
    {
        return $this->render('base/accueil.html.twig', [

            "lastactualites" => $actualiteRepository->findLastActualites(3),
            "lastrealisations" => $realisationsRepository->findLastRealisations(3)
        
        ]);
    
    }

    // PARTIE À PROPOS

    /**
     * @Route("/a-propos", name="apropos")
     */
    public function apropos(): Response
    {

        return $this->render('base/apropos.html.twig');
    
    }

    // PARTIE HEADER

    public function header(): Response
    {
    
        return $this->render('base/header.html.twig');
    
    }

    // PARTIE ACCES COMPTE

    /**
     * @Route("/acces-compte", name="acces_compte")
     */
    public function accesCompte(): Response
    {
        # Nous créons une nouvelles route pour la session d'accès aux rôles (Administateur / utilisateur). Elle permet de renvoyer le type d'utilisateur vers son espace correspondant si l'accès est authentifié au rôle admin dans ce cas, nous le renvoyons vers l'espace admin. Aussi si le rôle utilisateur est authentifié il sera redirigé vers l'accueil #

        if($this->isGranted('ROLE_ADMIN')) 
        {
            return $this->redirectToRoute('page_admin');
        } 
        
        elseif ($this->isGranted('ROLE_USER')) 
        {
            return $this->redirectToRoute('espace_utilisateur');
        }
        
        else
        {
            return $this->redirectToRoute('accueil');
        }
    
    }

}

