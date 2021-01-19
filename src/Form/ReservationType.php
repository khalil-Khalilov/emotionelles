<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('mail')
            ->add('numero_tel')
            ->add('date_reservation',DateType::class, [
                'widget'=> "single_text"

            ])
            ->add('heure_reservation',ChoiceType::class, [
                'choices'  => [
                    '09:00' => "09",
                    '10:00' => "10",
                    '11:00' => "11",
                    '14:00' => "14",
                    '15:00' => "15",
                    '16:00' => "16",
                ],
                'mapped' => false
            ])
            ->add('motif')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
