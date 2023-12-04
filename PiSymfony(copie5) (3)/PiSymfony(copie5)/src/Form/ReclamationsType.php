<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ReclamationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
        ->add('CibleReclamation', ChoiceType::class, [
            'choices' => [
                'Customer Service Department' => 'Customer Service Department',
                'Reservation Manager or Department' => 'Reservation Manager or Department',
                'After-Sales Service Department' => 'After-Sales Service Department',
                'General Management or CEO' => 'General Management or CEO',
            ],
            'attr' => ['class' => 'form-control', 'placeholder' => 'Complaint Target'],
        ])
            ->add('dateReclamation', DateType::class, [
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Select date'],
            ])
            ->add('TextReclamation',TextareaType::class ,[
                'attr' => ['class' => 'form-control','placeholder'=>'complaint'],
            ])
            ->add('Add', SubmitType::class  ,[
                'attr' => ['class' => 'btn btn-block sent-butnn'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
