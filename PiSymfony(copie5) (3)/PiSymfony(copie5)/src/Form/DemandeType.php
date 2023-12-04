<?php

namespace App\Form;

use App\Entity\Demande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Validator\Constraints as Assert;

class DemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('destination',TextareaType::class ,[
                'attr' => ['class' => 'form-control','placeholder'=>'Destination'],
            ])
            ->add('type',TextareaType::class ,[
                'attr' => ['class' => 'form-control','placeholder'=>'Type of Trip'],
            ])
            ->add('duration', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Duration in days'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\LessThanOrEqual([
                        'value' => 30,
                        'message' => 'Duration must not exceed 30 days.',
                    ]),
                ],
            ])
            ->add('description',TextareaType::class ,[
                'attr' => ['class' => 'form-control','placeholder'=>'Description'],
            ])
            ->add('Add', SubmitType::class  ,[
                'attr' => ['class' => 'btn btn-block sent-butnn'],
            ]);
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
