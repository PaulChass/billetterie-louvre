<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Ticket;
use App\Form\TicketFormType;
use App\Form\FlatpickrType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reservationDate', FlatpickrType::class)
        ;

        $builder->add('tickets', CollectionType::class, array(
            'entry_type' => TicketFormType::class,
            'entry_options' => array('label' => false),
            'allow_add' => true,
        ));
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
