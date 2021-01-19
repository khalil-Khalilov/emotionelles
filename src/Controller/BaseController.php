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
    public function accueil(): Response
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
    
    ///////////////////////////////////////////////////////////////////

    public function header(): Response
    {
        return $this->render('base/header.html.twig');
    }

    ///////////////////////////////////////////////////////////////////

    /**
     * @Route("/acces-compte", name="acces_compte")
     */
    public function accesCompte(): Response
    {
        # Nous créons une nouvelles route pour la session d'accès aux rôles (Administatuer / utilisateur). Elle permet de renvoyer le type d'utilisateur vers son espace correspondant si l'accès est authentifié au rôle admin dans ce cas, nous le renvoyons vers l'espace admin. Aussi si le rôle utilisateur est authentifié il sera redirigé vers l'accueil #

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

