<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Reservation;
use App\Form\ReservationFormType;
use App\Entity\Ticket;
use App\Service\TicketTypeManager;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\ReservationRepository;
use App\Repository\TicketRepository;

class TicketController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function homePage()
    {
        return $this->render('ticket/home.html.twig');
    }

    
    /**
     * @Route("/reservation", name="reservation")
     */    
    public function new(Request $request, ObjectManager $manager, TicketTypeManager $ticketTypeManager)
    {
        $reservation = new reservation();
        $originalTickets = new ArrayCollection();
        
        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);
        foreach ($reservation->getTickets() as $ticket) {
            $originalTickets->add($ticket);
        }
       

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalTickets as $ticket) {
                $type = $ticketTypeManager -> calculateType(
                    $ticket->getType(),
                    $ticket->getBirthday(),
                    $reservation->getReservationDate()
                );
                $ticket->setType($type);
                $price = $ticketTypeManager -> calculatePrice($type);
                $ticket->setPrice($price);
                $manager->persist($ticket); 
                $ticket->setReservation($reservation); 
            }
            $manager->persist($reservation);  
            $manager->flush(); 
            return $this->redirectToRoute('recap', ['id' => $reservation->getId()]);
            
            
            // ... maybe do some form processing, like saving the Ticket and Reservation objects
        }
        return $this->render('ticket/ticket.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/recap/{id}", name="recap")
     */
    public function recap($id, ReservationRepository $reservationRepository, TicketRepository $ticketRepository, TicketTypeManager $ticketTypeManager) {
        $reservation = $reservationRepository->find($id);
        $tickets = $ticketRepository->findByReservation($reservation);
        $totalPrice=0;
        $amountOfTickets=0;
        foreach($tickets as $ticket)
        {
        $typeName = $ticketTypeManager -> nameType($ticket->getType());
        $totalPrice=$totalPrice + $ticket->getPrice();
        $amountOfTickets++;
        }
        $dayOrHalfDay =  $ticketTypeManager -> dayOrHalfDay($reservation->getReservationDate());
        return $this->render('ticket/recapitulatif.html.twig', array(
            'reservation'=>$reservation,
            'typeName'=>$typeName, 
            'dayOrHalfDay'=>$dayOrHalfDay, 
            'totalPrice'=>$totalPrice,
            'amountOfTickets' => $amountOfTickets )
        );
    }
}
