<?php

namespace App\Controller;

use App\Entity\NewsletterContact;
use App\Repository\NewsletterContactRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{
    /**
     * @Route("/newsletter/subscribe", name="newsletter_subscribe")
     */
    public function newsletterSubscribe(Request $request, NewsletterContactRepository $newsletterContactRepository)
    {

        if ($request->isMethod('POST')){
            
            $mail = $request->request->get('mail');

            $contactExist = $newsletterContactRepository->findOneBy(['mail'=>$mail]);

            if($contactExist === NULL){
                
                $contact = new NewsletterContact();
                $contact->setMail($mail);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();

                $this->addFlash('success', "Bravo, vous êtes inscrit ! Merci");

            }
            
            else
            {
                $this->addFlash('info', "Zut, vous êtes déjà inscrit !");
            }

            return $this->redirect($request->headers->get("referer"));
        }
    }
}
