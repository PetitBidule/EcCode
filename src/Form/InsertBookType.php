<?php

namespace App\Form;

use App\Entity\BookRead;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class InsertBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // creation du formulaire pour l'ajout de livre lu ou non
        $builder
            ->add('description', TextType::class, [
                'label' => 'Description',
            ])
            ->add('rating', ChoiceType::class, [
                'label' => 'Note',
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
            ])
            ->add('is_read', CheckboxType::class, [
                'label' => 'Lecture terminée',
                'required' => false,
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => BookRead::class,

        ]);
    }
}
