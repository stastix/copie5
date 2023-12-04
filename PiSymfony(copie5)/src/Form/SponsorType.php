<?php

namespace App\Form;

use App\Entity\Sponsor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SponsorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomsponsor')
            ->add('secteurdactivite')
            ->add('adressesponsor')
            ->add('numtelsponsor', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'pattern' => '^(\+?\d{1,3}[\s-]?)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$',
                    'title' => 'Entrez un numéro de téléphone valide'
                ]
            ])
            ->add('emailsponsor')
            ->add('duree');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Configure your entity class
        $resolver->setDefaults([
            'data_class' => Sponsor::class,
        ]);
    }
}

