<?php

namespace App\Controller;

use App\Service\StripeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Reservation;
use App\Form\ReservationFormType;
use App\Service\TicketTypeManager;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\ReservationRepository;
use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\Response;

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
    public function new(Request $request, ObjectManager $manager, TicketTypeManager $ticketTypeManager, ValidatorInterface $validator)
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

                $errors = $validator->validate($ticket);
                if (count($errors) > 0) {
                    /*
                     * Uses a __toString method on the $errors variable which is a
                     * ConstraintViolationList object. This gives us a nice string
                     * for debugging.
                     */
                    $errorsString = (string) $errors;

                    return new Response($errorsString);
                }
                $manager->persist($ticket);
                $ticket->setReservation($reservation);
            }
            $manager->persist($reservation);
            $manager->flush();
            return $this->redirectToRoute('recap', ['id' => $reservation->getId()]);
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
        $typeName = 0;
        $amountOfTickets=0;
        $dayOrHalfDay =  $ticketTypeManager -> dayOrHalfDay($reservation -> getReservationDate());
        foreach($tickets as $ticket) {
            $typeName = $ticketTypeManager -> nameType($ticket->getType());
            $totalPrice=$totalPrice + $ticket->getPrice();
            $amountOfTickets++;
        }
        return $this->render('ticket/recapitulatif.html.twig', array(
            'reservation'=>$reservation,
            'typeName'=> $typeName,
            'dayOrHalfDay'=>$dayOrHalfDay,
            'totalPrice'=>$totalPrice,
            'amountOfTickets' => $amountOfTickets )
        );
    }

    /**
     * @Route *("/paiement" , name="paiement")
     */
    public function paiement(StripeManager $stripeManager)
    {
        $customer = $stripeManager -> createCustomer();
        $card = $stripeManager -> createCard($customer['id']);
        $charge = $stripeManager -> createCharge();
        if($charge['status'] != 'succeeded'){
            $this->redirectToRoute('home');
        }
        return $this->render('ticket/confirmation.html.twig', array(
            'card' => $card,
            "charge" => $charge
        ));
    }
}
