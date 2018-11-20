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
    public function index()
    {
        return $this->render('ticket/home.html.twig');
    }

    /**
     * @Route("/reservation", name="reservation")
     */
    public function homepage(Request $request, ObjectManager $manager)
    {
        $Reservation = new Reservation();

        $form = $this->createFormBuilder($Reservation)
                     ->add('reservationDate')
                     ->add('Envoyer',SubmitType::class )
                     ->getForm();


        return $this->render('ticket/reservation.html.twig', ['form'=>$form->createView()
        ]);
    }


}
