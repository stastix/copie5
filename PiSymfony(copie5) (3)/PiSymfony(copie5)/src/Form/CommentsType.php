<?php
// src/Form/CommentsType.php

namespace App\Form;

use App\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\EventListener\BadWordsListener;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('context', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'context'],
            ])
            ->add('comment', SubmitType::class, [
                'attr' => ['class' => 'btn btn-block sent-butnn'],
            ]);

        
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $badWordsListener = new BadWordsListener();
            $badWordsListener->onFormSubmit($event);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}

