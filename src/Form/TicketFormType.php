<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class TicketFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class,[
                'attr'=> [
                    'placeholder'=>'Votre Prénom',
                    'autocomplete' => 'on'
                ], 'label'=>'Prénom'
            ])

            ->add('lastName',TextType::class,[
                'attr'=> [
                    'placeholder'=>'Nom',
                    'autocomplete' => 'on'
                ], 'label'=>'Nom de famille'
            ])

            ->add('birthday',  BirthdayType::class,  [
                'widget' => 'choice',
                'label'=>'Date de naissance',
                'format' => 'dd-MM-yyyy',
            ])

            ->add('country', CountryType::class, [
                'placeholder' => 'France',
                'label'=>'Pays'
            ])
                
            ->add('type',CheckboxType::class,[
                'required'=>false,
                'label'=>'Tarif réduit ( Un justificatif sera demandé) '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
