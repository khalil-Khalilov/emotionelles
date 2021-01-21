<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MotdepasseController extends AbstractController
 {
    /**
     * @Route("/mot-de-passe-oublie", name="motdepasse_oublie")
     */
    public function motdepasse(Request $request, UserRepository $userRepository, EmailService $emailService): Response
    {
        if($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneByEmail($email);
           if ($user) {

            $lien = $this->generateUrl('nouveau_passe',[], UrlGeneratorInterface::ABSOLUTE_URL);
                //dd($lien);

              $emailService->send([
                  'to' => $user->getEmail(),
                  'subject' => "Reinitialisation email" ,
                  'template' => "email/envoipasse_oublie.html.twig",
                  'context'=>[
                      'lien'=>$lien,
                      'user'=> $user,
                  ] ,

             ]);

           }
           $this->addFlash('success', "Vous recevrez un message si votre compte existe.");
           return $this->redirectToRoute('motdepasse_oublie');
        
        }
        return $this->render('motdepasse/motdepasse_oublie.html.twig', [
            
        ]);
    }

    /**
     * @Route("/reinitialiser", name="nouveau_passe")
     */
    public function envoipasseoublie() {

        return $this->render('reinitialiser/nouveau_passe.html.twig',[

        ]);
    }
}