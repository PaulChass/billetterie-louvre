<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Reservation;
use App\Form\ReservationFormType;
use App\Entity\Ticket;
use App\Service\Calculator;
use Doctrine\Common\Collections\ArrayCollection;



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
    
    public function new(Request $request, ObjectManager $manager, Calculator $calculator)
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
                $checkbox = $ticket->getType();
                $birthday = $ticket->getBirthday();
                $time = $reservation->getReservationDate();
                $type = $calculator -> calculateType($checkbox,$birthday,$time);
                $ticket->setType($type);
                $price = $calculator -> calculatePrice($type);
                $ticket->setPrice($price);
                $manager->persist($ticket); 
                $ticket->setReservation($reservation); 
            }
            $manager->persist($reservation);  
            $manager->flush(); 
            
            // ... maybe do some form processing, like saving the Ticket and Reservation objects
        }
        return $this->render('ticket/ticket.html.twig', array(
            'form' => $form->createView(),
        ));
    

    // render some form template
}
    /*
    public function reservationPage(Request $requestdie;, ObjectManager $manager)
    {
        $Reservation = new Reservation();

        $form = $this->createFormBuilder($Reservation)
                     ->add('reservationDate')
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $Reservation->setCreatedAt(new \DateTime());

            $manager->persist($Reservation);
            $manager->flush();
           
            return $this->redirectToRoute('billet'); 
        }   
        elseif($form->isSubmitted())
        {
            return $this->redirectToRoute('billet'); 
        }
        return $this->render('ticket/reservation.html.twig', ['form'=>$form->createView()
        ]);
    }
    /*

    /**
     * @Route("/billet", name="billet")
     */
    public function billetPage()
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketFormType::class,$ticket);
        

        return $this->render('ticket/ticket.html.twig');
    }

}
