<?php

namespace App\Form;

use App\Entity\Establishment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstablishmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('colorCss', ChoiceType::class, [
                'choices' => [
                    'Bleu' => 'blue',
                    'Rouge' => 'red',
                    'Jaune' => 'yellow',
                    'Cyan' => 'cyan',
                ],
                'placeholder' => 'Choisissez une couleur'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Establishment::class,
        ]);
    }
}
