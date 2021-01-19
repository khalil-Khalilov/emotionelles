<?php

namespace App\Controller;

use App\Entity\Realisations;
use App\Form\RealisationsType;
use App\Repository\RealisationsRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RealisationsController extends AbstractController
{
    /**
     * @Route("/realisations", name="realisations")
     */
    public function realisations(RealisationsRepository $realisationsRepository): Response
    {
        $realisations = $realisationsRepository->findAll();
        
        return $this->render('realisations/realisations.html.twig', [
            "realisations" => $realisations,
        ]);
    }

    /**
     * @Route("/realisation/{id}", name="realisation", requirements={"id":"\d+"})
     */
    public function produit(RealisationsRepository $realisationsRepository, $id): Response
    {
        $realisation = $realisationsRepository->find($id);
        
        return $this->render('realisations/realisation.html.twig',[
            "realisation" => $realisation,
        ]);
    }

    /**
     * @Route("/realisations/back", name="realisationsBack")
     */
    public function realisationBack(Request $request): Response 
    {
        $realisation = new RealisationsType();

        $form = $this->createForm(RealisationType::class, $realisation);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($realisation);
                $em->flush();

                $this->addFlash('success', "La réalisation a bien été enregistré.");
                
                return $this->redirectToRoute('realisations');
            }

            else 

            {
                $this->addFlash('danger', "Le formulaire comporte des erreurs.");
            }
        }

        return $this->render('realisation/realisationsBack.html.twig', [
            "form" => $form->createView(),
            "realisation" => $realisation,
        ]);
    }

}
