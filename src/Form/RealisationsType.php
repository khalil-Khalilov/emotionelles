<?php

namespace App\Form;

use App\Entity\Realisations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RealisationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image')
            ->add('nom_produit')
            ->add('prix')
            ->add('image')
            ->add('caracteristique')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Realisations::class,
        ]);
    }
}
