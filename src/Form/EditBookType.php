<?php

namespace App\Form;

use App\Entity\BookRead;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class EditBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
         ->add('description', TextType::class, [
                'label' => 'Description',
                'data' => 'Default value',
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
                'data' => '1'

            ])
            ->add('is_read', CheckboxType::class, [
                'label' => 'Lecture terminée',
                'data' => true,
                'required' => false,
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
