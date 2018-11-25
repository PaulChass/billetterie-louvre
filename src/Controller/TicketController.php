<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Reservation;

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
     * @Route("/billet", name="billet")
     */
    public function billetPage()
    {
        return $this->render('ticket/ticket.html.twig');
    }

    /**
     * @Route("/reservation", name="reservation")
     */
    public function reservationPage(Request $request, ObjectManager $manager)
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
           
            return $this->redirectToRoute('/billet'); 
        }   
        elseif($form->isSubmitted())
        {
            return $this->billetPage();
        }
        return $this->render('ticket/reservation.html.twig', ['form'=>$form->createView()
        ]);
    }
}
