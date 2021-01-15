<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Form\ActualiteType;
use App\Repository\ActualiteRepository;
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
            "actualites"=>$actualites,
        ]);
    }


    /**
     * @Route("/actualite/{id}", name="actualite", requirements={"id":"\d+"})
    */
    public function actualite(ActualiteRepository $actualiteRepository, $id): Response
    {

        $actualite = $actualiteRepository->find($id);

        return $this->render('actualite/actualite.html.twig', [
            "actualite"=>$actualite,
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
                
                $this->addFlash('success', "L'article a bien été enregistré.");
                return $this->redirectToRoute('actualites');
            }else {
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
     * @Route("/admin/supprimer/{id}", name="supprimer")
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
