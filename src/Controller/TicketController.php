<?php

namespace App\Controller;

use App\Service\StripeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Reservation;
use App\Form\ReservationFormType;
use App\Service\TicketTypeManager;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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


        // $allTicketsSole { show message }
        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);
        foreach ($reservation->getTickets() as $ticket) {
            $originalTickets->add($ticket);
        }


        if ($form->isSubmitted() && $form->isValid())
        {
            foreach ($originalTickets as $ticket) {
                $type = $ticketTypeManager -> calculateType(
                    $ticket->getType(),
                    $ticket->getBirthday(),
                    $reservation->getReservationDate()
                );
                $ticket->setType($type);
                $price = $ticketTypeManager -> guessPrice($type);
                $ticket->setPrice($price);

                $errors = $validator->validate($ticket);
                if (count($errors) > 0) {
                    /*
                     * Uses a __toString method on the $errors variable which is a
                     * ConstraintViolationList object. This gives us a nice string
                     * for debugging.
                     */
                    $errorsString = (string)$errors;

                    return new Response($errorsString);
                }

                $manager->persist($ticket);
                $ticket->setReservation($reservation);
            }
            $manager->persist($reservation);
            $manager->flush();
            if($originalTickets->isEmpty()== false)
            return $this->redirectToRoute('recap', ['id' => $reservation->getId()]);
        }
        return $this->render('ticket/ticket.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/recap/{id}", name="recap")
     * @ParamConverter("reservation")
     */
    public function recap( Reservation $reservation, TicketRepository $ticketRepository, TicketTypeManager $ticketTypeManager) {
        $tickets = $ticketRepository->findByReservation($reservation);
        $amountOfTickets=0;
        $typeName = 0;

        $dayOrHalfDay =  $ticketTypeManager -> dayOrHalfDay($reservation -> getReservationDate());
        foreach($tickets as $ticket) {
            $typeName = $ticketTypeManager -> nameType($ticket->getType());
            $amountOfTickets++;
        }
        $totalPrice = $ticketTypeManager -> calculatePrice($reservation, $ticketRepository);
        return $this->render('ticket/recapitulatif.html.twig', array(
            'reservation'=>$reservation,
            'typeName'=> $typeName,
            'dayOrHalfDay'=>$dayOrHalfDay,
            'totalPrice'=>$totalPrice,
            'amountOfTickets' => $amountOfTickets )
        );
    }

    /**
     * @Route *("/paiement/{id}" , name="paiement")
     * @ParamConverter("reservation")
     */
    public function paiement(StripeManager $stripeManager ,Reservation $reservation, TicketTypeManager $ticketTypeManager, TicketRepository $ticketRepository, ObjectManager $manager )
    {
        $charge = $stripeManager -> stripePayment($reservation, $ticketTypeManager, $ticketRepository);
        if($charge['paid'] != true){
        dump($charge);}
        $reservation->setIsPaid('true');
        $manager->flush();
        return $this->redirectToRoute('registration', ['id' => $reservation->getId()]);

    }

    /**
     * @Route *("/registration/{id}" , name="registration")
     * @ParamConverter("reservation")
     */
    public function registration(Reservation $reservation, \Swift_Mailer $mailer)
    {
        $mail = $reservation->getEmailAddress();
        $message = (new \Swift_Message('Billet pour le Musée du Louvre'))
            ->setFrom('send@example.com')
            ->setTo($mail)
            ->setBody(
                $this->renderView(
                    'ticket/registration.html.twig',['name'=>$mail]
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    ['name' => $name]
                ),
                'text/plain'
            )
            */
        ;
        $mailer -> send($message);
        return $this->render('ticket/confirmation.html.twig',['mail'=>$mail]);
    }
}
