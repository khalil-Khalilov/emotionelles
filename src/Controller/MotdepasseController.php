<?php

namespace App\Controller;

use App\Form\ReinitialisationMpType;
use App\Repository\UserRepository;
use App\Service\EmailService;
use Nzo\UrlEncryptorBundle\Encryptor\Encryptor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Security\ControlAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MotdepasseController extends AbstractController
{
    /**
     * @Route("/mot-de-passe-oublie", name="motdepasse_oublie")
     */
    public function motdepasse(Request $request, UserRepository $userRepository, EmailService $emailService,Encryptor $encryptor ): Response
    {
        
        if($request->isMethod('POST')) 
        {

            $email = $request->request->get('email');
            $user = $userRepository->findOneByEmail($email);

            if ($user) {
                $token = $encryptor->encrypt('nouveau-passe$'.$user->getEmail());
                $lien = $this->generateUrl('nouveau_passe',[
                    'token' => $token
                ], UrlGeneratorInterface::ABSOLUTE_URL);
            
                //dd($token);

                $emailService->send([
                    'to' => $user->getEmail(),
                    'subject' => "Reinitialisation email" ,
                    'template' => "email/envoipasse_oublie.email.twig",
                    'context'=>[
                        'lien'=>$lien,
                        'user'=> $user,
                    ] ,

                ]);

            }
            
            $this->addFlash('success', "Vous recevrez un message si votre compte existe.");
            return $this->redirectToRoute('motdepasse_oublie');
        
        }
        
        return $this->render('motdepasse/motdepasse_oublie.html.twig', []);
    
    }

    /**
     * @Route("/mot-de-passe-oublie/reinitialiser/{token}", name="nouveau_passe")
     */
    public function envoipasseoublie($token, Encryptor $encryptor, UserRepository $userRepository, Request $request,UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, ControlAuthenticator $authenticator) 
    {
        $decrypt = $encryptor->decrypt($token);
        $pos = strpos($decrypt, 'nouveau-passe$');
        $email = str_replace('nouveau-passe$','', $decrypt);
        $user = $userRepository->findOneByEmail($email);
          //dd($pos);
        

        if ($pos !== 0 || !$user){
            throw new AccessDeniedException();

        }
        $form = $this->createForm(ReinitialisationMpType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            //dd($user); un dump pour verfier la function sur la page de reintialisation : eventuel bug.
              //dd($passwordEncoder->encodePassword(
                  //  $user,
                  //$form->get('plainPassword')->getData()
              //)); : nous faisons un dump sur la function pour verifier si l'encodage du nom User a été effectué

              //cette function nous permet d'encoder le mot de passe créé
             // $user->setPassword(
              //  $passwordEncoder->encodePassword(
               //     $user,
                 //   $form->get('password')->getData()
             //   )
          //  );
           // $entityManager = $this->getDoctrine()->getManager();
           // $entityManager->persist($user);
           // $entityManager->flush();

             // Permet d'afficher un message de confirmation apres changement du mot de passe
             $this->addFlash('success', 'Votre mot de passe a été reinitialisé');
             
                //FUNCTION Permettant à  de se connecter automatiquement
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

       
        return $this->render('motdepasse/nouveau_passe.html.twig',[
            'form' => $form->createView(),

        ]);

    }

}