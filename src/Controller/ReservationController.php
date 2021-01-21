<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;

use App\Service\EmailService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="reservation")
     */

    public function contact(EmailService $emailService, Request $request, ReservationRepository $reservationRepository): Response
    {         
        $reservation = new Reservation();
        
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            
            if($form->isValid()){

                $date = $form->get('date_reservation')->getData(); // 2020-10-02
                $heure = $form->get('heure_reservation')->getData(); // 09
                $date_reservation = new \DateTime($date->format("Y-m-d")." ".$heure.":00:00");
                $dateisFree = $reservationRepository->isdateFree ($date_reservation);
        
                if($dateisFree){

                    $reservation->setDateReservation($date_reservation);          

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($reservation);
                    $em->flush();

                    $emailService->send([            
                        'replyTo' => $reservation->getMail(),
                        'subject' => "Vous avez une nouvelle réservation - Emotion'elles",
                        'template' => 'email/reservation.email.twig',
                        'context' => [
                            'reservation' => $reservation                              
                        ]
                    ]); 
    
                    $emailService->send([
                        
                        'to' => $reservation->getMail(),
                        'subject' => "Nous avons bien noté votre réservation - Emotion'elles",
                        'template' => 'email/reservation_confirm.email.twig',
                        'context' => [
                            'reservation' => $reservation
                        ]               
                    
                        ]);     

                    $this->addFlash('success', "La réservation a bien été enregistré.");
                    
                    return $this->redirectToRoute('actualites');
                
                }
                
                else
                {
                
                    $this->addFlash('danger', "Oups, la date a déjà été choisi.");
                    $form->get('heure_reservation')->addError(new FormError('Désolée, mais cet horaire est déjà pris.'));
                
                }

            }    
            
            else 
            {
            
                $this->addFlash('danger', "Le formulaire comporte des erreurs.");
            
            }
        
        }
        
        $date = $form->get('date_reservation')->getData(); // 2020-10-02
        $heure = $form->get('heure_reservation')->getData(); // 09:00
                
        return $this->render('reservation/reservation.html.twig', [
            "form" => $form->createView(),
            "reservation" => $reservation,
        ]);
        
    }

}
