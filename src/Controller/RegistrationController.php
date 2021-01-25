<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\ControlAuthenticator;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
    
        $this->emailVerifier = $emailVerifier;
    
    }

    /**
     * @Route("/inscription", name="app_register")
     */
    public function appRegister(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, ControlAuthenticator $authenticator): Response
    {

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $user->setPassword(
                $passwordEncoder->encodePassword(

                    $user,
                    $form->get('plainPassword')->getData()

                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // genère un lien url envoyé à l'utilisateur
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())

                    ->from(new Address('valordebene@gmail.com', 'emotionelles'))
                    ->to ($this->getParameter('ADMIN_EMAIL'))//($user->getEmail())

                    ->subject('Confirmez votre email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
           );

             $this->addFlash('success', 'Vous êtes inscrit entant que membre. Merci de confirmer votre lien inscription');



        }  

        return $this->render('registration/inscription.html.twig', [
        
            'registrationForm' => $form->createView(),
        
        ]);
    
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */ 
    public function verifyUserEmail(Request $request)
    {//validation de l email a partir d'un lien d'inscription dans le User  quand quelqu'un s'inscrit il verifie avec la function private dasn User.php
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        try
        {
        
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        
        }
        catch (VerifyEmailExceptionInterface $exception) 
        {
        
            $this->addFlash('verify_email_error', $exception->getReason());
        
        }

    }
    
}
