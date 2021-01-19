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
               //dd($dateisFree);
                     if($dateisFree) 
                    {

                         $reservation -> setDateReservation( $date_reservation);          
    
                         $em = $this->getDoctrine()->getManager();
                         $em->persist($reservation);
                         $em->flush();

                         $emailService->send([            
                            'replyTo' => $reservation->getMail(),
                            'subject' => "Emotionnelles-Vous avez un message",
                            'template' => 'email/reservation.email.twig',
                            'context' => [
                                'reservation' => $reservation                              
                            ]
                        ]); 
            
                        $emailService->send([
                            'to' => $reservation->getMail(),
                            'subject' => "Nous avons bien reçu votre message",
                            'template' => 'email/reservation_confirm.email.twig',
                            'context' => [
                                'reservation' => $reservation
                            ]               
                        ]);     

                         $this->addFlash('success', "Le message a bien été enregistré.");
                            return $this->redirectToRoute('actualites');
                         }
                        else
                        {
                         $this->addFlash('danger', "La date est déja prise.");
                         $form->get('heure_reservation')->addError(new FormError('cet horaire est deja pris'));
                         }

             
                        }    
                        else {
                                $this->addFlash('danger', "Le formulaire comporte des erreurs.");
                                }
                }
                            $date = $form->get('date_reservation')->getData(); // 2020-10-02
                            $heure = $form->get('heure_reservation')->getData(); // 09
                        
                return $this->render('reservation/reservation.html.twig', [
                    "form" => $form->createView(),
                    "reservation" => $reservation,
                ]);
                
            }

        }
