<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
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
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TicketController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function homePage(ReservationRepository $reservationRepository)
    {
        $soldTickets= $reservationRepository->countSoldTickets();
        $limit= 1000;
        $soldTickets=$soldTickets['totalSoldTickets'];
        $limitReached= $soldTickets > $limit;
        return $this->render('ticket/home.html.twig',['limitReached'=>$limitReached]);
    }


    /**
     * @Route("/reservation", name="reservation")
     */
    public function booking(Request $request, ObjectManager $manager, TicketTypeManager $ticketTypeManager, ValidatorInterface $validator)
    {
        $reservation = new reservation();
        $originalTickets = new ArrayCollection();
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

        $dayOrHalfDay =  $ticketTypeManager -> dayOrHalfDay($reservation -> getReservationDate());
        foreach($tickets as $ticket) {
            $typeName = $ticketTypeManager->nameType($ticket->getType());
            $amountOfTickets++;
            $tarifs[] = $typeName;
        }
        $totalPrice = $ticketTypeManager -> calculatePrice($reservation, $ticketRepository);
        return $this->render('ticket/recapitulatif.html.twig', array(
            'reservation'=>$reservation,
            'typeName'=> $tarifs,
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
        $isPaid = $stripeManager -> stripePayment($reservation, $ticketTypeManager, $ticketRepository);
        if($isPaid != true){
            $this->addFlash(
                'notice',
                'Une erreur est survenu lors du paiement... Veuillez réeessayer'
            );

            return $this->redirectToRoute('recap', ['id' => $reservation]);
        }
        $reservation->setIsPaid('true');
        $manager->flush();
        return $this->redirectToRoute('registration', ['id' => $reservation->getId()]);

    }

    /**
     * @Route *("/registration/{id}" , name="registration")
     * @ParamConverter("reservation")
     */
    public function registration(Reservation $reservation, \Swift_Mailer $mailer, TicketRepository $ticketRepository, TicketTypeManager $ticketTypeManager)
    {
        $mail = $reservation->getEmailAddress();
        $tickets = $ticketRepository->findByReservation($reservation);
        $amountOfTickets=0;

        $dayOrHalfDay =  $ticketTypeManager -> dayOrHalfDay($reservation -> getReservationDate());
        foreach($tickets as $ticket) {
            $typeName = $ticketTypeManager -> nameType($ticket->getType());
            $amountOfTickets++;
            $tarifs[] = $typeName;
        }
        $message = (new \Swift_Message('Billet pour le Musée du Louvre'))
            ->setFrom('send@example.com')
            ->setTo($mail)
            ->setBody(
                $this->renderView(
                    'ticket/registration.html.twig',['name'=>$mail,
                        'reservation'=>$reservation,
                        'typeName'=> $tarifs,
                        'dayOrHalfDay'=>$dayOrHalfDay,
                        'amountOfTickets' => $amountOfTickets]
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


    public function logs(LoggerInterface $logger)
    {
        $logger->info('I just got the logger');
        $logger->error('An error occurred');

        $logger->critical('Critical message', [
            // include extra "context" info in your logs
            'cause' => 'in_hurry',
        ]);

        // ...
    }

 //   public function sendMail()
}
