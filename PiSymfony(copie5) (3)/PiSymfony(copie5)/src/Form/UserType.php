<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {       
         $isEdit = $options['is_edit'];
         $password = $options['password'];


        $builder
        
        ->add('email',TextType::class, ['attr'=>['class' => 'form-control input-sm','placeholder'=>"E-mail"]])
  
            ->add('nom',TextType::class, ['attr'=>['class' => 'form-control input-sm','placeholder'=>"Prénom"]])
            ->add('prenom',TextType::class, ['attr'=>['class' => 'form-control input-sm','placeholder'=>"Nom"]]);
                    
            if ($password) {
                $builder->add('motDePasse', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'mapped' => false,
                    'invalid_message' => 'The password fields must match.',
                    'required' => true,
                    'first_options' => [
                        'label' => 'Password', // Added label for the first password field
                        'attr' => ['class' => 'form-control', 'placeholder' => "Mot de passe"],
                    ],
                    'second_options' => [
                        'label' => false, // Removed label for the second password field
                        'attr' => ['class' => 'form-control', 'placeholder' => "Confirmer Mot de passe"],
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ]);
            }
             

            $builder->add('numeroTelephone',NumberType::class, ['attr'=>['class' => 'form-control input-sm','placeholder'=>"GSM"]])
            ->add( 'dateNaissance',
            DateType::class,[
            'html5'  => false,
            'mapped'=> false,
            'format' => 'dd-MM-yyyy']
            , ['attr'=>['class' => 'form-control js-datepicker','placeholder'=>"Date de naissance"]])
           
           
                ->add('genre', ChoiceType::class, array('choices' => array('Autre' => 'Autre','Homme' => 'Homme', 'Femme' => 'Femme')), ['attr'=>['class' => 'form-control h50','placeholder'=>"Nom"]])
            
        ;
        
        if (!$isEdit) {
            $builder->add('role', ChoiceType::class, [
                'choices' => [
                    'ADMIN' => 'ADMIN',
                    'GUIDE' => 'GUIDE',
                    'CLIENT' => 'CLIENT',
                ],
                'label' => 'Role',
                'required' => true,
                'placeholder' => 'Sélectionnez une role',
            ]);
        }
         
            
            $builder->add('save', SubmitType::class, ['attr'=>['class' => 'btn btn-primary']]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
            'password' => true,
        ]);
    }

   
}
