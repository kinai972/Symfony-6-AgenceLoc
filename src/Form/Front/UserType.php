<?php

namespace App\Form\Front;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'email', type: TextType::class, options: [
                'label' => "Adresse électronique :",
                'attr' => [
                    'placeholder' => "Saisir l'adresse électronique",
                ]
            ])
            ->add(child: 'username', type: TextType::class, options: [
                'label' => "Pseudo :",
                'attr' => [
                    'placeholder' => "Saisir le pseudo",
                ]
            ])
            ->add(child: 'firstName', type: TextType::class, options: [
                'label' => "Prénom :",
                'attr' => [
                    'placeholder' => "Saisir le prénom",
                ]
            ])
            ->add(child: 'lastName', type: TextType::class, options: [
                'label' => "Nom :",
                'attr' => [
                    'placeholder' => "Saisir le nom",
                ]
            ])
            ->add(child: 'gender', type: ChoiceType::class, options: [
                'label' => "Civilité :",
                'choices' => [
                    'Homme' => 'm',
                    'Femme' => 'f',
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe :',
                    'attr' => [
                        'placeholder' => "Saisir le mot de passe",
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe :',
                    'attr' => [
                        'placeholder' => "Confirmer le mot de passe",
                    ]
                ],
                'invalid_message' => 'Les mots de passe ne sont pas identiques.',
                'required' => in_array('password', $options['validation_groups'] ?? []),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
