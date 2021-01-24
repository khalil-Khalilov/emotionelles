<?php

namespace App\Controller;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(EmailService $emailService, Request $request): Response
    //cette fonction sera transmise et traitée dans le document/fichier service.

    {  
       
        $method = $request->getMethod();
        
        if ($request->isMethod('POST')){
            
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $numero = $request->request->get('numero');
            $email = $request->request->get('email');
            $message = $request->request->get('message');
       
            $emailService->send([
            //une variable $emailService en forme de tableau transmet les informations à Emailservice
            
                'replyTo' => $email,
                'message' => $message,
                'subject' => "Vous avez un message - Emotion'elles",
                'template' => 'email/contact.email.twig',
                'context' => [
                    'nom'=> $nom,
                    'prenom'=> $prenom,
                    'numero'=> $numero,
                    'mail'=> $email,
                    'message'=> $message
            ]
            // nous pouvons rajouter des lignes supplémentaires si besoin dans le tableau
        
        ]); 

        $emailService->send([
        // Email de confirmation client

            'to' => $email,
            'subject' => "Nous avons bien reçu votre message - Emotion'elles",
            'template' => 'email/contact_confirmation.email.twig',
            'context' => [
                'nom'=> $nom,
                'prenom'=> $prenom,
                'numero'=> $numero,
                'mail'=> $email,
                'message'=> $message
            ]
            
        ]);

            $this->addFlash('success', "Nous avons bien reçu votre message.");
            //message flash pour signifier que le message a été envoyé.
 
            return $this->redirectToRoute('contact');
        
        }
    
        return $this->render('contact/contact.html.twig', []);
    
    }
}
