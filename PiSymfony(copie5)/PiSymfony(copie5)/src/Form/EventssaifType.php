<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Eventssaif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventssaifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('destinationsaif')
            ->add('titlesaif')
            ->add('descriptionsaif')
            ->add('durationsaif')
            ->add('imagesaif')
            ->add('prixsaif')
            ->add('typesaif')
            ->add('comment', SubmitType::class);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eventssaif::class,
        ]);
    }
}
