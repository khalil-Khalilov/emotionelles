<?php

namespace App\Controller;

use Container7uo7xSm\getValidator_EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(getValidator_EmailService): Response
    {
        return $this->render('contact/contact.html.twig', [
            
        ]);
    }
}
