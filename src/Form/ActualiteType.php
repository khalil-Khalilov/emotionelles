<?php

namespace App\Form;

use App\Entity\Actualite;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActualiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class,[
                'required' => false,
                'label' => 'Upload Image',
                'mapped' => false,
            ])
            ->add('titre')
            ->add('description')
            ->add('lieu')
            ->add('date')
            ->add('facebookEvent')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Actualite::class,
        ]);
    }
}
