<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Entity\Comment;
use App\Form\ActualiteType;
use App\Form\CommentType;
use App\Repository\ActualiteRepository;
use App\Repository\CommentRepository;
use App\Repository\NewsletterContactRepository;
use App\Service\EmailService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActualiteController extends AbstractController
{
    /**
     * @Route("/actualites", name="actualites")
    */
    public function actualites(ActualiteRepository $actualiteRepository): Response
    {
        $actualites = $actualiteRepository->findAll();

        return $this->render('actualite/actualites.html.twig', [
            "actualites" => $actualites,
        ]);
    }


    /**
     * @Route("/actualite/{id}", name="actualite", requirements={"id":"\d+"})
    */
    public function actualite(Request $request, ActualiteRepository $actualiteRepository, $id): Response
    {

        
        $actualite = $actualiteRepository->find($id);

        $comment = new Comment;
        
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            

            if($form->isValid()){

                $comment
                    ->setCreatedAt(new DateTime())
                    ->setUser($this->getUser())
                    ->setActualite($actualite);

                // dd($comment);

                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();
                
                $this->addFlash('success', "Comment is add.");

                return $this->redirectToRoute('actualite',["id"=>$actualite->getId()]);
            }
            else 
            {
                $this->addFlash('danger', "Comment not add.");
            }
        }

        return $this->render('actualite/actualite.html.twig', [
            "actualite"=>$actualite,
            "form" => $form->createView(),
        ]);
    }


    /**
     * @Route("admin/back", name="actualiteBack")
    */
    public function actualiteBack(EmailService $emailService ,NewsletterContactRepository $newsletterContactRepository, Request $request): Response
    {

        $actualite = new Actualite();

        $form = $this->createForm(ActualiteType::class, $actualite);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            
            if($form->isValid()){

                $new = $actualite->getId() === NULL;

                $em = $this->getDoctrine()->getManager();
                $em->persist($actualite);
                $em->flush();
                
                $this->addFlash('success', "L'article a bien été crée.");

                if($new){

                    $contacts = $newsletterContactRepository->findAll();

                    foreach($contacts as $contact){

                        $emailService->send([

                            'to' => $contact->getMail(),
                            'subject' => "Nouvelle actualité - Emotion'elles",
                            'template' => 'email/newsletter_actualite.email.twig',
                            'context' => [
                                "actualite" => $actualite,
                                "contact" => $contact
                            ]
                            
                        ]);
                    }
                }
                
                return $this->redirectToRoute('actualites');
            }
            else 
            {
                $this->addFlash('danger', "Le formulaire comporte des erreurs.");
            }
        }



        return $this->render('actualite/actualiteBack.html.twig', [
            "form" => $form->createView(),
            "actualite" => $actualite,
        ]);
    }
    

    /**
     * @Route("admin/modifierActualite/{id}", name="modifierActualite", requirements={"id":"\d+"})
    */
    public function modifierActualite(Request $request, ActualiteRepository $actualiteRepository, $id): Response
    {

        $actualite = $actualiteRepository->find($id);

        $form = $this->createForm(ActualiteType::class, $actualite);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){

                $em = $this->getDoctrine()->getManager();
                $em->persist($actualite);
                $em->flush();
                
                $this->addFlash('success', "L'article a bien été modifié.");
                return $this->redirectToRoute('actualites');
            }else {
                $this->addFlash('danger', "Le formulaire comporte des erreurs.");
            }
        }

        return $this->render('actualite/modifierActualite.html.twig', [
            "form" => $form->createView(),
            "actualite" => $actualite,
        ]);
    }

    /**
     * @Route("/admin/supprimerActualite/{id}", name="supprimerActualite")
    */
    public function deleteActualite(Actualite $actualite)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($actualite);
        $em->flush();

        $this->addFlash('success', "L'article a bien été supprimé.");
        return $this->redirectToRoute('actualites');
    }

    /////////////////////////////////////////////////////////////////////////

    /**
     * @Route("/modifierCommentaire/{id}", name="modifierCommentaire", requirements={"id":"\d+"})
    */
    public function modifierCommentaire(ActualiteRepository $actualiteRepository, Request $request, CommentRepository $commentRepository, $id): Response
    {

        $comment = $commentRepository->find($id);

        $actualite = $actualiteRepository->find($id);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){

                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();
                
                $this->addFlash('success', "Le commentaire a bien été modifié.");

                // return $this->redirectToRoute('actualite',["id"=>$actualite->getId()]);
                return $this->redirectToRoute('actualites');
            }else {
                $this->addFlash('danger', "Le formulaire comporte des erreurs.");
            }
        }

        return $this->render('actualite/modifierCommentaire.html.twig', [
            "form" => $form->createView(),
            "comment" => $comment,
        ]);
    }

    /**
     * @Route("supprimerCommentaire/{id}", name="supprimerCommentaire")
    */
    public function deleteCommentaire(ActualiteRepository $actualiteRepository, Comment $comment, $id)
    {
        $actualite = $actualiteRepository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        $this->addFlash('success', "Le commentaire a bien été supprimé.");

        return $this->redirectToRoute('actualites');
        // return $this->redirectToRoute('actualite',["id"=>$actualite->getId()]);
    }
}
