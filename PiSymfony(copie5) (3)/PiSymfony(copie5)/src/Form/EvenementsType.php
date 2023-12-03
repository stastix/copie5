<?php

namespace App\Form;

use App\Entity\Evenements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class EvenementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class)
            ->add('description', TextType::class)
            ->add('dateDepart', DateType::class)
            ->add('prix', MoneyType::class, [
                'currency' => 'TND',
            ])
            ->add('typeevenement', TextType::class)
            ->add('guideId', TextType::class)
            ->add('destination', CountryType::class, [
                // Définit le champ comme une liste déroulante de pays
                'placeholder' => 'Sélectionner un pays',
                // Personnalisez les options si nécessaire
            ])
            ->add('image', FileType::class, [
                'label' => 'Télécharger une image',
                'required' => false, // Selon votre besoin (si le champ est facultatif)
                // D'autres options comme la validation peuvent être ajoutées ici
            ])            
            ->add('sponsorevenement', ChoiceType::class, [
                'choices' => [
                    'Sponsor 1' => 'Sponsor 1',
                    'Sponsor 2' => 'Sponsor 2',
                    // Ajoutez d'autres sponsors ici
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenements::class,
        ]);
    }
}
