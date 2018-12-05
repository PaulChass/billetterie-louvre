<?php 

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class FlatpickrType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'widget' => 'single_text',
            'attr' => array(
                'placeholder' => 'Choisissez la date de visite',
                'class' => "flatpickr datetime",
            ),
        ));
    }

    public function getParent()
    {
        return DateTimeType::class;
    }
}   
