<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Establishment;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', MoneyType::class, [
                'divisor' => 100,
            ])
            ->add('title')
            ->add('description')
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Virement' => 'outcome',
                    'Versement' => 'income'
                ],
                'placeholder' => 'Choisissez une option',
            ])
            ->add('establishment', EntityType::class, [
                'class' => Establishment::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un établissement bancaire',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez une catégorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
