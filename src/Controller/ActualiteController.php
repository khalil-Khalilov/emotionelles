<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Entity\Comment;
use App\Form\ActualiteType;
use App\Form\CommentType;
use App\Repository\ActualiteRepository;
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
                
                $this->addFlash('success', "ok.");

                return $this->redirectToRoute('actualite',["id"=>$actualite->getId()]);
            }
            else 
            {
                $this->addFlash('danger', "PAS OK.");
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
    public function actualiteBack(Request $request): Response
    {

        $actualite = new Actualite();

        $form = $this->createForm(ActualiteType::class, $actualite);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            
            if($form->isValid()){

                $em = $this->getDoctrine()->getManager();
                $em->persist($actualite);
                $em->flush();
                
                $this->addFlash('success', "L'article a bien été crée.");
                
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
     * @Route("admin/modifier/{id}", name="modifier", requirements={"id":"\d+"})
    */
    public function modifier(Request $request, ActualiteRepository $actualiteRepository, $id): Response
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

        return $this->render('actualite/modifier.html.twig', [
            "form" => $form->createView(),
            "actualite" => $actualite,
        ]);
    }

    /**
     * @Route("/admin/supprimer/{id}", name="supprimerActualite")
    */
    public function deleteBlogArticle(Actualite $actualite)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($actualite);
        $em->flush();

        $this->addFlash('success', "L'article a bien été supprimé.");
        return $this->redirectToRoute('actualites');
    }
}
