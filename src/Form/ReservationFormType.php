<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emailAddress', EmailType::class, [
                'attr'=> [
                    'placeholder' => 'exemple@mail.com',
                    'autocomplete' => 'on',
                ],
                'label' => 'Adresse mail'
            ])
            ->add('reservationDate', FlatpickrType::class ,
                ['label'=>'Date et heure de la visite'], ['required'=>"true"] )

            ->add('Recap & Paiement', SubmitType::class)
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
