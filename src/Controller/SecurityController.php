<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function appLogin(AuthenticationUtils $authenticationUtils): Response
    {    
        if ($this->getUser()) 
        // Si l'utilisateur est connecte on peut le redigirer sur une page au choix
        {
            
            return $this->redirectToRoute('accueil');
        
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        // get the login error if there is one

        $lastUsername = $authenticationUtils->getLastUsername();
        // last username entered by the user

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function appLogout() {}

}
