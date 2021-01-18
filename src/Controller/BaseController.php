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

    public function header(): Response
    {
        return $this->render('base/header.html.twig');
    }



    //Nous creons une nouvelles route pour la session d acces au role. elle permet de renvoyer le type d'utilisateur vers son espace correspondant
    //si l'acces est authentifié au role admin dans ce cas, nous le renvoyons vers l'espace admin .aussi si le role utilisateur est authentifié
    //il sera redirigé vers l'accueil 
       /**
     * @Route("/acces-compte", name="acces_compte")
     */
    public function accesCompte(): Response
    {
           // dd($this->getUser());
        if($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('page_admin');

        } elseif ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('espace_utilisateur');
        }else{
            return $this->redirectToRoute('accueil');

        }
        
    }
  
}

